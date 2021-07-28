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

class SendNotificationTransferJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * Execute the job.
     *
     * @return void
    */

    public function handle()
    {
        $notificationSended = SendNotificationTransferJob::sendNotification()->notificationSended();
        if (!$notificationSended) {

        }
    }

    private function logsNotificationNotSended() : void
    {
        Log::warning(__('Transfer not authorized'));
        Log::info('payer_id: '.$this->payerId); 
        Log::info('payee_id: '.$this->payeeId); 
        Log::info('amount:   '.$this->amount); 
    }
}
