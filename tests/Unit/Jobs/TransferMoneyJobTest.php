<?php

namespace Tests\Unit\Jobs;

use App\Jobs\Transfer\TransferMoneyJob;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Repository\Transfer\TransferRepository;
use Illuminate\Support\Facades\Config;
use App\Models\Transfer;
class TransferMoneyJobTest extends TestCase
{
    use DatabaseMigrations;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Config::set('external_services.mock_transfer','https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');
        Config::set('external_services.mock_notification','http://o4d9z.mocklab.io/notify');
    }

    public function test_it_should_create_a_new_transfer_in_database()
    {
        $mock               = $this->mockApiRequest();
        $oldAmountPayer     = User::find(1)->balance()->first()->amount;
        $oldAmountPayee     = User::find(2)->first()->balance()->first()->amount;
        $job                = new TransferMoneyJob($mock);
        $transfer           = (new Transfer);
        $transferRepository = new TransferRepository($transfer);
        $job->handle($transferRepository);
        $this->assertEquals($oldAmountPayer - 1, User::find(1)->balance()->first()->amount);
        $this->assertEquals($oldAmountPayee + 1, User::find(2)->balance()->first()->amount);

        $transferCreated = Transfer::first();
        $this->assertEquals(Transfer::count()  , 1);
        $this->assertEquals($transferCreated->payer_id  , 1);
        $this->assertEquals($transferCreated->payee_id  , 2);
        $this->assertEquals($transferCreated->amount  , 1);
    }

    private function mockApiRequest()
    {
        return [ 
                    "payer_id"  => 1,
                    "payee_id"  => 2,
                    "amount"    => 1,
               ];
    }
}
