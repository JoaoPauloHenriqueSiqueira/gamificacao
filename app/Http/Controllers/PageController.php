<?php

namespace App\Http\Controllers;

use App\Services\AlbumService;
use App\Services\CampaignService;
use App\Services\CompanyService;
use App\Transformers\CompanyTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $campaignService;
    protected $companyService;
    protected $albumService;

    protected $carbon;

    public function __construct(
        CampaignService $campaignService,
        AlbumService $albumService,
        CompanyService $companyService,
        Carbon $carbon
    ) {
        $this->campaignService = $campaignService;
        $this->companyService = $companyService;
        $this->albumService = $albumService;
        $this->carbon = $carbon;
    }

    public function index()
    {
        
        return view('pages.apresentation');
    }

}
