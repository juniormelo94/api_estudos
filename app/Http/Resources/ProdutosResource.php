<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProdutosResource extends JsonResource
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
                'nome' => $this->nome,
                'tipo' => $this->tipo,
                'descricao' => $this->descricao,
                'codigo_barras' => $this->codigo_barras,
                'qr_code' => $this->qr_code,
                'img_1' => $this->img_1,
                'img_2' => $this->img_2,
                'img_3' => $this->img_3,
                'marcas_id' => $this->marcas_id,
                // 'criado_por' => $this->criado_por,
                // 'atualizado_por' => $this->atualizado_por,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'divulgacao_existe' => $this->divulgacao_existe,
                'instalacao_produtos' => $this->instalacao_produtos,
            ],
        ];
    }
}
