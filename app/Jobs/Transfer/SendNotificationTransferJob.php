<?php

namespace App\Jobs\Transfer;

use App\Facades\Services\SendNotificationExternalService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
class SendNotificationTransferJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $payerId;
    private $payeeId;
    private $amount;

    public function __construct(int $payerId, int $payeeId, int $amount)
    {
        $this->payerId = $payerId;
        $this->payeeId = $payeeId;
        $this->amount  = $amount;
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
            if (!$notificationSended) {
                throw new \Exception(__('External service is not available.'));
            }
            $this->logJobSuccess();
        }  catch (\Throwable $e) {
            $this->logJobFailed($e);
            throw new \Exception($e);
        }
    }

    private function logJobFailed($e = null) : void
    {
        Log::warning(__('Notification not sended'));
        Log::info('reason: '. json_encode($e));
    }

    private function logJobSuccess() : void
    {
        Log::info(
            _('Notification sended, payee_id: '.$this->payeeId.' payer_id: '.$this->payerId.' amount: '.$this->amount)
        );
    }
}
