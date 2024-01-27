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
            'nom' => ['required', 'string'],
            'etat' => ['required', 'in:actif,corbeille,supprimé'],
            'type_id' => ['required', 'exists:types,id'],
            'lieuDepart' => ['required', 'string'],
            'lieuArrivee' => ['required', 'string'],
        ];
    }


    public function failedValidation(validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 422,
            'errors' => $validator->errors()
        ]));
    }
}
