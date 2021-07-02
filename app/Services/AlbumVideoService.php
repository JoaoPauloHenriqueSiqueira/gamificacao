<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Library\Upload;
use App\Repositories\Contracts\AlbumVideosRepositoryInterface;
use App\Repositories\VideoRepository;

class AlbumVideoService
{
    protected $repository;
    protected $userService;
    protected $videoRepository;
    protected $carbon;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        AlbumVideosRepositoryInterface $repository,
        Carbon $carbon,
        Upload $uploadPlugin,
        VideoRepository $videoRepository
    ) {
        $this->repository = $repository;
        $this->videoRepository = $videoRepository;
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

    private function makeParamsFilter($request)
    {
        $companyId = Auth::user()->company_id;
        $filterColumns = ['company_id' => $companyId];

        if (Arr::get($request, 'search_campaign_name')) {
            array_push($filterColumns, ['name', 'like', '%' . Arr::get($request, 'search_campaign_name') . '%']);
        }

        return  $filterColumns;
    }

    public function find($taskId)
    {
        return $this->repository->find($taskId)->toArray();
    }

    public function addVideos($request)
    {
        $campaignId = Arr::get($request, "album_id");
        if ($campaignId) {
            if (!$this->checkCompany($campaignId)) {
                return response('Sem permissão para essa empresa', 422);
            }

            $campaign = $this->repository->find($campaignId);

            foreach ($campaign->videos as $video) {
                $videoFind = $this->videoRepository->find($video->id);
                $videoFind->delete();
            }

            $campaign->videos()->detach();

            $videos = [];
            if (is_array(Arr::get($request, 'videos'))) {

                foreach (Arr::get($request, 'videos') as $video) {
                    $videoSave = [];
                    $videoSave["company_id"] = Auth::user()->company_id;
                    $videoSave["path"] = $video;
                    $videoId = $this->videoRepository->updateOrCreate($videoSave);

                    $newVideo = [];
                    $newVideo['album_video_id'] = $campaignId;
                    $newVideo['video_id'] = $videoId->id;
                    array_push($videos, $newVideo);
                }
            }

            $campaign->videos()->attach($videos);
            return redirect()->back()->with('message', 'Registro criado/atualizado!');
        }
        return redirect()->back()->with('message', 'Ocorreu algum erro');
    }


    public function save($request)
    {
        if ($request->validated()) {
            $campaignId = Arr::get($request, "id");
            $companyId = Auth::user()->company_id;

            if ($campaignId) {
                if (!$this->checkCompany($campaignId)) {
                    return response('Sem permissão para essa empresa', 422);
                }

                $campaign = $this->repository->find($campaignId);
                $foto = $request->file('background');

                if ($foto) {
                    $this->uploadPlugin->remove(Arr::get($campaign, "background"));
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

            if (Arr::exists($request, "valid_at")) {
                $request['valid_at'] = $this->carbon->parse($request['valid_at'])->startOfDay();
            }

            if (Arr::exists($request, "valid_from")) {
                $request['valid_from'] = $this->carbon->parse($request['valid_from'])->endOfDay();
            }

            $response = $this->repository->updateOrCreate(["id" => $campaignId], $request->all());
            $this->addPhoto($request, $response, 'background');

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
        $this->uploadPlugin->remove(Arr::get($campaignFind, "background"));

        $response = $this->repository->delete($campaignId);
        if ($response) {
            return response("Removido com sucesso", 200);
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
