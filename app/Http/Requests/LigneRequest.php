<?php

namespace App\Http\Requests;

use App\Models\Ligne;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class LigneRequest extends FormRequest
{

    public function rules()
    {
        return [
            'nom' => ['nullable', 'regex:/^[1-9][A-Za-z0-9]/', function ($attribute, $value, $fail) {
                $existingLine = Ligne::where('nom', $value)
                    ->where('etat', '!=', 'supprimé')
                    ->exists();
                if ($existingLine) {
                    $fail('Cette Ligne existe déja dans votre réseau');
                }
            },],
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
