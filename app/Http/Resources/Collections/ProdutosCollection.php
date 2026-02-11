<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProdutosCollection extends ResourceCollection
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
                    'nome' => $modelo->nome,
                    'tipo' => $modelo->tipo,
                    'descricao' => $modelo->descricao,
                    'codigo_barras' => $modelo->codigo_barras,
                    'qr_code' => $modelo->qr_code,
                    'img_1' => $modelo->img_1,
                    'img_2' => $modelo->img_2,
                    'img_3' => $modelo->img_3,
                    'marcas_id' => $modelo->marcas_id,
                    // 'criado_por' => $modelo->criado_por,
                    // 'atualizado_por' => $modelo->atualizado_por,
                    'status' => $modelo->status,
                    'created_at' => $modelo->created_at,
                    'updated_at' => $modelo->updated_at,
                    'divulgacao_existe' => $modelo->divulgacao_existe,
                    'instalacao_produtos' => $modelo->instalacao_produtos,
                ];
            }),
        ]; 
    }
}
