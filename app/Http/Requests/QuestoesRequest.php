<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class QuestoesRequest extends FormRequest
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
            // Regras relacionadas a tabela Questoes
            'texto' => ['nullable', 'string', 'min:1'],
            'img' => ['nullable', 'string'],
            'disciplinas_id' => ['required', 'integer', Rule::exists('disciplinas', 'id')],
            'status' => ['required', 'string'],
            'criado_por' => ['nullable', 'integer', 'min:1'],
            'atualizado_por' => ['nullable', 'integer', 'min:1'],
            // Regras relacionadas a tabela Alternativas
            'alternativas' => ['required', 'array'],
            'alternativas.*.texto' => ['nullable', 'string', 'min:1'],
            'alternativas.*.img' => ['nullable', 'string'],
            'alternativas.*.resposta_correta' => ['required', 'boolean'],
            'alternativas.*.status' => ['required', 'string'],
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
            // Mensagens da tabela Questoes
            'texto.required' => 'O texto é obrigatório.',
            'texto.string' => 'O texto precisa ser no formato string.',
            'texto.max' => 'O texto deve conter o valor mínimo de 1 caractere.',
            'img.string' => 'A imagem precisa ser no formato string.',
            'disciplinas_id.required' => 'A disciplina é obrigatória.',
            'disciplinas_id.integer' => 'A disciplina precisa ser no formato inteiro.',
            'disciplinas_id.exists' => 'A disciplina com id :input não existe.',
            'status.required' => 'O status é obrigatório.',
            'status.string' => 'O status precisa ser no formato string.',
            // Mensagens da tabela Alternativas
            'alternativas.required' => 'As alternativas são obrigatórios.',
            'alternativas.array' => 'As alternativas devem vir em um array.',
            'alternativas.*.texto.required' => 'O texto das alternativas são obrigatório.',
            'alternativas.*.texto.string' => 'O texto das alternativas precisam ser no formato string.',
            'alternativas.*.texto.min' => 'O texto das alternativas devem conter o valor mínimo de 1 caractere.',
            'alternativas.*.img.string' => 'A imagem das alternativas precisam ser no formato string.',
            'alternativas.*.resposta_correta.required' => 'A resposta correta das alternativas são obrigatórias.',
            'alternativas.*.resposta_correta.boolean' => 'A resposta correta das alternativas precisam ser no formato booleano.',
            'alternativas.*.status.required' => 'O status das alternativas são obrigatórios',
            'alternativas.*.status.string' => 'O status das alternativas precisam ser no formato inteiro.',
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
