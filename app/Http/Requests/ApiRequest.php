<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator, $show = true)
    {
        $response = [
            'message' => "Erreur de validation"
        ];

        if ($show) $response['errors'] = collect($validator->errors())->map(function ($item) { return $item[0]; });

        throw new HttpResponseException(response()->json($response)->setStatusCode(422, "Erreur de validation"));
    }
}
