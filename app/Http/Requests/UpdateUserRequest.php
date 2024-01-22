<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password as PasswordRule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "nom" => ['nullable', 'max:30', "string"],
            "prenom" => ['nullable', 'max:60', "string"],
            "adresse" => ['nullable', 'max:30', "string"],
            "telephone" => ['nullable', 'max:30', "string"],
            "image" => "sometimes",
            "email" => "nullable|email|unique:users",
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
