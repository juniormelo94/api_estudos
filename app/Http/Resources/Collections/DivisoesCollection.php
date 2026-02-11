<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DivisoesCollection extends ResourceCollection
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
                    'ramo' => $modelo->ramo,
                    'cnpj' => $modelo->cnpj,
                    'cor_primaria' => $modelo->cor_primaria,
                    'cor_secundaria' => $modelo->cor_secundaria,
                    'cor_tercearia' => $modelo->cor_tercearia,
                    'logo_img' => $modelo->logo_img,
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
