<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VendasResource extends JsonResource
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
                'preco_total' => $this->preco_total,
                'lucro_total_original' => $this->lucro_total_original,
                'lucro_total_desconto' => $this->lucro_total_desconto,
                'maquina_cartao' => $this->maquina_cartao,
                'quantidade_parcelas' => $this->quantidade_parcelas,
                'valor_pacelas' => $this->valor_pacelas ? number_format($this->valor_pacelas, 2) : $this->valor_pacelas,
                'taxa_juros' => $this->taxa_juros,
                'formas_pagamentos_id' => $this->formas_pagamentos_id,
                'clientes_id' => $this->clientes_id,
                'colaboradores_id' => $this->colaboradores_id,
                'instalacoes_id' => $this->instalacoes_id,
                // 'criado_por' => $this->criado_por,
                // 'atualizado_por' => $this->atualizado_por,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'cliente' =>  [
                    'id' => $this->cliente?->id,
                    'nome_completo' => $this->cliente?->nome_completo,
                    'primeiro_nome' => $this->cliente?->primeiro_nome,
                    'ultimo_nome' => $this->cliente?->ultimo_nome,
                    'apelido' => $this->cliente?->apelido,
                    'cpf' => $this->cliente?->cpf,
                    'data_nascimento' => $this->cliente?->data_nascimento,
                    'rg' => $this->cliente?->rg,
                    'sexo' => $this->cliente?->sexo,
                    'estado_civil' => $this->cliente?->estado_civil,
                    'img' => $this->cliente?->img,
                    'email_pessoal' => $this->cliente?->email_pessoal,
                    'telefone_pessoal' => $this->cliente?->telefone_pessoal,
                    'celular_pessoal' => $this->cliente?->celular_pessoal,
                    'whatsapp_pessoal' => $this->cliente?->whatsapp_pessoal,
                    'instagram_pessoal' => $this->cliente?->instagram_pessoal,
                    'facebook_pessoal' => $this->cliente?->facebook_pessoal,
                    // 'criado_por' => $this->cliente?->criado_por,
                    // 'atualizado_por' => $this->cliente?->atualizado_por,
                    'status' => $this->cliente?->status,
                    'created_at' => $this->cliente?->created_at,
                    'updated_at' => $this->cliente?->updated_at,
                ],
                'colaborador' =>  [
                    'id' => $this->colaborador?->id,
                    'nome_completo' => $this->colaborador?->nome_completo,
                    'primeiro_nome' => $this->colaborador?->primeiro_nome,
                    'ultimo_nome' => $this->colaborador?->ultimo_nome,
                    'apelido' => $this->colaborador?->apelido,
                    'cpf' => $this->colaborador?->cpf,
                    'data_nascimento' => $this->colaborador?->data_nascimento,
                    'rg' => $this->colaborador?->rg,
                    'sexo' => $this->colaborador?->sexo,
                    'estado_civil' => $this->colaborador?->estado_civil,
                    'img' => $this->colaborador?->img,
                    'email_pessoal' => $this->colaborador?->email_pessoal,
                    'telefone_pessoal' => $this->colaborador?->telefone_pessoal,
                    'celular_pessoal' => $this->colaborador?->celular_pessoal,
                    'whatsapp_pessoal' => $this->colaborador?->whatsapp_pessoal,
                    'instagram_pessoal' => $this->colaborador?->instagram_pessoal,
                    'facebook_pessoal' => $this->colaborador?->facebook_pessoal,
                    // 'criado_por' => $this->colaborador?->criado_por,
                    // 'atualizado_por' => $this->colaborador?->atualizado_por,
                    'status' => $this->colaborador?->status,
                    'created_at' => $this->colaborador?->created_at,
                    'updated_at' => $this->colaborador?->updated_at,
                ],
                'forma_pagamento' =>  [
                    'id' => $this->forma_pagamento?->id,
                    'nome' => $this->forma_pagamento?->nome,
                    'tipo_pagamento' => $this->forma_pagamento?->tipo_pagamento,
                    // 'criado_por' => $this->forma_pagamento?->criado_por,
                    // 'atualizado_por' => $this->forma_pagamento?->atualizado_por,
                    'status' => $this->forma_pagamento?->status,
                    'created_at' => $this->forma_pagamento?->created_at,
                    'updated_at' => $this->forma_pagamento?->updated_at,
                ],
                'vendas_estoques' => $this->vendas_estoques->map(function ($venda_estoque) {
                    return [
                        'id' => $venda_estoque->id,
                        'vendas_id' => $venda_estoque->vendas_id,
                        'estoques_id' => $venda_estoque->estoques_id,
                        'status' => $venda_estoque->status,
                        'created_at' => $venda_estoque->created_at,
                        'updated_at' => $venda_estoque->updated_at,
                        'estoque' =>  [
                            'id' => $venda_estoque->estoque->id,
                            'desconto_compra' => $venda_estoque->estoque->desconto_compra,
                            'preco_compra_original' => $venda_estoque->estoque->preco_compra_original,
                            'preco_compra_desconto' => $venda_estoque->estoque->preco_compra_desconto,
                            'desconto_venda' => $venda_estoque->estoque->desconto_venda,
                            'preco_venda_original' => $venda_estoque->estoque->preco_venda_original,
                            'preco_venda_desconto' => $venda_estoque->estoque->preco_venda_desconto,
                            'preco_venda_avista' => $venda_estoque->estoque->preco_venda_avista,
                            'vendido' => $venda_estoque->estoque->vendido,
                            'desconto_pagamento_avista' => $venda_estoque->estoque->desconto_pagamento_avista,
                            'vencimento' => $venda_estoque->estoque->vencimento,
                            'produtos_id' => $venda_estoque->estoque->produtos_id,
                            'instalacoes_id' => $venda_estoque->estoque->instalacoes_id,
                            // 'criado_por' => $venda_estoque->estoque->criado_por,
                            // 'atualizado_por' => $venda_estoque->estoque->atualizado_por,
                            'status' => $venda_estoque->estoque->status,
                            'created_at' => $venda_estoque->estoque->created_at,
                            'updated_at' => $venda_estoque->estoque->updated_at,
                            'produto' =>  [
                                'id' => $venda_estoque->estoque->produto?->id,
                                'nome' => $venda_estoque->estoque->produto?->nome,
                                'tipo' => $venda_estoque->estoque->produto?->tipo,
                                'descricao' => $venda_estoque->estoque->produto?->descricao,
                                'codigo_barras' => $venda_estoque->estoque->produto?->codigo_barras,
                                'qr_code' => $venda_estoque->estoque->produto?->qr_code,
                                'img_1' => $venda_estoque->estoque->produto?->img_1,
                                'img_2' => $venda_estoque->estoque->produto?->img_2,
                                'img_3' => $venda_estoque->estoque->produto?->img_3,
                                'marcas_id' => $venda_estoque->estoque->produto?->marcas_id,
                                // 'criado_por' => $venda_estoque->estoque->produto?->criado_por,
                                // 'atualizado_por' => $venda_estoque->estoque->produto?->atualizado_por,
                                'status' => $venda_estoque->estoque->produto?->status,
                                'created_at' => $venda_estoque->estoque->produto?->created_at,
                                'updated_at' => $venda_estoque->estoque->produto?->updated_at,
                            ],
                        ],
                    ];
                }),
            ],
        ];
    }
}
