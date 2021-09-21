<?php

namespace App\Http\Controllers;

use App\Services\AlbumService;
use App\Services\CampaignService;
use App\Services\CompanyService;
use App\Services\CreditCardService;
use App\Services\UserService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PagSeguroRecorrente;

class HomeController extends Controller
{
    protected $userService;
    protected $albumService;
    protected $campaignService;
    protected $creditCardService;
    protected $carbon;

    public function __construct(
        UserService $userService,
        CompanyService $companyService,
        AlbumService $albumService,
        CampaignService $campaignService,
        CreditCardService $creditCardService,
        Carbon $carbon
    ) {
        $this->userService = $userService;
        $this->companyService = $companyService;
        $this->albumService = $albumService;
        $this->campaignService = $campaignService;
        $this->creditCardService = $creditCardService;
        $this->carbon = $carbon;
    }

    public function index(Request $request)
    {
        try {
            $request["search_album_active"] = 1;
            $request["search_campaign_active"] = 1;

            $metrics = [
                'campaigns' => $this->campaignService->list($request)->count(),
                'users' => $this->userService->list($request)->count(),
                'albums' => $this->albumService->list($request)->count(),
            ];

            $data = $this->companyService->find(Auth::user()->company_id);
            $card = $this->creditCardService->search($request)->first();

            $breadcrumbs = [];

            //Pageheader set true for breadcrumbs
            $pageConfigs = ['pageHeader' => true];

            return view('pages.home', [
                "data" => $data,
                "card" => $card,
                "metrics" => $metrics,
                'pageConfigs' => $pageConfigs,
                'breadcrumbs' => $breadcrumbs,
                "urlAws" => ENV('AWS_URL'),
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function indexUser()
    {
        if(Auth::user()->admin){
            return redirect()->route('home');
        }

        try {
            $breadcrumbs = [];

            //Pageheader set true for breadcrumbs
            $pageConfigs = ['pageHeader' => true];

            return view('pages.home_user', [
                'pageConfigs' => $pageConfigs,
                'breadcrumbs' => $breadcrumbs,
                "urlAws" => ENV('AWS_URL'),
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function myAccount()
    {
        try {
         
            $user = Auth::user();
            $breadcrumbs = [];

            $pageConfigs = ['pageHeader' => true];

            return view('pages.perfil', [
                "data" => $user,
                'pageConfigs' => $pageConfigs,
                'breadcrumbs' => $breadcrumbs,
                "urlAws" => ENV('AWS_URL'),
            ]);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function active()
    {
        try {
            return view('auth.active');
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function activePost(Request $request)
    {
        try {
            $name = Auth::user()->name;
            $this->userService->active($request);
            return redirect()->route('home')->with("message", "Bem-vinda(o), $name  🖖");
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

    public function porcentagem($parcial, $total)
    {
        if ($total == 0) {
            return number_format((($parcial - $total)) * 100, 0);
        }

        return number_format((($parcial - $total) / $total) * 100, 0);
    }
}
