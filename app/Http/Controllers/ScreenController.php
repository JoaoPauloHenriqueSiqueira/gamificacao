<?php

namespace App\Http\Controllers;

use App\Services\AlbumService;
use App\Services\CampaignService;
use App\Services\CompanyService;
use App\Transformers\CompanyTransformer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScreenController extends Controller
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
        $var1 = true;
        $var2 = false;
        $var3 = true;

        return response()->json(['success' => false]);
    }

    public function page(Request $request, $name)
    {
        $search = [];

        $name =  str_replace(' ', '_', trim($name));
        array_push($search, ['name', 'like', '%' . $name . '']);
        $company = $this->companyService->searchField($search);

        //TODO CRIAR COMPANY PADRÃO (TELA CAIR NA TELA DO EXIBE TV)
        if (!$company) {
            $company = $this->companyService->searchField(['id' => 1]);
        }

        //TODO BUSCAR PELO NOME
        $campaigns = $this->campaignService->searchScreen($company['id']);
        $albums = $this->albumService->searchScreen($company['id']);
        $data = $campaigns->concat($albums);

        $company = (new CompanyTransformer)->transform($company);
        //1 pegar campanhas/albums/albums_videos ok/ n ok / n ok
        //2 formatar e normalizar slides ok
        //3 validar no front video e slides n ok/ ok
        //4 dinamicamente exibir slides e videos ok / n ok
        //scraps (recados)

        $url = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . url("/scraps/{$company['token_screen']}") . '%2F&choe=UTF-8';

        return view('pages.screen', [
            'qrCode' => $url,
            'campaigns' => $data,
            'company' => $company,
            "urlAws" => ENV('AWS_URL'),
        ]);
    }
}
