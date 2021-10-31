<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignValidator;
use App\Services\AlbumService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    protected $service;

    public function __construct(AlbumService $service)
    {
        $this->service = $service;
    }

    /**
     * Renderiza view com usuarios
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $pageConfigs = ['pageHeader' => true];
            return view('pages.albums', [
                "datas" => $this->service->search($request),
                'pageConfigs' => $pageConfigs,
                "search" => $request->all(),
                "urlAws" => ENV('AWS_URL'),
            ], ['breadcrumbs' =>  []]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function indexNotAdm(Request $request)
    {
        try {
            $pageConfigs = ['pageHeader' => true];
            $userId = Auth::user()->id;

            return view('pages.albums_not_adm', [
                "datas" => $this->service->search($request),
                'myUser' => $userId,
                'pageConfigs' => $pageConfigs,
                "search" => $request->all(),
                "urlAws" => ENV('AWS_URL'),
            ], ['breadcrumbs' =>  []]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    
    public function addPhotos(Request $request)
    {
        try {
            return $this->service->addPhotoCampaign($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create(CampaignValidator $request)
    {
        try {
            return $this->service->save($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function delete(Request $request)
    {
        try {
            return $this->service->delete($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deletePhoto(Request $request)
    {
        try {
            return $this->service->deletePhoto($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deletePhotoAlbum(Request $request)
    {
        try {
            return $this->service->deletePhotoAlbum($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
