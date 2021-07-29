<?php

namespace Tests\Unit\Services\External;

use App\Facades\Services\SendNotificationExternalService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
class SendNotificationExternalServiceTest extends TestCase
{
    use DatabaseMigrations;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Config::set('external_services.mock_notification','http://o4d9z.mocklab.io/notify');
    }

    public function test_it_should_be_able_to_transfer()
    {
        $externalService = SendNotificationExternalService::sendNotification()->notificationSended();
        $this->assertTrue($externalService);
    }
}
