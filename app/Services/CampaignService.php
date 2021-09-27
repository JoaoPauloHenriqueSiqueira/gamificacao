<?php

namespace App\Services;

use App\Events\ScreenChanges;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Repositories\Contracts\CampaignRepositoryInterface;
use App\Library\Upload;
use App\Transformers\CampaignTransformer;

class CampaignService
{
    protected $repository;
    protected $userService;
    protected $companyService;
    protected $carbon;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        CampaignRepositoryInterface $repository,
        UserService $userService,
        CompanyService $companyService,
        Carbon $carbon,
        Upload $uploadPlugin
    ) {
        $this->repository = $repository;
        $this->userService = $userService;
        $this->companyService = $companyService;
        $this->uploadPlugin = $uploadPlugin;
        $this->carbon = $carbon;
    }

    public function search($request)
    {
        $filterColumns = $this->makeParamsFilter($request);

        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        });

        return $list->paginate(10);
    }

    public function searchScreen($id)
    {
        $filterColumnsPeriod = $this->makeParamsSearch($id);
        $filterColumns2 = $this->makeParamsSearch2($id);
        $filterColumns3 = $this->makeParamsSearch3($id);


        $list = $this->repository->scopeQuery(function ($query) use ($filterColumnsPeriod, $filterColumns2,$filterColumns3) {
            return $query->where($filterColumnsPeriod)->orWhere($filterColumns2)->orWhere($filterColumns3)->whereJsonContains('days_week',  strval($this->carbon->now()->dayOfWeek))->orderBy('created_at', 'DESC');
            // return $query->where($filterColumnsPeriod)->orderBy('created_at', 'DESC');
        });

        return (new CampaignTransformer)->transform($list->get(), $this->userService->searchBirthday($id));
    }

    public function list($request)
    {

        $filterColumns = $this->makeParamsFilter($request);

        return $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns);
        });
    }

    private function makeParamsFilter($request)
    {
        $companyId = Auth::user()->company_id;
        $filterColumns = ['company_id' => $companyId];

        if (Arr::get($request, 'search_campaign_name')) {
            array_push($filterColumns, ['name', 'like', '%' . Arr::get($request, 'search_campaign_name') . '%']);
        }

        if (Arr::get($request, 'search_campaign_active')) {
            array_push($filterColumns, ['active', Arr::get($request, 'search_campaign_active')]);
        }

        return  $filterColumns;
    }

    private function makeParamsSearch($id)
    {
        $filterColumns = ['company_id' => $id];
        $company = $this->companyService->find($id);
        //TODO -> se n]ao tiver company mostrar pagina 404
        if ($company) {
            array_push($filterColumns, ['valid_at', '<=', $this->carbon->now()->format('Y-m-d H:i:s')]);
            array_push($filterColumns, ['valid_from', '>=', $this->carbon->now()->format('Y-m-d H:i:s')]);
            array_push($filterColumns, ['active', 1]);
        }

        return  $filterColumns;
    }

   

    private function makeParamsSearch2($id)
    {
        $filterColumns = ['company_id' => $id];
        $company = $this->companyService->find($id);
        //TODO -> se n]ao tiver company mostrar pagina 404
        if ($company) {
            array_push($filterColumns, ['is_continuous', true]);
            array_push($filterColumns, ['active', 1]);
        }

        return  $filterColumns;
    }

    private function makeParamsSearch3($id)
    {
        $filterColumns = ['company_id' => $id];
        $company = $this->companyService->find($id);
        if ($company) {
            array_push($filterColumns, ['active', 1]);
            array_push($filterColumns, ['is_birthday', 1]);
        }

        return  $filterColumns;
    }


    public function find($taskId)
    {
        return $this->repository->find($taskId)->toArray();
    }

    public function addUsers($request)
    {
        $campaignId = Arr::get($request, "campaign_id");

        if ($campaignId) {
            //TODO -> VER RETORNOS VIEW IMPORTANTE COM MESSAGE AO INVÉS DE BACK
            if (!$this->checkCompany($campaignId)) {
                return redirect()->back()->with('message','Sem permissão para essa empresa');
            }

            if (empty(Arr::get($request, 'users'))) {
                return redirect()->back()->with('message','Nenhum usuário adicionado. Adicione um usuário clicando no botão ao lado direito');
            }

            $campaign = $this->repository->find($campaignId);

            $campaign->users()->detach();

            $users = [];
            if (is_array(Arr::get($request, 'users'))) {
                foreach (Arr::get($request, 'users') as $user) {
                    if ($this->userService->checkCompany($user)) {
                        $newUser = [];
                        $newUser['campaign_id'] = $campaignId;
                        $newUser['user_id'] = $user;
                        array_push($users, $newUser);
                    }
                }
            }
            $campaign->users()->attach($users);
            return redirect()->back()->with('message', 'Registro criado/atualizado!');
        }
    }


    public function save($request, $new = false)
    {
        if ($new) {
            return $this->repository->updateOrCreate($request);
        }

        if ($request->validated()) {
            $campaignId = Arr::get($request, "id");

            if ($campaignId) {
                if (!$this->checkCompany($campaignId)) {
                    return redirect()->back()->with('message', 'Sem permissão para essa empresa');
                }

                $campaign = $this->repository->find($campaignId);
                $foto = $request->file('background');

                if ($foto) {
                    $this->uploadPlugin->remove(Arr::get($campaign, "background"));
                }
            }
            $request['company_id'] = Auth::user()->company_id;

            $active = false;
            if (Arr::exists($request->all(), "active")) {
                $active = true;
            }
            $request['active'] = $active;

            $isContinuous = true;
            if (Arr::exists($request->all(), "is_not_continuous")) {
                $isContinuous = false;
            }
            $request['is_continuous'] = $isContinuous;

            if (Arr::exists($request->all(), "valid_from")) {
                $request['valid_from'] = $this->carbon->parse($request['valid_from'])->endOfDay();
            }

            $durationFrames = $request['duration_frames'] ?? false;
            if (!$durationFrames) {
                $request['duration_frames'] = 20;
            }

            $response = $this->repository->updateOrCreate(["id" => $campaignId], $request->all());
            $this->addPhoto($request, $response, 'background');

            broadcast(new ScreenChanges(Auth::user()->company_id))->toOthers();

            if ($response) {
                return redirect()->back()->with('message', 'Registro criado/atualizado!');
            }

            return redirect()->back()->with('message', 'Ocorreu algum erro');
        }
    }

    private function addPhoto($request, $response, $property)
    {
        $foto = $request->file($property);
        $companyId = Auth::user()->company_id;
        $campaignId = Arr::get($response, "id");
        $path = "photos/company/$companyId/campaign/$campaignId/$property";

        if ($path && $foto) {
            $pathPhoto = $this->uploadPlugin->upload($foto, $path);
            if (!$pathPhoto) {
                return;
            }

            $campaign = $this->repository->find($campaignId);
            $campaign[$property] = $pathPhoto;
            $campaign->save();
        }
    }

    public function delete($request)
    {
        $campaignId = Arr::get($request, "id");

        if (!$this->checkCompany($campaignId)) {
            return response('Sem permissão para essa empresa', 422);
        }

        $campaignFind =  $this->repository->find($campaignId);

        $birthday = Arr::get($campaignFind, "is_birthday");
        if ($birthday) {
            return response("Campanha de aniversário não pode ser removida, apenas desativada", 422);
        }

        $this->uploadPlugin->remove(Arr::get($campaignFind, "background"));

        $response = $this->repository->delete($campaignId);
        if ($response) {
            return response("Removido com sucesso", 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }

    public function deletePhoto($request)
    {
        $id = Arr::get($request, "id");
        $data =  $this->repository->find($id);

        if (Arr::get($data, 'company_id') == Auth::user()->company_id) {
            $response = $this->uploadPlugin->remove(Arr::get($data, "background"));
            if(!$response){
                \Log::info("Tentativa de remover foto bucket deu erro:".Arr::get($data, "background"));
            }
            $data['background'] = null;
            $data->save();
            return response('Removido com sucesso', 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }


    private function checkCompany($campaignId)
    {
        if ($campaignId) {
            $companyId = Auth::user()->company_id;
            $companySale = $this->repository->find($campaignId);
            if ($companyId != Arr::get($companySale, "company_id")) {
                return false;
            }
        }

        return true;
    }
}
