<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password as PasswordRule;

class StoreUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'max:30', "alpha"],
            'prenom' => ['required', 'regex:/^[a-zA-Z][a-zA-Z -]{2,100}$/'],
            "adresse" => ['required', 'max:100', "string"],
            'telephone' => ['required', 'regex:/^(77|78|76|70|75|33)[0-9]{7}$/', 'unique:users,telephone'],
            "image" => "sometimes",
            "role_id" => "required|integer",
            "reseau_id" => "required|integer",
            "email" => "required|email|unique:users,email|admin_systems,email",
            'password' => [PasswordRule::default(), 'confirmed'],
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
