<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TiposUsersResource extends JsonResource
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
                'tipo' => $this->tipo,
                'descricao' => $this->descricao,
                // 'criado_por' => $this->criado_por,
                // 'atualizado_por' => $this->atualizado_por,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'tipos_users_permissoes' => $this->tipos_users_permissoes->map(function ($tipo_user_permissao) {
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
            ],
        ];
    }
}
