<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class EstoquesRequest extends FormRequest
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
            'desconto_compra' => ['nullable', 'numeric', 'min:0'],
            'preco_compra_original' => ['required', 'numeric', 'min:0'],
            'preco_compra_desconto' => ['nullable', 'numeric', 'min:0'],
            'desconto_venda' => ['nullable', 'numeric', 'min:0'],
            'preco_venda_original' => ['required', 'numeric', 'min:0'],
            'preco_venda_desconto' => ['nullable', 'numeric', 'min:0'],
            'preco_venda_avista' => ['nullable', 'numeric', 'min:0'],
            'vendido' => ['required', 'boolean'],
            'desconto_pagamento_avista' => ['required', 'boolean'],
            'vencimento' => ['nullable', 'date', 'after:today'],
            'produtos_id' => ['required', 'integer', Rule::exists('produtos', 'id')],
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
            'desconto_compra.numeric' => 'O desconto de compra precisa ser no formato numérico.',
            'desconto_compra.min' => 'O desconto de compra deve conter o valor mínimo de 0.',
            'preco_compra_original.required' => 'O preço de compra original é obrigatório.',
            'preco_compra_original.numeric' => 'O preço de compra original precisa ser no formato numérico.',
            'preco_compra_original.min' => 'O preço de compra original deve conter o valor mínimo de 0.',
            'preco_compra_desconto.numeric' => 'O preço de compra com desconto precisa ser no formato numérico.',
            'preco_compra_desconto.min' => 'O preço de compra com desconto deve conter o valor mínimo de 0.',
            'desconto_venda.numeric' => 'O desconto de venda precisa ser no formato numérico.',
            'desconto_venda.min' => 'O desconto de venda deve conter o valor mínimo de 0.',
            'preco_venda_original.required' => 'O preço de venda original é obrigatório.',
            'preco_venda_original.numeric' => 'O preço de venda original precisa ser no formato numérico.',
            'preco_venda_original.min' => 'O preço de venda original deve conter o valor mínimo de 0.',
            'preco_venda_desconto.numeric' => 'O preço de venda com desconto precisa ser no formato numérico.',
            'preco_venda_desconto.min' => 'O preço de venda com desconto deve conter o valor mínimo de 0.',
            'preco_venda_avista.numeric' => 'O preço de venda à vista precisa ser no formato numérico.',
            'preco_venda_avista.min' => 'O preço de venda à vista deve conter o valor mínimo de 0.',
            'vendido.required' => 'O item foi vendido é obrigatório.',
            'vendido.boolean' => 'O item foi vendido precisa ser no formato booleano.',
            'desconto_pagamento_avista.required' => 'O desconto de pagamento à vista é obrigatório.',
            'desconto_pagamento_avista.boolean' => 'O desconto de pagamento à vista precisa ser no formato booleano.',
            'vencimento.date' => 'A data de vencimento precisa ser no formato data.',
            'vencimento.after' => 'A data de vencimento deve ser uma data futura.',
            'produtos_id.required' => 'O id da produto é obrigatório.',
            'produtos_id.exists' => 'O produto com id :input não existe.',
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
