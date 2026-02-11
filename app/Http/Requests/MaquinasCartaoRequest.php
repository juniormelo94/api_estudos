<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class MaquinasCartaoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'taxas_parcelas' => json_encode($this->taxas_parcelas),
            'taxas_links_parcelas' => json_encode($this->taxas_links_parcelas),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'modelo' => ['required', 'string', 'max:100'],
            'empresa_responsavel' => ['required', 'string', 'max:100'],
            'bandeiras_aceitas' => ['required', 'array'],
            'taxa_debito' => ['nullable', 'numeric', 'min:0'],
            'taxas_parcelas' => ['required', 'json'],
            'taxas_links_parcelas' => ['nullable', 'json'],
            'taxa_pix' => ['nullable', 'numeric', 'min:0'],
            'instalacoes_id' => ['required', 'integer', Rule::exists('instalacoes', 'id')],
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
            'modelo.required' => 'O modelo é obrigatório.',
            'modelo.string' => 'O modelo precisa ser no formato string.',
            'modelo.max' => 'O modelo deve conter no máximo 100 caracteres.',
            'empresa_responsavel.required' => 'A empresa responsável é obrigatório.',
            'empresa_responsavel.string' => 'A empresa responsável precisa ser no formato string.',
            'empresa_responsavel.max' => 'A empresa responsável deve conter no máximo 100 caracteres.',
            'bandeiras_aceitas.required' => 'As bandeiras aceitas são obrigatórias.',
            'bandeiras_aceitas.array' => 'As bandeiras aceitas devem ser um array.',
            'taxa_debito.numeric' => 'A taxa de débito precisa ser no formato numérico.',
            'taxa_debito.min' => 'A taxa de débito deve conter o valor mínimo de 0.',
            'taxas_parcelas.required' => 'As taxas das parcelas são obrigatórias.',
            'taxas_parcelas.json' => 'As taxas das parcelas devem ser uma string json.',
            'taxas_links_parcelas.json' => 'As taxas dos links das parcelas devem ser uma string json.',
            'taxa_pix.numeric' => 'A taxa do pix precisa ser no formato numérico.',
            'taxa_pix.min' => 'A taxa do pix deve conter o valor mínimo de 0.',
            'instalacoes_id.required' => 'O id da instalação é obrigatório.',
            'instalacoes_id.exists' => 'A instalação com id :input não existe.',
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
