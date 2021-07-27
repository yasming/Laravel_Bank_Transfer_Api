<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;
class ValidateIfPayerHaveValidAmountRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $payerAmount;

    public function __construct($payerId)
    {
        $this->payerAmount = User::find($payerId)?->getAmount();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value <= $this->payerAmount;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Your balance is insufficient for this transaction');
    }
}
