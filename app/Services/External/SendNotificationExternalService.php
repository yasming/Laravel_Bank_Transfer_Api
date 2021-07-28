<?php


namespace App\Services\External;

use Illuminate\Support\Facades\Http;

class SendNotificationExternalService
{
    const MESSAGE_SENT = 'Success';
    private $url;
    private $response;

    public function __construct()
    {
       $this->url = config('external_services.mock_notification');
    }

    public function sendNotification() : self
    {
        $this->response = Http::get($this->url)->json();
        return $this;
    }

    public function notificationSended() : bool
    {
        if (!isset($this->response['message'])) {
            return false;
        }
        
        if ($this->response['message'] == self::MESSAGE_SENT) {
            return true;
        }

        return false;
    }
}
