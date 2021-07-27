<?php


namespace App\Services\External;

use Illuminate\Support\Facades\Http;

class TransferExternalService
{
    const AUTHORIZED_STATUS = 'Autorizado';
    private $url;
    private $response;

    public function __construct()
    {
       $this->url = config('external_services.mock_transfer');
    }

    public function validateTransfer() : self
    {
        $this->response = Http::get($this->url)->json();
        return $this;
    }

    public function transferIsAuthorized() : bool
    {
        if (!isset($this->response['message'])) {
            return false;
        }
        
        if ($this->response['message'] == self::AUTHORIZED_STATUS) {
            return true;
        }

        return false;
    }
}
