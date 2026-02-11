<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InstalacoesClientesCollection extends ResourceCollection
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
                    'clientes_id' => $modelo->clientes_id,
                    // 'criado_por' => $modelo->criado_por,
                    // 'atualizado_por' => $modelo->atualizado_por,
                    'status' => $modelo->status,
                    'created_at' => $modelo->created_at,
                    'updated_at' => $modelo->updated_at,
                    'cliente' =>  [
                        'id' => $modelo->cliente?->id,
                        'nome_completo' => $modelo->cliente?->nome_completo,
                        'primeiro_nome' => $modelo->cliente?->primeiro_nome,
                        'ultimo_nome' => $modelo->cliente?->ultimo_nome,
                        'apelido' => $modelo->cliente?->apelido,
                        'cpf' => $modelo->cliente?->cpf,
                        'data_nascimento' => $modelo->cliente?->data_nascimento,
                        'rg' => $modelo->cliente?->rg,
                        'sexo' => $modelo->cliente?->sexo,
                        'estado_civil' => $modelo->cliente?->estado_civil,
                        'img' => $modelo->cliente?->img,
                        'email_pessoal' => $modelo->cliente?->email_pessoal,
                        'telefone_pessoal' => $modelo->cliente?->telefone_pessoal,
                        'celular_pessoal' => $modelo->cliente?->celular_pessoal,
                        'whatsapp_pessoal' => $modelo->cliente?->whatsapp_pessoal,
                        'instagram_pessoal' => $modelo->cliente?->instagram_pessoal,
                        'facebook_pessoal' => $modelo->cliente?->facebook_pessoal,
                        // 'criado_por' => $modelo->cliente?->criado_por,
                        // 'atualizado_por' => $modelo->cliente?->atualizado_por,
                        'status' => $modelo->cliente?->status,
                        'created_at' => $modelo->cliente?->created_at,
                        'updated_at' => $modelo->cliente?->updated_at,
                    ],
                ];
            }),
        ];
    }
}
