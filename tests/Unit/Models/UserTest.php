<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;
use App\Models\Balance;
class UserTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

   public function test_it_should_return_user_balance_quantity()
   {
       $this->assertEquals(User::first()->balance()->count(), Balance::whereUserId(User::first()->id)->count());
   }

   public function test_it_should_return_user_balance_amount()
   {
       $this->assertEquals(User::first()->balance()->first()->amount, User::first()->getAmount());
   }
}