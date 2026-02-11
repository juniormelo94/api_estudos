<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstoquesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => true,
            'data' => [
                'id' => $this->id,
                'desconto_compra' => $this->desconto_compra,
                'preco_compra_original' => $this->preco_compra_original,
                'preco_compra_desconto' => $this->preco_compra_desconto,
                'desconto_venda' => $this->desconto_venda,
                'preco_venda_original' => $this->preco_venda_original,
                'preco_venda_desconto' => $this->preco_venda_desconto,
                'preco_venda_avista' => $this->preco_venda_avista,
                'vendido' => $this->vendido,
                'vencimento' => $this->vencimento,
                'desconto_pagamento_avista' => $this->desconto_pagamento_avista,
                'produtos_id' => $this->produtos_id,
                'instalacoes_id' => $this->instalacoes_id,
                // 'criado_por' => $this->criado_por,
                // 'atualizado_por' => $this->atualizado_por,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'produto' =>  [
                    'id' => $this->produto?->id,
                    'nome' => $this->produto?->nome,
                    'tipo' => $this->produto?->tipo,
                    'descricao' => $this->produto?->descricao,
                    'codigo_barras' => $this->produto?->codigo_barras,
                    'qr_code' => $this->produto?->qr_code,
                    'img_1' => $this->produto?->img_1,
                    'marcas_id' => $this->produto?->marcas_id,
                    // 'criado_por' => $this->produto?->criado_por,
                    // 'atualizado_por' => $this->produto?->atualizado_por,
                    'status' => $this->produto?->status,
                    'created_at' => $this->produto?->created_at,
                    'updated_at' => $this->produto?->updated_at,
                ],
                'venda_estoque' => $this->venda_estoque
                ? [
                    'id' => $this->venda_estoque?->id,
                    'vendas_id' => $this->venda_estoque?->vendas_id,
                    'estoques_id' => $this->venda_estoque?->estoques_id,
                    'status' => $this->venda_estoque?->status,
                    'created_at' => $this->venda_estoque?->created_at,
                    'updated_at' => $this->venda_estoque?->updated_at,
                    'venda' => [
                        'id' => $this->venda_estoque?->venda?->id,
                        'preco_total' => $this->venda_estoque?->venda?->preco_total,
                        'lucro_total_original' => $this->venda_estoque?->venda?->lucro_total_original,
                        'lucro_total_desconto' => $this->venda_estoque?->venda?->lucro_total_desconto,
                        'maquina_cartao' => $this->venda_estoque?->venda?->maquina_cartao,
                        'quantidade_parcelas' => $this->venda_estoque?->venda?->quantidade_parcelas,
                        'valor_pacelas' => $this->venda_estoque?->venda?->valor_pacelas ? number_format($this->venda_estoque?->venda?->valor_pacelas, 2) : $this->venda_estoque?->venda?->valor_pacelas,
                        'taxa_juros' => $this->venda_estoque?->venda?->taxa_juros,
                        'formas_pagamentos_id' => $this->venda_estoque?->venda?->formas_pagamentos_id,
                        'clientes_id' => $this->venda_estoque?->venda?->clientes_id,
                        'colaboradores_id' => $this->venda_estoque?->venda?->colaboradores_id,
                        'instalacoes_id' => $this->venda_estoque?->venda?->instalacoes_id,
                        // 'criado_por' => $this->venda_estoque?->venda?->criado_por,
                        // 'atualizado_por' => $this->venda_estoque?->venda?->atualizado_por,
                        'status' => $this->venda_estoque?->venda?->status,
                        'created_at' => $this->venda_estoque?->venda?->created_at,
                        'updated_at' => $this->venda_estoque?->venda?->updated_at,
                    ]
                ]
                : null,
            ],
        ];
    }
}
