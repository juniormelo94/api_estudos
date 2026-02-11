<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
                    'name' => $modelo->name,
                    'email' => $modelo->email,
                    'email_verified_at' => $modelo->email_verified_at,
                    'created_at' => $modelo->created_at,
                    'updated_at' => $modelo->updated_at,
                    'colaborador_user' =>  [
                        'id' => $modelo->colaborador_user?->id,
                        'cargo' => $modelo->colaborador_user?->cargo,
                        'divisoes_ids' => $modelo->colaborador_user?->divisoes_ids,
                        'instalacoes_ids' => $modelo->colaborador_user?->instalacoes_ids,
                        'colaboradores_id' => $modelo->colaborador_user?->colaboradores_id,
                        'users_id' => $modelo->colaborador_user?->users_id,
                        'tipos_users_id' => $modelo->colaborador_user?->tipos_users_id,
                        // 'criado_por' => $modelo->colaborador_user?->criado_por,
                        // 'atualizado_por' => $modelo->colaborador_user?->atualizado_por,
                        'status' => $modelo->colaborador_user?->status,
                    ],
                ];
            }),
        ];
    }
}
