<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ColaboradoresUsersRequest extends FormRequest
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
            'cargo' => ['required', 'string', 'max:200'],
            'colaboradores_id' => [
                'required', 
                'integer', 
                Rule::unique('colaboradores_users', 'colaboradores_id')->ignore(optional($colaboradorUser)->id), 
                Rule::exists('colaboradores', 'id')
            ],
            'users_id' => ['required', 'integer', Rule::exists('users', 'id')],
            'tipos_users_id' => [
                'integer', 
                Rule::exists('tipos_users', 'id')
            ],
            'criado_por' => ['required', 'string'],
            'atualizado_por' => ['nullable', 'string'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cargo.required' => 'O cargo é obrigatório.',
            'cargo.string' => 'O cargo precisa ser no formato string.',
            'cargo.max' => 'O cargo deve conter no máximo 200 caracteres.',
            'colaboradores_id.required' => 'O id do colaborador é obrigatório.',
            'colaboradores_id.unique' => 'Já existe um usuário para esse colaborador.',
            'colaboradores_id.exists' => 'O colaborador com id :input não existe.',
            'users_id.required' => 'O id do usuário é obrigatório.',
            'users_id.exists' => 'O usuário com id :input não existe.',
            'tipos_users_id.required' => 'O id do tipo do usuário é obrigatório.',
            'tipos_users_id.exists' => 'O tipo do usuário com id :input não existe.',
            'criado_por.required' => 'O criado por é obrigatório.',
            'criado_por.string' => 'O criado por precisa ser no formato string.',
            'atualizado_por.string' => 'O atualizado por precisa ser no formato string.',
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
