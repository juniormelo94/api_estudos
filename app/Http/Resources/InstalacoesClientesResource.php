<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstalacoesClientesResource extends JsonResource
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
                'clientes_id' => $this->clientes_id,
                // 'criado_por' => $this->criado_por,
                // 'atualizado_por' => $this->atualizado_por,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'cliente' =>  [
                    'id' => $this->cliente?->id,
                    'nome_completo' => $this->cliente?->nome_completo,
                    'primeiro_nome' => $this->cliente?->primeiro_nome,
                    'ultimo_nome' => $this->cliente?->ultimo_nome,
                    'apelido' => $this->cliente?->apelido,
                    'cpf' => $this->cliente?->cpf,
                    'data_nascimento' => $this->cliente?->data_nascimento,
                    'rg' => $this->cliente?->rg,
                    'sexo' => $this->cliente?->sexo,
                    'estado_civil' => $this->cliente?->estado_civil,
                    'img' => $this->cliente?->img,
                    'email_pessoal' => $this->cliente?->email_pessoal,
                    'telefone_pessoal' => $this->cliente?->telefone_pessoal,
                    'celular_pessoal' => $this->cliente?->celular_pessoal,
                    'whatsapp_pessoal' => $this->cliente?->whatsapp_pessoal,
                    'instagram_pessoal' => $this->cliente?->instagram_pessoal,
                    'facebook_pessoal' => $this->cliente?->facebook_pessoal,
                    // 'criado_por' => $this->cliente?->criado_por,
                    // 'atualizado_por' => $this->cliente?->atualizado_por,
                    'status' => $this->cliente?->status,
                    'created_at' => $this->cliente?->created_at,
                    'updated_at' => $this->cliente?->updated_at,
                ],
            ],
        ];
    }
}
