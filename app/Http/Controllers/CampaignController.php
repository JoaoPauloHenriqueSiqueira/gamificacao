<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampaignValidator;
use App\Http\Requests\UserValidator;
use App\Services\CampaignService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    protected $service;
    protected $userService;

    public function __construct(CampaignService $service, UserService $userService)
    {
        $this->service = $service;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        try {
            $pageConfigs = ['pageHeader' => true];
            return view('pages.campaign', [
                "datas" => $this->service->search($request),
                "users" => $this->userService->getAll($request),
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
            return view('pages.campaign_not_adm', [
                "datas" => $this->service->search($request),
                "users" => $this->userService->getAll($request),
                'pageConfigs' => $pageConfigs,
                "search" => $request->all(),
                "urlAws" => ENV('AWS_URL'),
            ], ['breadcrumbs' =>  []]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function addMyUser(Request $request)
    {
        try {
            return $this->service->addMyUser($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteMyUser(Request $request)
    {
        try {
            return $this->service->deleteMyUser($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function addUsers(Request $request)
    {
        try {
            return $this->service->addUsers($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create(CampaignValidator $request)
    {
        try {
            return $this->service->save($request,false);
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
}
