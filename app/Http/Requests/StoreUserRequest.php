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
            'nom' => ['required', 'max:30', "alpha",'min:2'],
            'prenom' => ['required', 'regex:/^[a-zA-Z][a-zA-Z -]{2,100}$/'],
            "adresse" => ['required', 'max:100', "string",'min:3'],
            'telephone' => ['required', 'regex:/^(77|78|76|70|75|33)[0-9]{7}$/', 'unique:users,telephone'],
            "image" => ["sometimes","image"],
            "role_id" =>[ "required","integer","exists:roles,id"],
            "reseau_id" => ["required","integer","exists:reseaus,id"],
            "email" => ["required","regex:/^[A-Za-z]+[A-Za-z0-9\._%+-]+@+[A-Za-z][A-Za-z0-9\.-]+\.[A-Za-z]{2,}$/","unique:users,email","unique:admin_systems,email"],
            'password' => ['sometimes',PasswordRule::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
        ];
    }

    public function failedValidation(validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
             $validator->errors()
        ],422));
    }
}
