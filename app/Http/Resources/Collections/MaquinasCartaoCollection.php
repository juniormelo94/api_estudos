<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MaquinasCartaoCollection extends ResourceCollection
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
                    'modelo' => $modelo->modelo,
                    'empresa_responsavel' => $modelo->empresa_responsavel,
                    'bandeiras_aceitas' => $modelo->bandeiras_aceitas,
                    'taxa_debito' => $modelo->taxa_debito,
                    'taxas_parcelas' => json_decode($modelo->taxas_parcelas),
                    'taxas_links_parcelas' => json_decode($modelo->taxas_links_parcelas),
                    'taxa_pix' => $modelo->taxa_pix,
                    'instalacoes_id' => $modelo->instalacoes_id,
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
