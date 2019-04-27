<?php

namespace App\Http\Requests\UserPayments;

use Illuminate\Foundation\Http\FormRequest;

class UserPaymentsUpdateRequest extends FormRequest
{
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
            'description' => 'string',
            'user_id' => 'integer',
            'experience_id' => 'integer',
            'transaction_id' => 'string',
            'amount' => 'integer',
            'currency' => 'string', // naira, dollar, pound
        ];
    }
}
