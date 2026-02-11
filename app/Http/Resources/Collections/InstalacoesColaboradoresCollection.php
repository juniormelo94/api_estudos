<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InstalacoesColaboradoresCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'status' => true,
            'data' => $this->collection->transform(function ($modelo) {
                return [
                    'id' => $modelo->id,
                    'observacoes' => $modelo->observacoes,
                    'instalacoes_id' => $modelo->instalacoes_id,
                    'colaboradores_id' => $modelo->colaboradores_id,
                    // 'criado_por' => $modelo->criado_por,
                    // 'atualizado_por' => $modelo->atualizado_por,
                    'status' => $modelo->status,
                    'created_at' => $modelo->created_at,
                    'updated_at' => $modelo->updated_at,
                    'colaborador' =>  [
                        'id' => $modelo->colaborador?->id,
                        'nome_completo' => $modelo->colaborador?->nome_completo,
                        'primeiro_nome' => $modelo->colaborador?->primeiro_nome,
                        'ultimo_nome' => $modelo->colaborador?->ultimo_nome,
                        'apelido' => $modelo->colaborador?->apelido,
                        'cpf' => $modelo->colaborador?->cpf,
                        'data_nascimento' => $modelo->colaborador?->data_nascimento,
                        'rg' => $modelo->colaborador?->rg,
                        'sexo' => $modelo->colaborador?->sexo,
                        'estado_civil' => $modelo->colaborador?->estado_civil,
                        'img' => $modelo->colaborador?->img,
                        'email_pessoal' => $modelo->colaborador?->email_pessoal,
                        'telefone_pessoal' => $modelo->colaborador?->telefone_pessoal,
                        'celular_pessoal' => $modelo->colaborador?->celular_pessoal,
                        'whatsapp_pessoal' => $modelo->colaborador?->whatsapp_pessoal,
                        'instagram_pessoal' => $modelo->colaborador?->instagram_pessoal,
                        'facebook_pessoal' => $modelo->colaborador?->facebook_pessoal,
                        // 'criado_por' => $modelo->colaborador?->criado_por,
                        // 'atualizado_por' => $modelo->colaborador?->atualizado_por,
                        'status' => $modelo->colaborador?->status,
                        'created_at' => $modelo->colaborador?->created_at,
                        'updated_at' => $modelo->colaborador?->updated_at,
                    ],
                ];
            }),
        ];
    }
}
