<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use App\Services\CompanyService;
use App\Services\UserService;
use Illuminate\Support\Arr;
use App\Http\Requests\CompanyRegister;
use App\Library\Format;
use App\Library\ViaCep;
use App\Notifications\ActiveCompany;
use App\Services\CampaignService;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    protected $userService;
    protected $companyService;
    protected $campaignService;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UserService $userService,
        CompanyService $companyService,
        CampaignService $campaignService

    ) {
        $this->userService = $userService;
        $this->companyService = $companyService;
        $this->campaignService = $campaignService;
        $this->middleware('guest');
    }

    private function makeUser($data, $company)
    {
        $newData = [];
        $newData['name'] = Arr::get($data, 'name');
        $newData['birthday'] = Arr::get($data, 'birthday');
        $newData['email'] = Arr::get($data, 'email');
        $newData['company_id'] = $company['id'];
        $newData['password'] = Hash::make(Arr::get($data, 'password'));
        $newData['admin'] = 1;
        $newData['token_active'] = mt_rand(100000, 999999);
        return $newData;
    }

    private function makeCampaign($company)
    {
        $newData = [];
        $newData['name'] = "Aniversariantes";
        $newData['active'] = 0;
        $newData['duration_frames'] = 20;
        $newData['company_id'] = $company['id'];
        $newData['is_continuous'] = 1;
        $newData['is_birthday'] = 1;
        return $newData;
    }

    private function makeCompany($data)
    {
        $newData = [];
        $cep = Format::extractNumbers(Arr::get($data, 'cep'));
        $newData['postalCode'] = $cep;
        $enderecoRequest = ViaCep::cepConsult($cep);
        
        if(!empty($enderecoRequest)){
            $newData['city'] = $enderecoRequest['localidade'] ?? "";
            $newData['district'] = $enderecoRequest['bairro'] ?? "";
            $newData['state'] = $enderecoRequest['uf'] ?? "";
            $newData['street'] = $enderecoRequest['logradouro'] ?? "" ;
        }

        $newData['phone'] = Format::extractNumbers(Arr::get($data, 'phone'));
        $newData['country'] = 'BRA';
        $newData['cpf'] = Format::extractNumbers(Arr::get($data, 'cpf'));
        $newData['active'] = 0;
        $newData['token_screen'] = md5(uniqid(""));
        return $newData;
    }

    protected function register(CompanyRegister $data)
    {
        if ($data->validated()) {
            $company = $this->makeCompany($data);
            $companyData = $this->companyService->save($company, true);

            $user = $this->makeUser($data, $companyData);
            $user = $this->userService->save($user, true);

            $campaign = $this->makeCampaign($companyData);
            $this->campaignService->save($campaign, true);

            //TODO - REMOVER 
            //$user->notify(new ActiveCompany($user->name, $user->token_active));
            return (new LoginController)->login($data);
        }

        return redirect()->back()->withInput($data->all())->with('message', 'Ocorreu algum erro');
    }


    // Register
    public function showRegistrationForm()
    {
        $pageConfigs = ['bodyCustomClass' => 'register-bg', 'isCustomizer' => false];

        return view('/auth/register', [
            'pageConfigs' => $pageConfigs
        ]);
    }
}
