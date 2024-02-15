<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LigneRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nom' => ['required', 'regex:/^[0-9][0-9A-Za-z]$/'],
            'type_id' => ['nullable', 'exists:types,id'],
            'lieuDepart' => ['required', 'string', 'min:2'],
            'lieuArrivee' => ['required', 'string', 'different:lieuDepart', 'min:2'],
        ];
    }

    public function failedValidation(validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
