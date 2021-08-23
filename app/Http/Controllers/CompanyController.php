<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company;
use App\Services\CompanyService;
use Exception;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    protected $service;

    public function __construct(CompanyService $service)
    {
        $this->service = $service;
    }

    public function update(Company $request)
    {
        try {
            return $this->service->save($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteLogo(Request $request)
    {
        try {
            return $this->service->deleteLogo($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function deleteBackground(Request $request)
    {
        try {
            return $this->service->deleteBackground($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
