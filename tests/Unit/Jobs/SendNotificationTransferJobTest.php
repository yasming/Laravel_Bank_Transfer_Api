<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Jobs\Transfer\SendNotificationTransferJob;
use Illuminate\Support\Facades\Config;

class SendNotificationTransferJobTest extends TestCase
{
    use DatabaseMigrations;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Config::set('external_services.mock_notification','http://o4d9z.mocklab.io/notify');
    }

    public function test_it_should_send_notification()
    {
        file_put_contents(storage_path('logs/laravel.log'),'');
        $job                = new SendNotificationTransferJob(1,2,100);
        $job->handle();

        $logs       = file_get_contents(storage_path('logs/laravel.log'),'');
        $logCreated = strpos($logs,'testing.INFO: Notification sended, payee_id: 2 payer_id: 1 amount: 100') != false ? true : false;
        $this->assertTrue($logCreated);
    }

}
