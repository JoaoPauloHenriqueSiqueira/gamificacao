<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $carbon;

    public function __construct(
        Carbon $carbon,
        PaymentService $service
    ) {
        $this->carbon = $carbon;
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return $this->service->transaction($request);
    }
}
