<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserToken;
use App\Services\AlbumService;
use App\Services\CampaignService;
use App\Services\CompanyService;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $carbon;

    public function __construct(
        Carbon $carbon
    ) {
        $this->carbon = $carbon;
    }


    public function index(Request $request)
    {
        \Log::debug("chegouuuu");
        \Log::debug($request);
    }
}
