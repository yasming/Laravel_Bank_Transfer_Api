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

    public function test_it_should_return_is_shopkeeper()
    {
        User::first()->update(['shopkeeper' => 1]);
        $this->assertEquals(User::first()->shopkeeper, User::first()->isShopkeeper());
    }

    public function test_it_should_return_is_user()
    {
        User::first()->update(['shopkeeper' => 0]);
        $this->assertEquals(!User::first()->shopkeeper, User::first()->isUser());
    }

    public function test_it_should_add_balace()
    {
        User::first()->balance()->first()->update(['amount' => 100]);
        $this->assertEquals(200, User::first()->addBalance(100));
    }

    public function test_it_should_remove_balace()
    {
        User::first()->balance()->first()->update(['amount' => 30]);
        $this->assertEquals(20, User::first()->addBalance(10));
    }
}
