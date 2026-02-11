<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class LogsCollection extends ResourceCollection
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
                    'mensagem_erro' => $modelo->mensagem_erro,
                    'codigo_erro' => $modelo->codigo_erro,
                    'arquivo_erro' => $modelo->arquivo_erro,
                    'linha_erro' => $modelo->linha_erro,
                    'rastreamento_erro' => $modelo->rastreamento_erro,
                    // 'criado_por' => $modelo->criado_por,
                    'created_at' => $modelo->created_at,
                    'updated_at' => $modelo->updated_at,
                ];
            }),
        ]; 
    }
}
