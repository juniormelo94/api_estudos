<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FormasPagamentosCollection extends ResourceCollection
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
                    'tipo_pagamento' => $modelo->tipo_pagamento,
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
