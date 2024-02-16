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
            'nom' => ['nullable', 'alpha', 'max:30','min:3'],
            'prenom' => ['nullable', 'regex:/^[a-zA-Z][a-zA-Z -]{2,100}$/'],
            'adresse' => ['nullable', 'string', 'max:100'],
            'telephone' => ['nullable', 'regex:/^(77|78|76|70|75|33)[0-9]{7}$/', 'unique:users,telephone'],
            'image' => ['sometimes'],
            'email' => ['nullable','regex:/^[A-Za-z]+[A-Za-z0-9\._%+-]+@+[A-Za-z][A-Za-z0-9\.-]+\.[A-Za-z]{2,}$/','unique:users,email', 'unique:admin_systems,email'],
            'password' => [
                'sometimes', PasswordRule::min(8)->mixedCase()->numbers()->symbols()->uncompromised(), 'confirmed'
            ],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ], 422));
    }
}
