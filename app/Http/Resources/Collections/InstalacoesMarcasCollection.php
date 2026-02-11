<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InstalacoesMarcasCollection extends ResourceCollection
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
                    'marcas_id' => $modelo->marcas_id,
                    // 'criado_por' => $modelo->criado_por,
                    // 'atualizado_por' => $modelo->atualizado_por,
                    'status' => $modelo->status,
                    'created_at' => $modelo->created_at,
                    'updated_at' => $modelo->updated_at,
                    'marca' =>  [
                        'id' => $modelo->marca?->id,
                        'nome' => $modelo->marca?->nome,
                        'ramo' => $modelo->marca?->ramo,
                        'cnpj' => $modelo->marca?->cnpj,
                        'logo_img' => $modelo->marca?->logo_img,
                        // 'criado_por' => $modelo->marca?->criado_por,
                        // 'atualizado_por' => $modelo->marca?->atualizado_por,
                        'status' => $modelo->marca?->status,
                        'created_at' => $modelo->marca?->created_at,
                        'updated_at' => $modelo->marca?->updated_at,
                    ],
                ];
            }),
        ];
    }
}
