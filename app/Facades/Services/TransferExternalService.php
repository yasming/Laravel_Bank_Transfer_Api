<?php

namespace App\Facades\Services;

use App\Services\External\TransferExternalService as Service;
use Illuminate\Support\Facades\Facade;

class TransferExternalService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Service::class;
    }
}
