<?php

namespace Tests\Unit\Rules;

use Tests\TestCase;
use App\Rules\ValidateIfPayerHaveValidAmountRule;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\User;

class ValidateIfPayerHaveValidAmountRuleTest extends TestCase
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
        $rule   = new ValidateIfPayerHaveValidAmountRule($this->user->id);
        $amount = $this->user->getAmount() - 10;
        $return = $rule->passes('payer_id', $amount);
        $this->assertTrue($return);
    }

    public function testPassesFalse()
    {
        $rule   = new ValidateIfPayerHaveValidAmountRule($this->user->id);
        $amount = $this->user->getAmount() + 10;
        $return = $rule->passes('payer_id', $amount);

        $this->assertFalse($return);
    }

    public function testMessage()
    {
        $rule    = new ValidateIfPayerHaveValidAmountRule($this->user->id);
        $return  = $rule->message();

        $this->assertEquals('Your balance is insufficient for this transaction', $return);
    }
  
}
