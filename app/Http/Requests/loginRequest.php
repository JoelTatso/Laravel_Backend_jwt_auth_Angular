<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class loginRequest extends FormRequest
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
            'email'=> 'required|email',
            'password' => 'required'
        ];
    }

    public function messages(): array
    {
        return[
            "email.email" => "Email incorrect !!!",
            "email.required" => "Email requis !!!",
            "password.required" => "Mot de passe requis !!!",
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
