<?php

namespace App\Services;

use App\Events\ScrapChanges;
use App\Events\ScreenChanges;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Repositories\Contracts\CampaignRepositoryInterface;
use App\Library\Upload;
use App\Repositories\Contracts\ScrapRepositoryInterface;
use App\Transformers\CampaignTransformer;
use App\Transformers\ScrapTransformer;

class ScrapService
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
        ScrapRepositoryInterface $repository,
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

    public function search()
    {
        $filterColumns = $this->makeParamsFilter();

        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        });

        return $list->paginate(10);
    }

    public function delete($request)
    {
        $scrapId = Arr::get($request, "id");

        if ($this->checkAuthor($scrapId)) {
            $response = $this->repository->delete($scrapId);
            if ($response) {
                broadcast(new ScrapChanges(Auth::user()->company_id))->toOthers();
                return response("Removido com sucesso", 200);
            }
        }

        return response('Ocorreu algum erro ao remover', 422);
    }

    public function list($request)
    {
        $filterColumns = $this->makeParamsFilterCompany($request);
        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns);
        });

        return (new ScrapTransformer)->transform($list->get());
    }

    private function makeParamsFilterCompany($request)
    {
        $companyId = Arr::get($request, 'id');
        $filterColumns = ['company_id' => $companyId];
        array_push($filterColumns, ['created_at', '>=', $this->carbon->now()->startOfDay()->format('Y-m-d H:i:s')]);
        return  $filterColumns;
    }


    private function makeParamsFilter()
    {
        $filterColumns = ['company_id' => Auth::user()->company_id];
        array_push($filterColumns, ['created_at', '>=', $this->carbon->now()->startOfDay()->format('Y-m-d H:i:s')]);
        array_push($filterColumns, ['user_id', Auth::user()->id]);
        return  $filterColumns;
    }

    public function find($taskId)
    {
        return $this->repository->find($taskId)->toArray();
    }

    public function save($request)
    {
        $token = $request->token;
        $search = [];
        array_push($search, ['token_screen', 'like', '%' . $token . '%']);
        $company = $this->companyService->searchField($search);

        if (!$company || (!Auth::user() && !$company['anonymus'])) {
            return redirect()->route('messages');
        }

        $request['company_id'] = $company['id'];

        if (Arr::exists($request->all(), "valid_from")) {
            $request['valid_from'] = $this->carbon->parse($request['valid_from'])->startOfDay();
        }

        $response = $this->repository->create($request->all());

        broadcast(new ScrapChanges($company['id']))->toOthers();

        if ($response) {
            return redirect()->back()->with('message', 'Mensagem enviada 😊');
        }

        return redirect()->back()->with('message', 'Ocorreu algum erro');
    }

    private function checkAuthor($scrapId)
    {
        $authorId = Auth::user()->id;
        $scrap = $this->repository->find($scrapId);
        if ($authorId != Arr::get($scrap, "user_id")) {
            return false;
        }

        return true;
    }
}
