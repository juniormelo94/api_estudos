<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ModelosProvasDisciplinasResource extends JsonResource
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
            'modelos_provas_id' => $this->modelos_provas_id,
            'disciplinas_id' => $this->disciplinas_id,
            'qtd_questoes' => $this->qtd_questoes,
            'status' => $this->status,
            // 'criado_por' => $this->criado_por,
            // 'atualizado_por' => $this->atualizado_por,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'disciplina' => new DisciplinasResource(
                $this->whenLoaded('disciplina')
            ),
        ];
    }
}
