<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EstoquesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => true,
            'data' => $this->collection->transform(function ($modelo) {
                return [
                    'id' => $modelo->id,
                    'desconto_compra' => $modelo->desconto_compra,
                    'preco_compra_original' => $modelo->preco_compra_original,
                    'preco_compra_desconto' => $modelo->preco_compra_desconto,
                    'desconto_venda' => $modelo->desconto_venda,
                    'preco_venda_original' => $modelo->preco_venda_original,
                    'preco_venda_desconto' => $modelo->preco_venda_desconto,
                    'preco_venda_avista' => $modelo->preco_venda_avista,
                    'vendido' => $modelo->vendido,
                    'vencimento' => $modelo->vencimento,
                    'desconto_pagamento_avista' => $modelo->desconto_pagamento_avista,
                    'produtos_id' => $modelo->produtos_id,
                    'instalacoes_id' => $modelo->instalacoes_id,
                    // 'criado_por' => $modelo->criado_por,
                    // 'atualizado_por' => $modelo->atualizado_por,
                    'status' => $modelo->status,
                    'created_at' => $modelo->created_at,
                    'updated_at' => $modelo->updated_at,
                    'produto' =>  [
                        'id' => $modelo->produto?->id,
                        'nome' => $modelo->produto?->nome,
                        'tipo' => $modelo->produto?->tipo,
                        'descricao' => $modelo->produto?->descricao,
                        'codigo_barras' => $modelo->produto?->codigo_barras,
                        'qr_code' => $modelo->produto?->qr_code,
                        'img_1' => $modelo->produto?->img_1,
                        'marcas_id' => $modelo->produto?->marcas_id,
                        // 'criado_por' => $modelo->produto?->criado_por,
                        // 'atualizado_por' => $modelo->produto?->atualizado_por,
                        'status' => $modelo->produto?->status,
                        'created_at' => $modelo->produto?->created_at,
                        'updated_at' => $modelo->produto?->updated_at,
                    ],
                    'venda_estoque' => $modelo->venda_estoque
                    ? [
                        'id' => $modelo->venda_estoque?->id,
                        'vendas_id' => $modelo->venda_estoque?->vendas_id,
                        'estoques_id' => $modelo->venda_estoque?->estoques_id,
                        'status' => $modelo->venda_estoque?->status,
                        'created_at' => $modelo->venda_estoque?->created_at,
                        'updated_at' => $modelo->venda_estoque?->updated_at,
                        'venda' => [
                            'id' => $modelo->venda_estoque?->venda?->id,
                            'preco_total' => $modelo->venda_estoque?->venda?->preco_total,
                            'lucro_total_original' => $modelo->venda_estoque?->venda?->lucro_total_original,
                            'lucro_total_desconto' => $modelo->venda_estoque?->venda?->lucro_total_desconto,
                            'maquina_cartao' => $modelo->venda_estoque?->venda?->maquina_cartao,
                            'quantidade_parcelas' => $modelo->venda_estoque?->venda?->quantidade_parcelas,
                            'valor_pacelas' => $modelo->venda_estoque?->venda?->valor_pacelas ? number_format($modelo->venda_estoque?->venda?->valor_pacelas, 2) : $modelo->venda_estoque?->venda?->valor_pacelas,
                            'taxa_juros' => $modelo->venda_estoque?->venda?->taxa_juros,
                            'formas_pagamentos_id' => $modelo->venda_estoque?->venda?->formas_pagamentos_id,
                            'clientes_id' => $modelo->venda_estoque?->venda?->clientes_id,
                            'colaboradores_id' => $modelo->venda_estoque?->venda?->colaboradores_id,
                            'instalacoes_id' => $modelo->venda_estoque?->venda?->instalacoes_id,
                            // 'criado_por' => $modelo->venda_estoque?->venda?->criado_por,
                            // 'atualizado_por' => $modelo->venda_estoque?->venda?->atualizado_por,
                            'status' => $modelo->venda_estoque?->venda?->status,
                            'created_at' => $modelo->venda_estoque?->venda?->created_at,
                            'updated_at' => $modelo->venda_estoque?->venda?->updated_at,
                        ]
                    ]
                    : null,
                ];
            }),
        ]; 
    }
}
