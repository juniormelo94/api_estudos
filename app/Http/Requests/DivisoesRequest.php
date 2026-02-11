<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class DivisoesRequest extends FormRequest
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
            'nome' => ['required', 'string', 'max:200', Rule::unique('divisoes', 'nome')->ignore($this->id)],
            'ramo' => ['required', 'string', 'max:200'],
            'cnpj' => ['nullable', 'string', 'max:50'],
            'cor_primaria' => ['required', 'string'],
            'cor_secundaria' => ['nullable', 'string'],
            'cor_tercearia' => ['nullable', 'string'],
            'logo_img' => ['nullable', 'string'],
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
            'nome.required' => 'O nome é obrigatório.',
            'nome.string' => 'O nome precisa ser no formato string.',
            'nome.max' => 'O nome deve conter no máximo 200 caracteres.',
            'nome.unique' => 'Já existe um cadastro com esse nome.',
            'ramo.required' => 'O ramo é obrigatório.',
            'ramo.string' => 'O ramo precisa ser no formato string.',
            'ramo.max' => 'O ramo deve conter no máximo 200 caracteres.',
            'cnpj.string' => 'O CPF precisa ser no formato string.',
            'cnpj.max' => 'O CPF deve conter no máximo 50 caracteres.',
            'cor_primaria.required' => 'A cor primaria é obrigatória.',
            'cor_primaria.string' => 'A cor primaria precisa ser no formato string.',
            'cor_secundaria.string' => 'A cor secundaria precisa ser no formato string.',
            'cor_tercearia.string' => 'A cor tercearia precisa ser no formato string.',
            'logo_img.string' => 'A logo precisa ser no formato string.',
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
