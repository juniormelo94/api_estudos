<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class PermissoesRequest extends FormRequest
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
            'chave' => ['required', 'string', 'max:200', Rule::unique('permissoes', 'chave')->ignore($this->id)],
            'grupo' => ['nullable', 'string', 'max:200'],
            'descricao' => ['nullable', 'string', 'max:200'],
            'criado_por' => ['nullable', 'string'],
            'atualizado_por' => ['nullable', 'string'],
            'status' => ['required', 'string'],
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
            'chave.required' => 'A chave é obrigatória.',
            'chave.string' => 'A chave precisa ser no formato string.',
            'chave.max' => 'A chave deve conter no máximo 200 caracteres.',
            'chave.unique' => 'Já existe um cadastro com esse nome de chave.',
            'grupo.string' => 'O grupo precisa ser no formato string.',
            'grupo.max' => 'O grupo deve conter no máximo 200 caracteres.',
            'descricao.string' => 'A descrição precisa ser no formato string.',
            'descricao.max' => 'A descrição deve conter no máximo 200 caracteres.',
            'criado_por.string' => 'O criado por precisa ser no formato string.',
            'atualizado_por.string' => 'O atualizado por precisa ser no formato string.',
            'status.required' => 'O status é obrigatório.',
            'status.string' => 'O status precisa ser no formato string.',
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
