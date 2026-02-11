<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProdutosDivulgacoesCollection extends ResourceCollection
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
                    'img_padrao_1' => $modelo->img_padrao_1,
                    'img_padrao_2' => $modelo->img_padrao_2,
                    'img_padrao_3' => $modelo->img_padrao_3,
                    'img_promocao_1' => $modelo->img_promocao_1,
                    'img_promocao_2' => $modelo->img_promocao_2,
                    'img_promocao_3' => $modelo->img_promocao_3,
                    'produtos_id' => $modelo->produtos_id,
                    // 'criado_por' => $modelo->criado_por,
                    // 'atualizado_por' => $modelo->atualizado_por,
                    'status' => $modelo->status,
                    'created_at' => $modelo->created_at,
                    'updated_at' => $modelo->updated_at,
                ];
            }),
        ]; 
    }
}
