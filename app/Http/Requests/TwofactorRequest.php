<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

use function PHPSTORM_META\map;

class TwofactorRequest extends FormRequest
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
            'two_factor_code' => 'integer|required',
            'email' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'two_factor_code.integer' => 'veillez entrer le code envoyé !!!',
            'two_factor_code.required' => 'champs requis !!!',
            'email.required' => 'email requis !!!'
        ];
    }

    public function failedValidation(Validator $validator){

        throw new HttpResponseException(response()->json([
            'statut' => false,
            'message' => 'Erreur de Validation',
            'ErrorsList' => $validator->errors()
        ]));

    }
}
