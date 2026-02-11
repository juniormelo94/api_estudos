<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogsResource extends JsonResource
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
                'mensagem_erro' => $this->mensagem_erro,
                'codigo_erro' => $this->codigo_erro,
                'arquivo_erro' => $this->arquivo_erro,
                'linha_erro' => $this->linha_erro,
                'rastreamento_erro' => $this->rastreamento_erro,
                // 'criado_por' => $this->criado_por,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
        ];
    }
}
