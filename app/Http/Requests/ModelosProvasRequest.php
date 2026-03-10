<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class ModelosProvasRequest extends FormRequest
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
            // Regras relacionadas a tabela ModelosProvas
            'nome' => ['required', 'string', 'max:200'],
            'status' => ['required', 'string'],
            'criado_por' => ['nullable', 'integer', 'min:1'],
            'atualizado_por' => ['nullable', 'integer', 'min:1'],
            // Regras relacionadas a tabela ModelosProvasDisciplinas
            'modelos_provas_disciplinas' => ['required', 'array'],
            'modelos_provas_disciplinas.*.disciplinas_id' => ['required', 'integer', Rule::exists('disciplinas', 'id')],
            'modelos_provas_disciplinas.*.qtd_questoes' => ['required', 'integer', 'min:1'],
            'modelos_provas_disciplinas.*.status' => ['required', 'string'],
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
            // Mensagens da tabela ModelosProvas
            'nome.required' => 'O nome é obrigatório.',
            'nome.string' => 'O nome precisa ser no formato string.',
            'nome.max' => 'O nome deve conter o valor máximo de 200 caracteres.',
            'status.required' => 'O status é obrigatório.',
            'status.string' => 'O status precisa ser no formato string.',
            // Mensagens da tabela ModelosProvasDisciplinas
            'modelos_provas_disciplinas.required' => 'Os modelos disciplinas são obrigatórios.',
            'modelos_provas_disciplinas.array' => 'Os modelos disciplinas devem vir em um array.',
            'modelos_provas_disciplinas.*.disciplinas_id.required' => 'A disciplina dos modelos disciplinas são obrigatórios.',
            'modelos_provas_disciplinas.*.disciplinas_id.integer' => 'A disciplina dos modelos disciplinas precisam ser no formato inteiro.',
            'modelos_provas_disciplinas.*.disciplinas_id.exists' => 'A disciplina com id :input não existe.',
            'modelos_provas_disciplinas.*.qtd_questoes.required' => 'A quantidade de questões dos modelos disciplinas são obrigatórios.',
            'modelos_provas_disciplinas.*.qtd_questoes.integer' => 'A quantidade de questões dos modelos disciplinas precisam ser no formato inteiro.',
            'modelos_provas_disciplinas.*.qtd_questoes.min' => 'A quantidade de questões dos modelos disciplinas devem ser maior que 0.',
            'modelos_provas_disciplinas.*.status.required' => 'O status dos modelos disciplinas são obrigatórios',
            'modelos_provas_disciplinas.*.status.string' => 'O status dos modelos disciplinas precisam ser no formato inteiro.',
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
