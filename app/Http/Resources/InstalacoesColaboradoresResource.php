<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstalacoesColaboradoresResource extends JsonResource
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
                'colaboradores_id' => $this->colaboradores_id,
                // 'criado_por' => $this->criado_por,
                // 'atualizado_por' => $this->atualizado_por,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'colaborador' =>  [
                    'id' => $this->colaborador?->id,
                    'nome_completo' => $this->colaborador?->nome_completo,
                    'primeiro_nome' => $this->colaborador?->primeiro_nome,
                    'ultimo_nome' => $this->colaborador?->ultimo_nome,
                    'apelido' => $this->colaborador?->apelido,
                    'cpf' => $this->colaborador?->cpf,
                    'data_nascimento' => $this->colaborador?->data_nascimento,
                    'rg' => $this->colaborador?->rg,
                    'sexo' => $this->colaborador?->sexo,
                    'estado_civil' => $this->colaborador?->estado_civil,
                    'img' => $this->colaborador?->img,
                    'email_pessoal' => $this->colaborador?->email_pessoal,
                    'telefone_pessoal' => $this->colaborador?->telefone_pessoal,
                    'celular_pessoal' => $this->colaborador?->celular_pessoal,
                    'whatsapp_pessoal' => $this->colaborador?->whatsapp_pessoal,
                    'instagram_pessoal' => $this->colaborador?->instagram_pessoal,
                    'facebook_pessoal' => $this->colaborador?->facebook_pessoal,
                    // 'criado_por' => $this->colaborador?->criado_por,
                    // 'atualizado_por' => $this->colaborador?->atualizado_por,
                    'status' => $this->colaborador?->status,
                    'created_at' => $this->colaborador?->created_at,
                    'updated_at' => $this->colaborador?->updated_at,
                ],
            ],
        ];
    }
}
