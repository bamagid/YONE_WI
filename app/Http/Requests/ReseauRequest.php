<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReseauRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nom' => ['required', 'string','unique:reseaus,nom'],
            "telephone" => ['nullable', 'regex:/^(77|78|76|70|75|33)[0-9]{7}$/', 'unique:users,telephone'],
            'description' => ['nullable', 'string'],
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
