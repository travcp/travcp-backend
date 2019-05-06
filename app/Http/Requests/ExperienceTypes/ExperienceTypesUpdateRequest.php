<?php

namespace App\Http\Requests\ExperienceTypes;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceTypesUpdateRequest extends FormRequest
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
            "name" => 'string|unique:experiences_types'
        ];
    }
}
