<?php

namespace Tests\Unit\Commands;

use App\Console\Commands\RunJobFailedsCommand;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;

class RunJobsFailedsCommandTest extends TestCase
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
        Queue::fake();

        \DB::table('failed_jobs')->insert([
            'uuid'       => '1648de5a-3bb5-4544-ae29-3dd643e0aab6',
            'connection' => 'redis',
            'queue'      => 'default',
            'payload'    => 'error',
            'exception'  => 'exception',
            'failed_at'  => now()
        ]);
        $this->assertEquals(1,\DB::table('failed_jobs')->count());
        
        $runJobFailedsCommands = new RunJobFailedsCommand;
        $runJobFailedsCommands->handle();

        $this->assertEquals(0,\DB::table('failed_jobs')->count());
    }

}
