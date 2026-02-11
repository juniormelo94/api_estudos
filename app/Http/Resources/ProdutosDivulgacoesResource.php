<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutosDivulgacoesResource extends JsonResource
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
            'data' => $this->resource ? [
                'id' => $this->id,
                'img_padrao_1' => $this->img_padrao_1,
                'img_padrao_2' => $this->img_padrao_2,
                'img_padrao_3' => $this->img_padrao_3,
                'img_promocao_1' => $this->img_promocao_1,
                'img_promocao_2' => $this->img_promocao_2,
                'img_promocao_3' => $this->img_promocao_3,
                'produtos_id' => $this->produtos_id,
                // 'criado_por' => $this->criado_por,
                // 'atualizado_por' => $this->atualizado_por,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]
            : null,
        ];
    }
}
