<?php

namespace App\Http\Requests;

use App\Rules\ValidateIfPayerHaveValidAmountRule;
use App\Rules\ValidateIfPayerIsUserRule;
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
            'amount'   => ['required','numeric',new ValidateIfPayerHaveValidAmountRule($this->payer_id)],
            'payer_id' => ['required', 'numeric', 'exists:users,id', new ValidateIfPayerIsUserRule],
            'payee_id' => 'required|numeric|exists:users,id|different:payer_id'
        ];
    }
}
