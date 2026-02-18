<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlternativasResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'texto' => $this->texto,
            'img' => $this->img,
            'resposta_correta' => $this->resposta_correta,
            'questoes_id' => $this->questoes_id,
            'status' => $this->status,
            // 'criado_por' => $this->criado_por,
            // 'atualizado_por' => $this->atualizado_por,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ];
    }
}
