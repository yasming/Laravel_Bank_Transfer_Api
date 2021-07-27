<?php

namespace App\Jobs\Transfer;

use App\Facades\Services\TransferExternalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\User;
class TransferMoneyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payerId;
    private $payeeId;
    private $amount;

    public function __construct($request)
    {
        $this->payerId = $request['payer_id'];
        $this->payeeId = $request['payee_id'];
        $this->amount  = $request['amount'];
    }

    /**
     * Execute the job.
     *
     * @return void
    */

    public function handle()
    {
        try {
            $transferIsAuthorized = TransferExternalService::validateTransfer()->transferIsAuthorized();

            if ($transferIsAuthorized) {
                $this->executeTransfer();
            }

            if (!$transferIsAuthorized) {
                $this->logsTransactionNotAuthorized();  
            }

        } catch (\Throwable $e) {
            
        }
    }

    private function executeTransfer()
    {
        $this->removeBalanceFromPayer();
        $this->addBalanceToPayee();
        // $this->createTransfer();
    }

    private function removeBalanceFromPayer() : void
    {
        User::find($this->payerId)->removeBalance($this->amount);
    }

    private function addBalanceToPayee() : void
    {
        User::find($this->payeeId)->addBalance($this->amount);
    }

    private function logsTransactionNotAuthorized()
    {
        Log::warning(__("Transfer not authorized"));
        Log::info('payer_id: '.$this->payerId); 
        Log::info('payee_id: '.$this->payeeId); 
        Log::info('amount:   '.$this->amount); 
    }
}
