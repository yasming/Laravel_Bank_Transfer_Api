<?php

namespace App\Jobs\Transfer;

use App\Facades\Services\TransferExternalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TransferMoneyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $request;
    
    public function __construct($request)
    {
        $this->request = $request;
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
    }

    private function logsTransactionNotAuthorized()
    {
        Log::warning(__("Transfer not authorized"));
        Log::info('payer_id: '.$this->request['payer_id']); 
        Log::info('payee_id: '.$this->request['payee_id']); 
        Log::info('amount:   '.$this->request['amount']); 
    }
}
