<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends ApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login' => 'required|regex:/^[a-z0-9]+$/u|min:5|max:10'
        ];
    }

    protected function failedValidation(Validator $validator, $show = false)
    {
        parent::failedValidation($validator, $show);
    }
}
