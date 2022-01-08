<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreditCard;
use App\Services\CreditCardService;
use Exception;

class CreditCardController extends Controller
{
    protected $service;

    public function __construct(CreditCardService $service)
    {
        $this->service = $service;

    }

    public function save(CreditCard $request)
    {
        try {
            return $this->service->save($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function update(CreditCard $request)
    {
        try {
            return $this->service->update($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function updatePlanStatus($company)
    {
        return $this->service->updatePlanStatus($company);
    }
    

    public function delete()
    {
        try {
            return $this->service->delete();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function retry()
    {
        try {
            return $this->service->retry();
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
