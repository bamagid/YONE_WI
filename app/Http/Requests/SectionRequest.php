<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SectionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'depart' => ['required', 'string'],
            'arrivee' => ['required', 'string', 'different:depart'],
            'ligne_id' => ['required', 'exists:lignes,id'],
            'tarif_id' => ['required', 'exists:tarifs,id'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ],422));
    }
}
