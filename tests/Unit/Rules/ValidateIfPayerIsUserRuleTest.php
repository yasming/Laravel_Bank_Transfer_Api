<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use App\Rules\ValidateIfPayerIsUserRule;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;

class ValidateIfPayerIsUserRuleTest extends TestCase
{
    use DatabaseMigrations;
    private $user;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::first();

    }

    public function testPassesTrue()
    {
        $rule   = new ValidateIfPayerIsUserRule;
        $this->user->update(['shopkeeper' => 0]);
        $return = $rule->passes('payer_id', $this->user->id);
        $this->assertTrue($return);
    }

    public function testPassesFalse()
    {
        $rule   = new ValidateIfPayerIsUserRule;
        $this->user->update(['shopkeeper' => 1]);

        $return = $rule->passes('payer_id', $this->user->id);

        $this->assertFalse($return);
    }

    public function testMessage()
    {
        $rule    = new ValidateIfPayerIsUserRule;
        $return  = $rule->message();

        $this->assertEquals('Shopkeeper cannot transfer money', $return);
    }
  
}
