<?php

namespace Tests\Unit\Services\External;

use App\Facades\Services\TransferExternalService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
class TransferExternalServiceTest extends TestCase
{
    use DatabaseMigrations;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Config::set('external_services.mock_transfer','https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
    }

    public function test_it_should_be_able_to_transfer()
    {
        $externalService = TransferExternalService::validateTransfer()->transferIsAuthorized();
        $this->assertTrue($externalService);
    }
}
