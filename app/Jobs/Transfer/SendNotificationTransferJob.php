<?php

namespace App\Jobs\Transfer;

use App\Facades\Services\SendNotificationExternalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\Transfer;
class SendNotificationTransferJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $status;
    private $transactionId;

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
        try {
            $notificationSended = SendNotificationExternalService::sendNotification()->notificationSended();
            $notificationSended = false;
            if (!$notificationSended) {
                throw new \Exception(__('External service is not available.'));
            }

        }  catch (\Throwable $e) {
            $this->logJobFailed($e);
            throw new \Exception($e);
        }
      
    }

    private function logJobFailed($e = null) : void
    {
        if ($this->status == Transfer::FAILURE) {
            Log::warning(__('Notification not send for transaction failure'));
        }

        if ($this->status == Transfer::SUCCESS) {
            Log::warning(__('Notification not send for transaction successfully'));
        }

        if ($e) {
            Log::info('reason: '. json_encode($e));
        }
    }
}
