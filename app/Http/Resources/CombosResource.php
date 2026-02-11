<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CombosResource extends JsonResource
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
                'nome' => $this->nome,
                'tipo' => $this->tipo,
                'descricao' => $this->descricao,
                'codigo_barras' => $this->codigo_barras,
                'qr_code' => $this->qr_code,
                'img_1' => $this->img_1,
                'img_2' => $this->img_2,
                'img_3' => $this->img_3,
                'instalacoes_id' => $this->instalacoes_id,
                // 'criado_por' => $this->criado_por,
                // 'atualizado_por' => $this->atualizado_por,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'combo_produtos' => $this->combo_produtos->map(function ($combo_produto) {
                    return [
                        'id' => $combo_produto->id,
                        'combos_id' => $combo_produto->combos_id,
                        'produtos_id' => $combo_produto->produtos_id,
                        'status' => $combo_produto->status,
                        'created_at' => $combo_produto->created_at,
                        'updated_at' => $combo_produto->updated_at,
                        'produto' =>  [
                            'id' => $combo_produto->produto?->id,
                            'nome' => $combo_produto->produto?->nome,
                            'tipo' => $combo_produto->produto?->tipo,
                            'descricao' => $combo_produto->produto?->descricao,
                            'codigo_barras' => $combo_produto->produto?->codigo_barras,
                            'qr_code' => $combo_produto->produto?->qr_code,
                            'img_1' => $combo_produto->produto?->img_1,
                            'img_2' => $combo_produto->produto?->img_2,
                            'img_3' => $combo_produto->produto?->img_3,
                            'marcas_id' => $combo_produto->produto?->marcas_id,
                            // 'criado_por' => $combo_produto->produto?->criado_por,
                            // 'atualizado_por' => $combo_produto->produto?->atualizado_por,
                            'status' => $combo_produto->produto?->status,
                            'created_at' => $combo_produto->produto?->created_at,
                            'updated_at' => $combo_produto->produto?->updated_at,
                            'estoque' => $combo_produto->produto?->estoque
                            ? [
                                'id' => $combo_produto->produto?->estoque?->id,
                                'desconto_compra' => $combo_produto->produto?->estoque?->desconto_compra,
                                'preco_compra_original' => $combo_produto->produto?->estoque?->preco_compra_original,
                                'preco_compra_desconto' => $combo_produto->produto?->estoque?->preco_compra_desconto,
                                'desconto_venda' => $combo_produto->produto?->estoque?->desconto_venda,
                                'preco_venda_original' => $combo_produto->produto?->estoque?->preco_venda_original,
                                'preco_venda_desconto' => $combo_produto->produto?->estoque?->preco_venda_desconto,
                                'preco_venda_avista' => $combo_produto->produto?->estoque?->preco_venda_avista,
                                'vendido' => $combo_produto->produto?->estoque?->vendido,
                                'desconto_pagamento_avista' => $combo_produto->produto?->estoque?->desconto_pagamento_avista,
                                'vencimento' => $combo_produto->produto?->estoque?->vencimento,
                                'produtos_id' => $combo_produto->produto?->estoque?->produtos_id,
                                'instalacoes_id' => $combo_produto->produto?->estoque?->instalacoes_id,
                                // 'criado_por' => $combo_produto->produto?->estoque?->criado_por,
                                // 'atualizado_por' => $combo_produto->produto?->estoque?->atualizado_por,
                                'status' => $combo_produto->produto?->estoque?->status,
                                'created_at' => $combo_produto->produto?->estoque?->created_at,
                                'updated_at' => $combo_produto->produto?->estoque?->updated_at,
                            ]
                            : null,
                        ],
                    ];
                }),
            ],
        ];
    }
}
