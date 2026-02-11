<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstalacoesProdutosResource extends JsonResource
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
                'observacoes' => $this->observacoes,
                'instalacoes_id' => $this->instalacoes_id,
                'produtos_id' => $this->produtos_id,
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
                    'img_2' => $this->produto?->img_2,
                    'img_3' => $this->produto?->img_3,
                    'marcas_id' => $this->produto?->marcas_id,
                    // 'criado_por' => $this->produto?->criado_por,
                    // 'atualizado_por' => $this->produto?->atualizado_por,
                    'status' => $this->produto?->status,
                    'created_at' => $this->produto?->created_at,
                    'updated_at' => $this->produto?->updated_at,
                ],
                'estoque' => $this->estoque->map(function ($estoque) {
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
            ],
        ];
    }
}
