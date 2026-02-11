<?php

namespace App\Http\Requests;

use App\Models\Estoques;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use App\Models\VendasEstoques;

class VendasRequest extends FormRequest
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
            // Regras relacionadas a tabela Vendas
            // 'preco_total' => ['required', 'numeric', 'min:0'],
            'preco_total' => ['nullable', 'numeric', 'min:0'],
            // 'lucro_total_original' => ['required', 'numeric', 'min:0'],
            'lucro_total_original' => ['nullable', 'numeric', 'min:0'],
            'lucro_total_desconto' => ['nullable', 'numeric', 'min:0'],
            'maquina_cartao' => ['nullable', 'string', 'max:100'],
            'quantidade_parcelas' => ['nullable', 'integer', 'min:1'],
            'valor_pacelas' => ['nullable', 'numeric', 'min:0'],
            'taxa_juros' => ['nullable', 'numeric'],
            'formas_pagamentos_id' => ['required', 'integer', Rule::exists('formas_pagamentos', 'id')],
            'clientes_id' => ['required', 'integer', Rule::exists('clientes', 'id')],
            'colaboradores_id' => ['required', 'integer', Rule::exists('colaboradores', 'id')],
            'instalacoes_id' => ['required', 'integer', Rule::exists('instalacoes', 'id')],
            'criado_por' => ['nullable', 'string'],
            'atualizado_por' => ['nullable', 'string'],
            'status' => ['required', 'string'],
            // Regras relacionadas a tabela VendasEstoques
            'estoques_ids' => ['required', 'array'],
            'estoques_ids.*' => [
                'required', 
                'integer', 
                Rule::exists('estoques', 'id'),
                function ($attribute, $value, $fail) {
                    if ($this->id == null && (Estoques::where('id', $value)->where('vendido', true)->exists()
                        || VendasEstoques::where('estoques_id', $value)->exists())) {
                        $fail("O item do estoque com id :{$value} não está disponível.");
                    }
                },
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
            'preco_total.required' => 'O preço total é obrigatório.',
            'preco_total.numeric' => 'O preço total precisa ser no formato numérico.',
            'preco_total.min' => 'O preço total deve conter o valor mínimo de 0.',
            'lucro_total_original.required' => 'O lucro total original é obrigatório.',
            'lucro_total_original.numeric' => 'O lucro total original precisa ser no formato numérico.',
            'lucro_total_original.min' => 'O lucro total original deve conter o valor mínimo de 0.',
            'lucro_total_desconto.numeric' => 'O lucro total com desconto precisa ser no formato numérico.',
            'lucro_total_desconto.min' => 'O lucro total com desconto deve conter o valor mínimo de 0.',
            'maquina_cartao.string' => 'A maquina de cartão precisa ser no formato string.',
            'maquina_cartao.max' => 'A maquina de cartão deve conter no máximo 100 caracteres.',
            'quantidade_parcelas.integer' => 'A quantidade de parcelas precisa ser no formato inteiro.',
            'quantidade_parcelas.min' => 'A quantidade de parcelas deve conter o valor mínimo de 1.',
            'valor_pacelas.numeric' => 'O valor das pacelas precisa ser no formato numérico.',
            'valor_pacelas.min' => 'O valor das pacelas deve conter o valor mínimo de 0.',
            'taxa_juros.numeric' => 'A taxa de juros precisa ser no formato numérico.',
            'formas_pagamentos_id.required' => 'O id da forma de pagamento é obrigatório.',
            'formas_pagamentos_id.exists' => 'A forma de pagamento com id :input não existe.',
            'clientes_id.required' => 'O id do cliente é obrigatório.',
            'clientes_id.exists' => 'O cliente com id :input não existe.',
            'colaboradores_id.required' => 'O id do colaborador é obrigatório.',
            'colaboradores_id.exists' => 'O colaborador com id :input não existe.',
            'instalacoes_id.required' => 'O id da instalação é obrigatório.',
            'instalacoes_id.exists' => 'A instalação com id :input não existe.',
            'criado_por.string' => 'O criado por precisa ser no formato string.',
            'atualizado_por.string' => 'O atualizado por precisa ser no formato string.',
            'status.required' => 'O status é obrigatório.',
            'status.string' => 'O status precisa ser no formato string.',
            'estoques_ids.required' => 'Os id´s dos estoques são obrigatórios.',
            'estoques_ids.array' => 'Os id´s dos estoques devem vir em um array.',
            'estoques_ids.*.exists' => 'O item do estoque com id :input não existe.'
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
