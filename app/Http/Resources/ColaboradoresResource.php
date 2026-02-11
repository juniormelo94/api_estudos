<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ColaboradoresResource extends JsonResource
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
                'nome_completo' => $this->nome_completo,
                'primeiro_nome' => $this->primeiro_nome,
                'ultimo_nome' => $this->ultimo_nome,
                'apelido' => $this->apelido,
                'cpf' => $this->cpf,
                'data_nascimento' => $this->data_nascimento,
                'rg' => $this->rg,
                'sexo' => $this->sexo,
                'estado_civil' => $this->estado_civil,
                'img' => $this->img,
                'email_pessoal' => $this->email_pessoal,
                'telefone_pessoal' => $this->telefone_pessoal,
                'celular_pessoal' => $this->celular_pessoal,
                'whatsapp_pessoal' => $this->whatsapp_pessoal,
                'instagram_pessoal' => $this->instagram_pessoal,
                'facebook_pessoal' => $this->facebook_pessoal,
                // 'criado_por' => $this->criado_por,
                // 'atualizado_por' => $this->atualizado_por,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'instalacao_colaboradores' => $this->instalacao_colaboradores,
            ],
        ];
    }
}
