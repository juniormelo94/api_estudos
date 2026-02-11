<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
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
            'token_type' => 'Bearer',
            'token' => $this->createToken('auth-token')->plainTextToken,
            'data' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'email_verified_at' => $this->email_verified_at,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'colaborador_user' =>  [
                    'id' => $this->colaborador_user?->id,
                    'cargo' => $this->colaborador_user?->cargo,
                    'divisoes_ids' => $this->colaborador_user?->divisoes_ids,
                    'instalacoes_ids' => $this->colaborador_user?->instalacoes_ids,
                    'colaboradores_id' => $this->colaborador_user?->colaboradores_id,
                    'users_id' => $this->colaborador_user?->users_id,
                    'tipos_users_id' => $this->colaborador_user?->tipos_users_id,
                    // 'criado_por' => $this->colaborador_user?->criado_por,
                    // 'atualizado_por' => $this->colaborador_user?->atualizado_por,
                    'status' => $this->colaborador_user?->status,
                ],
            ],
        ];
    }
}
