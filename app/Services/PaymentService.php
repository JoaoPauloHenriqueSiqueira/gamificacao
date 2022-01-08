<?php

namespace App\Services;

use Carbon\Carbon;
use App\Repositories\CreditsCardsRepository;
use PagSeguroRecorrente;
use App\Events\PaymentChanges;


class PaymentService
{
    protected $repository;
    protected $carbon;

    public function __construct(
        CreditsCardsRepository $repository,
        Carbon $carbon
    ) {
        $this->repository = $repository;
        $this->carbon = $carbon;
    }

    public function transaction($request)
    {
        $notification = (array) PagSeguroRecorrente::notification($request->notificationCode, $request->notificationType);

        $status = $notification['status'];
        $card = $this->repository->findByField("reference", $notification['reference'])->first();

        \Log::info($request);
        \Log::info($notification);

        if ($card) {
            $company = $card['company_id'];

            //alterações de status da adesão
            if ($request->notificationType == "preApproval") {
                $status = $notification['status'];
                $card['plan_status'] = $status;
                $card->save();
            }

            //alteraçao de status de pagamento
            if ($request->notificationType == "transaction") {
                $card['status'] = $status;
                $card->save();
            }

            if (
                $status == "CANCELLED_BY_RECEIVER" ||
                $status == "CANCELLED_BY_SENDER" ||
                $status == "SUSPENDED" ||
                $status == "CANCELLED"
            ) {
                $this->repository->delete($card->id);
            }

            broadcast(new PaymentChanges($company, $status))->toOthers();
        }
    }
}
