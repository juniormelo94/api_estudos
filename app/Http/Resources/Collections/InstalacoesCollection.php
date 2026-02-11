<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InstalacoesCollection extends ResourceCollection
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
                    'endereco' => $modelo->endereco,
                    'bairro' => $modelo->bairro,
                    'numero' => $modelo->numero,
                    'complemento' => $modelo->complemento,
                    'cep' => $modelo->cep,
                    'cidade' => $modelo->cidade,
                    'uf' => $modelo->uf,
                    'email' => $modelo->email,
                    'telefone' => $modelo->telefone,
                    'celular' => $modelo->celular,
                    'whatsapp' => $modelo->whatsapp,
                    'instagram' => $modelo->instagram,
                    'facebook' => $modelo->facebook,
                    'divisoes_id' => $modelo->divisoes_id,
                    // 'criado_por' => $modelo->criado_por,
                    // 'atualizado_por' => $modelo->atualizado_por,
                    'status' => $modelo->status,
                    'created_at' => $modelo->created_at,
                    'updated_at' => $modelo->updated_at,
                ];
            }),
        ];    
    }
}
