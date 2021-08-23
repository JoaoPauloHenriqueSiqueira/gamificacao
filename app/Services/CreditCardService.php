<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use App\Repositories\CreditsCardsRepository;
use Illuminate\Support\Facades\DB;
use PagSeguroRecorrente;
use App\Library\PagSeguro;

class CreditCardService
{
    protected $repository;
    protected $userService;
    protected $carbon;
    protected $photoRepository;
    protected $companyService;

    public function __construct(
        CreditsCardsRepository $repository,
        Carbon $carbon,
        CompanyService $companyService
    ) {
        $this->repository = $repository;
        $this->carbon = $carbon;
        $this->companyService = $companyService;
    }

    public function search($request)
    {
        $filterColumns = $this->makeParamsSearch($request);

        $list = $this->repository->scopeQuery(function ($query) use ($filterColumns) {
            return $query->where($filterColumns)->orderBy('created_at', 'DESC');
        });

        return $list->paginate(10);
    }

    private function makeParamsSearch($request)
    {
        $filterColumns = ['company_id' => Auth::user()->company_id];
        return  $filterColumns;
    }

    public function find($taskId)
    {
        return $this->repository->find($taskId)->toArray();
    }

    public function consultPlanRequest()
    {
        $card = $this->repository->findByField("company_id", Auth::user()->company_id)->first();
        return PagSeguroRecorrente::showPreApproval($card->plan_token);
    }

    public function consultPlanPayments()
    {
        $card = $this->repository->findByField("company_id", Auth::user()->company_id)->first();
        return PagSeguroRecorrente::paymentOrders($card->plan_token);
    }

    public function updatePlanStatus($companyId)
    {
        $master = Auth::user()->master;

        if ($master) {
            $card = $this->repository->findByField('company_id', $companyId)->first();

            if ($card) {
                $status = (array) PagSeguroRecorrente::showPreApproval($card->plan_token);
                if ($status) {
                    if ($status['status'] != $card->plan_status) {
                        \Log::debug(__METHOD__ . ' Company : ' . $companyId . ' REQUEST: ' . json_encode(Auth::user(), true) . ' STATUS ANTIGO : ' . $card->plan_status . ' STATUS ATUAL : ' . $status['status']);
                        $card['plan_status'] = $status['status'];
                        $card->save();

                        return response('Plan status atualizado', 200);
                    }

                    if ($status['status'] == $card->plan_status) {
                        return response('Plan status já está atualizado', 200);
                    }
                }
            }

            return response('Não foi possível alterar o plan status dessa empresa', 422);
        }
       return abort(404);
    }

    public function update($request)
    {
        if ($request->validated()) {

            DB::beginTransaction();

            $card = $this->repository->findByField("company_id", Auth::user()->company_id)->first();

            if (!$card) {
                return response('Você não possui um método de pagamaneto salvo. Caso necessite, entre em contato com nosso suporte', 422);
            }

            $response = $this->repository->updateOrCreate(["id" => $card->id], $request->all());

            $company = $this->companyService->find(Auth::user()->company_id);
            $senderInfo = [
                'senderName' => Auth::user()->name,
                'senderPhone' => $company['phone'], //Qualquer formato, desde que tenha o DDD
                'senderEmail' =>  Auth::user()->email,
                'senderHash' => $request->hash
            ];

            if (strlen($company['cpf']) <= 11) {
                $senderInfo['senderCPF'] = $company['cpf'];
            }

            $setCreditCardHolder = [
                'creditCardHolderBirthDate' => Auth::user()->getBirthdayCardAttribute() // 10/02/1940 Deve estar nesse formato,
            ];

            $setSenderAddress = [
                'senderAddressStreet' => $company['street'],
                'senderAddressNumber' => $company['number'],
                'senderAddressDistrict' => $company['district'],
                'senderAddressPostalCode' => $company['postalCode'],
                'senderAddressCity' =>  $company['city'],
                'senderAddressState' => $company['state']
            ];

            $reference = $card->reference;
            try {
                $tokenPlan = PagSeguroRecorrente::setPreApprovalCode($card->plan_token)
                    ->setType('CREDITCARD')
                    ->setReference($reference)
                    ->setSenderInfo($senderInfo)
                    ->setCreditCardHolder($setCreditCardHolder)
                    ->setSenderAddress($setSenderAddress)
                    ->sendPreApprovalPaymentMethod([
                        'creditCardToken' => $response->token
                    ]);


                if ($tokenPlan) {
                    $response = $this->repository->updateOrCreate(["id" => $response->id], ['plan_token' => $tokenPlan, 'reference' => $reference]);
                    if ($response) {
                        DB::commit();
                        return response('Adesão ao plano realizada com sucesso, aguarde confirmação de pagamento', 200);
                    }
                }
            } catch (\Artistas\PagSeguro\PagSeguroException $e) {
                DB::rollback();
                return response($e->getMessage(), 422);
            }
        }
    }

    public function save($request)
    {
        if ($request->validated()) {
            DB::beginTransaction();

            $card = $this->repository->findByField('company_id', Auth::user()->company_id)->first();
            if ($card) {
                return response('Você já possui um plano vinculado a essa empresa. Você pode alterar a forma de pagamento ou cancelar a assinatura. Caso necessite, entre em contato com nosso suporte', 422);
            }

            if (!$card) {
                $response = $this->repository->create($request->all());
            }

            $hash = $request->hash;
            if (!$hash) {
                $hashRequest = (array) PagSeguro::getSession();
                $hash = Arr::get($hashRequest, "id", false);
            }
            if (!$hash) {
                return response('Ocorreu um erro. Verifique se todos os seus dados estão corretos e tente novamente', 422);
            }

            $company = $this->companyService->find(Auth::user()->company_id);
            $senderInfo = [
                'senderName' => Auth::user()->name,
                'senderPhone' => $company['phone'],
                'senderEmail' => Auth::user()->email,
                'senderHash' => $hash,
                'senderCPF' => $company['cpf']
            ];

            $setCreditCardHolder = [
                'creditCardHolderBirthDate' => Auth::user()->getBirthdayCardAttribute(), // 10/02/1940 Deve estar nesse formato,
            ];

            $setSenderAddress = [
                'senderAddressStreet' => $company['street'],
                'senderAddressNumber' => $company['number'],
                'senderAddressDistrict' => $company['district'],
                'senderAddressPostalCode' => $company['postalCode'],
                'senderAddressCity' => $company['city'],
                'senderAddressState' => $company['state'],
            ];

            $reference = md5(uniqid(rand(), true));

            try {
                $tokenPlan = PagSeguroRecorrente::setPlan(env('PLANO_PAGSEGURO'))
                    ->setReference($reference)
                    ->setSenderInfo($senderInfo)
                    ->setCreditCardHolder($setCreditCardHolder)
                    ->setSenderAddress($setSenderAddress)
                    ->sendPreApproval([
                        'creditCardToken' => $response->token,
                    ]);

                if ($tokenPlan) {
                    $response = $this->repository->updateOrCreate(['id' => $response->id], ['plan_token' => $tokenPlan, 'reference' => $reference]);
                    if ($response) {
                        DB::commit();

                        return response('Adesão ao plano realizada com sucesso, aguarde confirmação de pagamento', 200);
                    }
                }
            } catch (\Artistas\PagSeguro\PagSeguroException $e) {

                \Log::debug($e->getMessage());
                DB::rollback();
                return response('Ocorreu um erro. Verifique se todos os seus dados estão corretos e tente novamente', 422);
            }
        }
    }

    public function delete()
    {
        $card = $this->repository->findByField("company_id", Auth::user()->company_id)->first();

        try {
            if ($card) {
                if ($card->plan_token) {
                    $cancel = (array) PagSeguroRecorrente::cancelPreApproval($card->plan_token);
                    if ($cancel['status'] == "ok") {
                        $card->plan_token = "";
                        $card->save();


                        return redirect()->back()->with('message', 'Assinatura cancelada com sucesso!');
                    }
                }
            }
        } catch (\Artistas\PagSeguro\PagSeguroException $e) {
            DB::rollback();

            $message = $e->getMessage();
            if ($message == "invalid pre-approval status to execute the requested operation. Pre-approval status is PENDING."); {
                $message  = "Seu pagamento ainda está sendo processado";
            }
            return redirect()->back()->with('message', $message);
        }

        return redirect()->back()->with('message', 'Ocorreu algum erro');
    }

    private function checkCompany($card)
    {
        if ($card) {
            $companyId = Auth::user()->company_id;
            $companySale = $this->repository->find($card);
            if ($companyId != Arr::get($companySale, "company_id")) {
                return false;
            }
        }

        return true;
    }
}
