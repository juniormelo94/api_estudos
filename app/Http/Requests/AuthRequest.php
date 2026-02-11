<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class AuthRequest extends FormRequest
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
        $rules = [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:6']
        ];

        if ($this->is('api/registrar')) {
            $rules = [
                'name' => ['required', 'string', 'max:50'],
                'email' => ['required', 'string', 'email', 'unique:users'],
                'password' => ['required', 'string', 'min:6', 'same:password_confirmation'],
                'password_confirmation' => ['required']
            ];
        }

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome precisa ser no formato string.',
            'name.max' => 'O nome deve conter no máximo 50 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.string' => 'O e-mail precisa ser no formato string.',
            'email.email' => 'O e-mail precisa ser no formato email.',
            'email.unique' => 'Já existe um cadastro com esse e-mail.',
            'password.required' => 'A senha é obrigatória.',
            'password.string' => 'A senha precisa ser no formato string.',
            'password.min' => 'A senha deve conter no mínimo 6 caracteres.',
            'password.same' => 'A senha e a confirmação de senha estão diferentes.',
            'password_confirmation.required' => 'A confirmação de senha é obrigatória.',
        ];
    }

    /**
     * Overriding the event validator for custom error response.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     *
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json(
                ["status" => false, "erros" => $validator->errors()], 
                422,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            )
        );
    }
}
