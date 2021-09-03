<?php

namespace App\Services;

use App\Events\ScreenChanges;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Library\Upload;
use App\Repositories\Contracts\AlbumRepositoryInterface;
use App\Repositories\Contracts\PhotoRepositoryInterface;
use App\Transformers\AlbumTransformer;

class AlbumService
{
    protected $repository;
    protected $userService;
    protected $carbon;
    protected $photoRepository;
    protected $companyService;

    public function __construct(
        AlbumRepositoryInterface $repository,
        Carbon $carbon,
        Upload $uploadPlugin,
        CompanyService $companyService,
        PhotoRepositoryInterface $photoRepository
    ) {
        $this->repository = $repository;
        $this->uploadPlugin = $uploadPlugin;
        $this->carbon = $carbon;
        $this->companyService = $companyService;
        $this->photoRepository = $photoRepository;
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

        $list = $this->repository->scopeQuery(function ($query) use ($filterColumnsPeriod, $filterColumns2) {
            return $query->where($filterColumnsPeriod)->orWhere($filterColumns2)->whereJsonContains('days_week',  strval($this->carbon->now()->dayOfWeek))->orderBy('created_at', 'DESC');
        });

        return (new AlbumTransformer)->transform($list->get());
    }

    public function list($request)
    {

        $filterColumns = $this->makeParamsFilter($request);

        return $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns);
        });
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


    private function makeParamsFilter($request)
    {
        $companyId = Auth::user()->company_id;
        $filterColumns = ['company_id' => $companyId];

        if (Arr::get($request, 'search_album_name')) {
            array_push($filterColumns, ['name', 'like', '%' . Arr::get($request, 'search_album_name') . '%']);
        }

        if (Arr::get($request, 'search_album_active')) {
            array_push($filterColumns, ['active', Arr::get($request, 'search_album_active')]);
        }

        return  $filterColumns;
    }

    public function find($taskId)
    {
        return $this->repository->find($taskId)->toArray();
    }

    private function addPhoto($request, $response, $property)
    {
        $foto = $request->file($property);
        $companyId = Auth::user()->company_id;
        $albumId = Arr::get($response, "id");
        $path = "photos/company/$companyId/album/$albumId/$property";

        if ($path && $foto) {
            $pathPhoto = $this->uploadPlugin->upload($foto, $path);
            if (!$pathPhoto) {
                return;
            }

            $campaign = $this->repository->find($albumId);
            $campaign[$property] = $pathPhoto;
            $campaign->save();
        }
    }

    public function addPhotoCampaign($request)
    {
        $fotos = $request->file('photos');

        if (empty($fotos)) {
            return redirect()->back()->with('message', 'Nenhuma foto enviada. Selecione as fotos clicando no botão "Fotos"');
        }

        $arrFotos = [];
        $companyId = Auth::user()->company_id;
        $albumId = Arr::get($request, "album_id");
        $path = "photos/company/$companyId/albums/$albumId/fotos";

        foreach ($fotos as $foto) {
            $newPhoto = [];
            $pathPhoto = $this->uploadPlugin->upload($foto, $path);

            if (!$pathPhoto) {
                continue;
            }
            
            $photoId = $this->photoRepository->updateOrCreate(['path' => $pathPhoto]);
            $newPhoto["photo_id"] = $photoId->id;
            $newPhoto["album_id"] = $albumId;
            array_push($arrFotos, $newPhoto);
        }

        $album = $this->repository->find($albumId);

        $album->photos()->attach(
            $arrFotos
        );

        broadcast(new ScreenChanges(Auth::user()->company_id))->toOthers();
        return redirect()->back()->with('message', 'Registro criado/atualizado!');
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

    public function deletePhotoAlbum($request)
    {
        $id = Arr::get($request, "id");
        $photoFind =  $this->photoRepository->find($id);

        if (Arr::get($photoFind, 'company_id') == Auth::user()->company_id) {
            $response = $this->uploadPlugin->remove(Arr::get($photoFind, "path"));
            $photoFind->delete();

            if ($response) {
                return response('Removido com sucesso', 200);
            }
        }

        return response('Ocorreu algum erro ao remover', 422);
    }


    public function save($request)
    {
        if ($request->validated()) {
            $albumId = Arr::get($request, "id");

            if ($albumId) {
                if (!$this->checkCompany($albumId)) {
                    return redirect()->back()->with('message', 'Sem permissão para essa empresa');
                }

                $album = $this->repository->find($albumId);
                $foto = $request->file('background');

                if ($foto) {
                    $this->uploadPlugin->remove(Arr::get($album, "background"));
                }
            }

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

            $durationFrames = $request['duration_frames'] ?? false;
            if (!$durationFrames) {
                $request['duration_frames'] = 20;
            }

            if (Arr::exists($request->all(), "valid_at")) {
                $request['valid_at'] = $this->carbon->parse($request['valid_at'])->startOfDay();
            }

            if (Arr::exists($request->all(), "valid_from")) {
                $request['valid_from'] = $this->carbon->parse($request['valid_from'])->endOfDay();
            }

            $response = $this->repository->updateOrCreate(["id" => $albumId], $request->all());

            $this->addPhoto($request, $response, 'background');

            broadcast(new ScreenChanges(Auth::user()->company_id))->toOthers();

            if ($response) {
                return redirect()->back()->with('message', 'Registro criado/atualizado!');
            }

            return redirect()->back()->with('message', 'Ocorreu algum erro');
        }
    }

    public function delete($request)
    {
        $albumId = Arr::get($request, "id");

        if (!$this->checkCompany($albumId)) {
            return response('Sem permissão para essa empresa', 422);
        }
        $albumFind =  $this->repository->find($albumId);
        $this->uploadPlugin->remove(Arr::get($albumFind, "background"));

        $response = $this->repository->delete($albumId);
        if ($response) {
            return response("Removido com sucesso", 200);
        }

        return response('Ocorreu algum erro ao remover', 422);
    }

    private function checkCompany($albumId)
    {
        if ($albumId) {
            $companyId = Auth::user()->company_id;
            $companySale = $this->repository->find($albumId);
            if ($companyId != Arr::get($companySale, "company_id")) {
                return false;
            }
        }

        return true;
    }
}
