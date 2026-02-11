<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaquinasCartaoResource extends JsonResource
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
                'modelo' => $this->modelo,
                'empresa_responsavel' => $this->empresa_responsavel,
                'bandeiras_aceitas' => $this->bandeiras_aceitas,
                'taxa_debito' => $this->taxa_debito,
                'taxas_parcelas' => json_decode($this->taxas_parcelas),
                'taxas_links_parcelas' => json_decode($this->taxas_links_parcelas),
                'taxa_pix' => $this->taxa_pix,
                'instalacoes_id' => $this->instalacoes_id,
                // 'criado_por' => $this->criado_por,
                // 'atualizado_por' => $this->atualizado_por,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
        ];
    }
}
