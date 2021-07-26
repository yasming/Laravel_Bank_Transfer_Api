<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateIfPayerHaveValidAmountRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $payerId;

    public function __construct($payerId)
    {
        $this->payerId = $payerId;
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
        
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
