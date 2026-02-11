<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstalacoesResource extends JsonResource
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
                'endereco' => $this->endereco,
                'bairro' => $this->bairro,
                'numero' => $this->numero,
                'complemento' => $this->complemento,
                'cep' => $this->cep,
                'cidade' => $this->cidade,
                'uf' => $this->uf,
                'email' => $this->email,
                'telefone' => $this->telefone,
                'celular' => $this->celular,
                'whatsapp' => $this->whatsapp,
                'instagram' => $this->instagram,
                'facebook' => $this->facebook,
                'divisoes_id' => $this->divisoes_id,
                // 'criado_por' => $this->criado_por,
                // 'atualizado_por' => $this->atualizado_por,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
        ];
    }
}
