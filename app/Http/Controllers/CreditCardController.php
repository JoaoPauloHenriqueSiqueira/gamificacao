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

    public function update(CreditCard $request)
    {
        try {
            \Log::debug($request);
            exit();
            return $this->service->save($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
