<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignValidator;
use Exception;
use Illuminate\Http\Request;

abstract class GenericController extends Controller
{
    protected $service;
    protected $page;
    protected $breadcrumbs;

    public function __construct($service, $page, $breadcrumbs = [])
    {
        $this->service = $service;
        $this->page = $page;
        $this->breadcrumbs = $breadcrumbs;
    }

    public function index(Request $request)
    {
        try {
            $pageConfigs = ['pageHeader' => true];
            return view($this->page, [
                "datas" => $this->service->search($request),
                'pageConfigs' => $pageConfigs,
                "search" => $request->all(),
                "urlAws" => ENV('AWS_URL'),
            ], ['breadcrumbs' =>  $this->breadcrumbs]);
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
