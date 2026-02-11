<?php

namespace App\Http\Resources\Collections;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VendasCollection extends ResourceCollection
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
                    'preco_total' => $modelo->preco_total,
                    'lucro_total_original' => $modelo->lucro_total_original,
                    'lucro_total_desconto' => $modelo->lucro_total_desconto,
                    'maquina_cartao' => $modelo->maquina_cartao,
                    'quantidade_parcelas' => $modelo->quantidade_parcelas,
                    'valor_pacelas' => $modelo->valor_pacelas,
                    'taxa_juros' => $modelo->taxa_juros,
                    'formas_pagamentos_id' => $modelo->formas_pagamentos_id,
                    'clientes_id' => $modelo->clientes_id,
                    'colaboradores_id' => $modelo->colaboradores_id,
                    'instalacoes_id' => $modelo->instalacoes_id,
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
                    'colaborador' =>  [
                        'id' => $modelo->colaborador?->id,
                        'nome_completo' => $modelo->colaborador?->nome_completo,
                        'primeiro_nome' => $modelo->colaborador?->primeiro_nome,
                        'ultimo_nome' => $modelo->colaborador?->ultimo_nome,
                        'apelido' => $modelo->colaborador?->apelido,
                        'cpf' => $modelo->colaborador?->cpf,
                        'data_nascimento' => $modelo->colaborador?->data_nascimento,
                        'rg' => $modelo->colaborador?->rg,
                        'sexo' => $modelo->colaborador?->sexo,
                        'estado_civil' => $modelo->colaborador?->estado_civil,
                        'img' => $modelo->colaborador?->img,
                        'email_pessoal' => $modelo->colaborador?->email_pessoal,
                        'telefone_pessoal' => $modelo->colaborador?->telefone_pessoal,
                        'celular_pessoal' => $modelo->colaborador?->celular_pessoal,
                        'whatsapp_pessoal' => $modelo->colaborador?->whatsapp_pessoal,
                        'instagram_pessoal' => $modelo->colaborador?->instagram_pessoal,
                        'facebook_pessoal' => $modelo->colaborador?->facebook_pessoal,
                        // 'criado_por' => $modelo->colaborador?->criado_por,
                        // 'atualizado_por' => $modelo->colaborador?->atualizado_por,
                        'status' => $modelo->colaborador?->status,
                        'created_at' => $modelo->colaborador?->created_at,
                        'updated_at' => $modelo->colaborador?->updated_at,
                    ],
                    'forma_pagamento' =>  [
                        'id' => $modelo->forma_pagamento?->id,
                        'nome' => $modelo->forma_pagamento?->nome,
                        'tipo_pagamento' => $modelo->forma_pagamento?->tipo_pagamento,
                        // 'criado_por' => $modelo->forma_pagamento?->criado_por,
                        // 'atualizado_por' => $modelo->forma_pagamento?->atualizado_por,
                        'status' => $modelo->forma_pagamento?->status,
                        'created_at' => $modelo->forma_pagamento?->created_at,
                        'updated_at' => $modelo->forma_pagamento?->updated_at,
                    ],
                    'vendas_estoques' => $modelo->vendas_estoques->map(function ($venda_estoque) {
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
                                    'marca' => [
                                        'id' => $venda_estoque->estoque->produto?->marca?->id,
                                        'nome' => $venda_estoque->estoque->produto?->marca?->nome,
                                        'ramo' => $venda_estoque->estoque->produto?->marca?->ramo,
                                        'cnpj' => $venda_estoque->estoque->produto?->marca?->cnpj,
                                        // 'logo_img' => $venda_estoque->estoque->produto?->marca?->logo_img,
                                        // 'criado_por' => $venda_estoque->estoque->produto?->marca?->criado_por,
                                        // 'atualizado_por' => $venda_estoque->estoque->produto?->marca?->atualizado_por,
                                        'status' => $venda_estoque->estoque->produto?->marca?->status,
                                        'created_at' => $venda_estoque->estoque->produto?->marca?->created_at,
                                        'updated_at' => $venda_estoque->estoque->produto?->marca?->updated_at,
                                    ],
                                ],
                            ],
                        ];
                    }),
                ];
            }),
        ]; 
    }
}
