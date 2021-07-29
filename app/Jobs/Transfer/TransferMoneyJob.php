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
use App\Repository\Transfer\TransferRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Transfer;
class TransferMoneyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payerId;
    private $payeeId;
    private $amount;
    private $transferRepository;
    private $transcationId;

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

    public function handle(TransferRepository $transferRepository)
    {
        try {
            DB::beginTransaction();
            
            $transferIsAuthorized = TransferExternalService::validateTransfer()->transferIsAuthorized();
            $this->executeTransferIfIsAuthorized($transferIsAuthorized, $transferRepository);

            DB::commit();
            SendNotificationTransferJob::dispatch(Transfer::SUCCESS);
        } catch (\Throwable $e) {
            DB::rollback();
            $this->logJobFailed($e);
            SendNotificationTransferJob::dispatch(Transfer::FAILURE);
            throw new \Exception($e);
        }
    }

    private function executeTransferIfIsAuthorized($transferIsAuthorized, $transferRepository) : void
    {
        if ($transferIsAuthorized) {
            $this->transferRepository = $transferRepository;
            $this->executeTransfer();
        }

        if (!$transferIsAuthorized) {
            $this->logsTransactionNotAuthorized();  
        }
    }

    private function executeTransfer() : void
    {
        $this->removeBalanceFromPayer();
        $this->addBalanceToPayee();
        $this->createTransfer();
    }

    private function removeBalanceFromPayer() : void
    {
        User::find($this->payerId)->removeBalance($this->amount);
    }

    private function addBalanceToPayee() : void
    {
        User::find($this->payeeId)->addBalance($this->amount);
    }

    private function createTransfer() : void
    {
        $transcation = $this->transferRepository->create([
            'payer_id' => $this->payerId,
            'payee_id' => $this->payeeId,
            'amount'   => $this->amount
        ]);
        $this->transactionId = $transcation->id;
    }

    private function logsTransactionNotAuthorized() : void
    {
        Log::warning(__('Transfer not authorized'));
        Log::info('payer_id: '.$this->payerId); 
        Log::info('payee_id: '.$this->payeeId); 
        Log::info('amount:   '.$this->amount); 
    }

    private function logJobFailed($e) : void
    {
        Log::info(__('-- Transcation failed --'));
        Log::info('reason: '. json_encode($e));
    }
}
