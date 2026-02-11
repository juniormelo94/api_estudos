<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstalacoesMarcasResource extends JsonResource
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
                'marcas_id' => $this->marcas_id,
                // 'criado_por' => $this->criado_por,
                // 'atualizado_por' => $this->atualizado_por,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'marca' =>  [
                    'id' => $this->marca?->id,
                    'nome' => $this->marca?->nome,
                    'ramo' => $this->marca?->ramo,
                    'cnpj' => $this->marca?->cnpj,
                    'logo_img' => $this->marca?->logo_img,
                    // 'criado_por' => $this->marca?->criado_por,
                    // 'atualizado_por' => $this->marca?->atualizado_por,
                    'status' => $this->marca?->status,
                    'created_at' => $this->marca?->created_at,
                    'updated_at' => $this->marca?->updated_at,
                ],
            ],
        ];
    }
}
