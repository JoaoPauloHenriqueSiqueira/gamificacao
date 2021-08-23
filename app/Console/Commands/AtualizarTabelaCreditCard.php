<?php

namespace App\Console\Commands;

use App\CreditsCards;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PagSeguroRecorrente;
use App\Library\PagSeguro;

class AtualizarTabelaCreditCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atualizar:credit-cards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para atualizar os dados da tabela credit card';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $cards = CreditsCards::whereDate('validated_at', '<', Carbon::today())->get();

        foreach ($cards as $card) {
            try {
                $status = (array) PagSeguroRecorrente::showPreApproval($card->plan_token);
                if ($status) {
                    if ($status['status'] != $card->plan_status) {
                        Log::debug(__METHOD__ . ' Company : ' . $card->company_id . ' REQUEST: KERNEL - STATUS ANTIGO : ' . $card->plan_status . ' STATUS ATUAL : ' . $status['status']);
                        $card['plan_status'] = $status['status'];
                        $card['validated_at'] =  Carbon::today();
                        $card->save();
                    }
                }
            } catch (\Artistas\PagSeguro\PagSeguroException $e) {
                return response($e->getMessage(), 422);
            }
        }
    }
}
