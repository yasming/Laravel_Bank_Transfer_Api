<?php

namespace App\Http\Requests;

use App\Rules\ValidateIfPayerHaveValidAmountRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormatResponseFormRequest;

class TransferRequest extends FormRequest
{
    use FormatResponseFormRequest;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'amount'   => [new ValidateIfPayerHaveValidAmountRule($this->payer_id)],
            'payee_id' => $this->getPayeeAndPayerValidation(),
            'payer_id' => $this->getPayeeAndPayerValidation()
        ];
    }

    private function getPayeeAndPayerValidation()
    {
        return ['required','numeric','exists:users,id'];
    }

}