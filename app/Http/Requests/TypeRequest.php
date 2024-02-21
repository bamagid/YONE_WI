<?php

namespace App\Http\Requests;

use App\Models\Type;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nom' => ['required', 'string','min:2', function ($attribute, $value, $fail) {
                $existingType = Type::where('nom', $value)
                    ->where('etat', '!=', 'supprimé')
                    ->exists();
                if ($existingType) {
                    $fail('Ce Type existe déja dans votre réseau');
                }
            },],
            'description' => ['nullable', 'string','min:10'],
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
