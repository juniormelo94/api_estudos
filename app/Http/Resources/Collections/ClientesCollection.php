<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientesCollection extends ResourceCollection
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
                    'nome_completo' => $modelo->nome_completo,
                    'primeiro_nome' => $modelo->primeiro_nome,
                    'ultimo_nome' => $modelo->ultimo_nome,
                    'apelido' => $modelo->apelido,
                    'cpf' => $modelo->cpf,
                    'data_nascimento' => $modelo->data_nascimento,
                    'rg' => $modelo->rg,
                    'sexo' => $modelo->sexo,
                    'estado_civil' => $modelo->estado_civil,
                    'img' => $modelo->img,
                    'email_pessoal' => $modelo->email_pessoal,
                    'telefone_pessoal' => $modelo->telefone_pessoal,
                    'celular_pessoal' => $modelo->celular_pessoal,
                    'whatsapp_pessoal' => $modelo->whatsapp_pessoal,
                    'instagram_pessoal' => $modelo->instagram_pessoal,
                    'facebook_pessoal' => $modelo->facebook_pessoal,
                    // 'criado_por' => $modelo->criado_por,
                    // 'atualizado_por' => $modelo->atualizado_por,
                    'status' => $modelo->status,
                    'created_at' => $modelo->created_at,
                    'updated_at' => $modelo->updated_at,
                    'instalacao_clientes' => $modelo->instalacao_clientes,
                ];
            }),
        ]; 
    }
}
