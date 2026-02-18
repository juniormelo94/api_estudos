<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class ProvasRequest extends FormRequest
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
            'nome' => ['nullable', 'string', 'min:200'],
            'modelos_provas_id' => ['required', 'integer', Rule::exists('modelos_provas', 'id')],
            'status' => ['required', 'string'],
            'criado_por' => ['nullable', 'integer', 'min:1'],
            'atualizado_por' => ['nullable', 'integer', 'min:1'],
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
            'nome.max' => 'O nome deve conter o valor máximo de 200 caracteres.',
            'modelos_provas_id.required' => 'O modelo de prova é obrigatória.',
            'modelos_provas_id.integer' => 'O modelo de prova precisa ser no formato inteiro.',
            'modelos_provas_id.exists' => 'O modelo de prova com id :input não existe.',
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
