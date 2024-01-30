<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password as PasswordRule;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nom' => ['required', 'alpha', 'max:30'],
            'prenom' => ['required', 'regex:/^[a-zA-Z][a-zA-Z -]{2,100}$/'],
            'adresse' => ['required', 'string', 'max:100'],
            'telephone' => ['nullable', 'regex:/^(77|78|76|70|75|33)[0-9]{7}$/', 'unique:users,telephone'],
            'image' => ['sometimes'],
            'email' => ['nullable', 'email', 'unique:users'],
            'password' => ['nullable', PasswordRule::default(), 'confirmed'],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 422,
            'errors' => $validator->errors()
        ]));
    }
}
