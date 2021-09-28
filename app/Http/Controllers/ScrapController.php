<?php

namespace App\Http\Controllers;

use App\Services\CompanyService;
use App\Services\ScrapService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScrapController extends Controller
{
    protected $service;
    protected $companyService;
    protected $carbon;

    public function __construct(
        ScrapService $service,
        CompanyService $companyService,
        Carbon $carbon
    ) {
        $this->service = $service;
        $this->companyService = $companyService;
        $this->carbon = $carbon;
    }

    public function index(Request $request)
    {
        $breadcrumbs = [];

        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];

        $company = $this->companyService->find(Auth::user()->company_id);
        $data = $this->service->search();

        return view(
            'pages.scrap',
            [
                'company' => $company,
                'datas' => $data,
                'pageConfigs' => $pageConfigs,
                'breadcrumbs' => $breadcrumbs,
                "urlAws" => ENV('AWS_URL'),
            ]
        );
    }

    public function create(Request $request)
    {
        try {
            return $this->service->save($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function list(Request $request)
    {
        try {
            return $this->service->list($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function indexAnon($token)
    {
        $search = [];

        array_push($search, ['token_screen', 'like', '%' . $token . '%']);
        $company = $this->companyService->searchField($search);

        if (!$company['anonymus']) {
            return redirect()->route('messages');
        }

        if(Auth::user()){
            return redirect()->route('messages');
        }

        $breadcrumbs = [];

        //Pageheader set true for breadcrumbs
        $pageConfigs = ['pageHeader' => true];

        return view('pages.scrap', [
            'company' => $company,
            'pageConfigs' => $pageConfigs,
            'breadcrumbs' => $breadcrumbs,
            "urlAws" => ENV('AWS_URL'),
        ]);
    }

    public function delete(Request $request)
    {
        try {
            return $this->service->delete($request);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}
