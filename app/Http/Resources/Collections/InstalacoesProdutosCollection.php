<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InstalacoesProdutosCollection extends ResourceCollection
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
                    'observacoes' => $modelo->observacoes,
                    'instalacoes_id' => $modelo->instalacoes_id,
                    'produtos_id' => $modelo->produtos_id,
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
                        'img_2' => $modelo->produto?->img_2,
                        'img_3' => $modelo->produto?->img_3,
                        'marcas_id' => $modelo->produto?->marcas_id,
                        // 'criado_por' => $modelo->produto?->criado_por,
                        // 'atualizado_por' => $modelo->produto?->atualizado_por,
                        'status' => $modelo->produto?->status,
                        'created_at' => $modelo->produto?->created_at,
                        'updated_at' => $modelo->produto?->updated_at,
                    ],
                    'estoque' => $modelo->estoque->map(function ($estoque) {
                        return [
                            'id' => $estoque->id,
                            'desconto_compra' => $estoque->desconto_compra,
                            'preco_compra_original' => $estoque->preco_compra_original,
                            'preco_compra_desconto' => $estoque->preco_compra_desconto,
                            'desconto_venda' => $estoque->desconto_venda,
                            'preco_venda_original' => $estoque->preco_venda_original,
                            'preco_venda_desconto' => $estoque->preco_venda_desconto,
                            'preco_venda_avista' => $estoque->preco_venda_avista,
                            'vendido' => $estoque->vendido,
                            'desconto_pagamento_avista' => $estoque->desconto_pagamento_avista,
                            'vencimento' => $estoque->vencimento,
                            'produtos_id' => $estoque->produtos_id,
                            'instalacoes_id' => $estoque->instalacoes_id,
                            // 'criado_por' => $estoque->criado_por,
                            // 'atualizado_por' => $estoque->atualizado_por,
                            'status' => $estoque->status,
                            'created_at' => $estoque->created_at,
                            'updated_at' => $estoque->updated_at,
                        ];
                    }),
                ];
            }),
        ];
    }
}
