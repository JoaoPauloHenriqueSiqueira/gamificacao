<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountUserValidator;
use App\Http\Requests\UserValidator;
use App\Services\CompanyService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $service;

    public function __construct(UserService $service, CompanyService $companyService)
    {
        $this->service = $service;
        $this->companyService = $companyService;
    }

    /**
     * Renderiza view com usuarios
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            $company = $this->companyService->find(Auth::user()->company_id);
            $pageConfigs = ['pageHeader' => true];
            return view('pages.user', [
                "datas" => $this->service->search($request),
                "password_default" => $company['password_default'],
                'pageConfigs' => $pageConfigs,
                "search" => $request->all(),
                "urlAws" => ENV('AWS_URL'),
            ], ['breadcrumbs' =>  []]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updateAccount(AccountUserValidator $request)
    {
        try {
            return $this->service->save($request, false, true);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updatePicture(Request $request)
    {
        try {
            return $this->service->savePicture($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function create(UserValidator $request)
    {
        try {
            return $this->service->save($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(UserValidator $request)
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
}
