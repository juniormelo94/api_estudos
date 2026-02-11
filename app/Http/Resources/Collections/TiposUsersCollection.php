<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TiposUsersCollection extends ResourceCollection
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
                    'tipo' => $modelo->tipo,
                    'descricao' => $modelo->descricao,
                    // 'criado_por' => $modelo->criado_por,
                    // 'atualizado_por' => $modelo->atualizado_por,
                    'status' => $modelo->status,
                    'created_at' => $modelo->created_at,
                    'updated_at' => $modelo->updated_at,
                    'tipos_users_permissoes' => $modelo->tipos_users_permissoes->map(function ($tipo_user_permissao) {
                        return [
                            'id' => $tipo_user_permissao->id,
                            'tipos_users_id' => $tipo_user_permissao->tipos_users_id,
                            'permissoes_id' => $tipo_user_permissao->permissoes_id,
                            'created_at' => $tipo_user_permissao->created_at,
                            'updated_at' => $tipo_user_permissao->updated_at,
                            'permissao' =>  [
                                'id' => $tipo_user_permissao->permissao->id,
                                'chave' => $tipo_user_permissao->permissao->chave,
                                'grupo' => $tipo_user_permissao->permissao->grupo,
                                'descricao' => $tipo_user_permissao->permissao->descricao,
                                // 'criado_por' => $tipo_user_permissao->permissao->criado_por,
                                // 'atualizado_por' => $tipo_user_permissao->permissao->atualizado_por,
                                'status' => $tipo_user_permissao->permissao->status,
                                'created_at' => $tipo_user_permissao->permissao->created_at,
                                'updated_at' => $tipo_user_permissao->permissao->updated_at,
                            ],
                        ];
                    }),
                ];
            }),
        ];
    }
}
