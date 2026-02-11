<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class TiposUsersRequest extends FormRequest
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
            // Regras relacionadas a tabela TiposUsers
            'tipo' => ['required', 'string', 'max:200', Rule::unique('tipos_users', 'tipo')->ignore($this->id)],
            'descricao' => ['nullable', 'string', 'max:200'],
            'criado_por' => ['nullable', 'string'],
            'atualizado_por' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            // Regras relacionadas a tabela TiposUsersPermissoes
            'permissoes_ids' => ['nullable', 'array'],
            'permissoes_ids.*' => [
                'required', 
                'integer', 
                Rule::exists('permissoes', 'id'),
            ],
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
            'tipo.required' => 'O tipo é obrigatório.',
            'tipo.string' => 'O tipo precisa ser no formato string.',
            'tipo.max' => 'O tipo deve conter no máximo 200 caracteres.',
            'tipo.unique' => 'Já existe um cadastro com esse nome de tipo.',
            'descricao.string' => 'A descrição precisa ser no formato string.',
            'descricao.max' => 'A descrição deve conter no máximo 200 caracteres.',
            'criado_por.string' => 'O criado por precisa ser no formato string.',
            'atualizado_por.string' => 'O atualizado por precisa ser no formato string.',
            'status.required' => 'O status é obrigatório.',
            'status.string' => 'O status precisa ser no formato string.',
            'permissoes_ids.array' => 'Os id´s das permissões devem vir em um array.',
            'permissoes_ids.*.exists' => 'A permissão com id :input não existe.'
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
