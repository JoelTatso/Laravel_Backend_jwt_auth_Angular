<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password' => 'required|confirmed',
            'email' => 'required'
        ];
    }

    public function messages(): array
    {
        return[
            "password.required" => "Mot de passe requis !!!",
            "password.confirmed" => "Veillez vérifier votre mot de passe !!!"
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json([
                'statut' => false,
                'message' => 'erreur de validation',
                'errorsList' => $validator->errors()
        ]));
    }
}
