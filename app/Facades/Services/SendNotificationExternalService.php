<?php

namespace App\Facades\Services;

use App\Services\External\SendNotificationExternalService as Service;
use Illuminate\Support\Facades\Facade;

class SendNotificationExternalService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Service::class;
    }
}
