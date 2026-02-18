<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestoesResource extends JsonResource
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
            'disciplinas_id' => $this->disciplinas_id,
            'status' => $this->status,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'alternativas' =>
                AlternativasResource::collection(
                    $this->whenLoaded('alternativas')
            )
        ];
    }
}
