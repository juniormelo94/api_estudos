<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\MaquinasCartao;
use App\Models\Estoques;
use App\Models\FormasPagamentos;
use App\Models\Vendas;
use App\Models\VendasEstoques;

class VendasRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\Vendas $model
     * @return void
     */
    public function __construct(protected Vendas $model)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function getAll()
    {
        $request = request();

        $query = $this->model->query();

        if ($request->has('criado_por')) {
            $query->where('criado_por', $request->criado_por);
        }

        if ($request->has('instalacoes_id')) {
            $query->where('instalacoes_id', $request->instalacoes_id);
        }

        if ($request->has('formas_pagamentos_id')) {
            $query->where('formas_pagamentos_id', $request->formas_pagamentos_id);
        }

        if ($request->has('clientes_id')) {
            $query->where('clientes_id', $request->clientes_id);
        }

        if ($request->has('colaboradores_id')) {
            $query->where('colaboradores_id', $request->colaboradores_id);
        }
 
        if ($request->has('criado_de') && $request->has('criado_ate')) {
            $query->whereDate('created_at', '>=', $request->criado_de)
                  ->whereDate('created_at', '<=', $request->criado_ate);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('pesquisar')) {
            $query->where('id', 'like', "%$request->pesquisar%");
        }

        if ($request->has('por_pagina')) {
            return $query->with([
                            'cliente', 
                            'colaborador', 
                            'forma_pagamento', 
                            'vendas_estoques.estoque.produto:id,nome,tipo,descricao,codigo_barras,qr_code,marcas_id,status,created_at,updated_at',
                            'vendas_estoques.estoque.produto.marca:id,nome,ramo,cnpj,status,created_at,updated_at'
                        ])
                         ->orderBy('created_at', 'desc')
                         ->paginate($request->por_pagina);
        }

        return $query->with([
                        'cliente', 
                        'colaborador', 
                        'forma_pagamento', 
                        'vendas_estoques.estoque.produto:id,nome,tipo,descricao,codigo_barras,qr_code,marcas_id,status,created_at,updated_at',
                        'vendas_estoques.estoque.produto.marca:id,nome,ramo,cnpj,status,created_at,updated_at'
                     ])
                     ->orderBy('created_at', 'desc')
                     ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function create($request)
    {
        $venda = $this->model;

        return tap($venda, function ($venda) use ($request) {
            $venda->preco_total = 0;
            $venda->lucro_total_original = 0;
            $venda->lucro_total_desconto = 0;
            $venda->maquina_cartao = $request->maquina_cartao;
            $venda->quantidade_parcelas = $request->quantidade_parcelas;
            $venda->valor_pacelas = $request->valor_pacelas;
            $venda->taxa_juros = $request->taxa_juros;
            $venda->formas_pagamentos_id = $request->formas_pagamentos_id;
            $venda->clientes_id = $request->clientes_id;
            $venda->colaboradores_id = $request->colaboradores_id;
            $venda->instalacoes_id = $request->instalacoes_id;
            $venda->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $venda->status = $request->status;
            $venda->save();

            // Preparar dados para inserir na tabela VendasEstoques
            $vendasEstoques = collect($request->estoques_ids)->map(function ($estoque_id) use ($venda) {
                return [
                    'vendas_id' => $venda->id,
                    'estoques_id' => $estoque_id,
                    'status' => 'Ativo',
                ];
            });

            // Criando linhas na tabela VendasEstoques
            VendasEstoques::insert($vendasEstoques->toArray());

            // Atualizando itens como vendidos no Estoques
            Estoques::whereIn('id', $request->estoques_ids)->update(['vendido' => true]);

            // Pegar id´s das formas de pagamentos que o tipo de pagamento seja à vista
            $formas_pagamentos_avista_ids = FormasPagamentos::where('tipo_pagamento', 'À Vista')->pluck('id');

            // Buscando venda e os itens da venda
            $venda->load('vendas_estoques.estoque');

            collect($venda->vendas_estoques)->map(function ($venda_estoque) use ($venda, &$formas_pagamentos_avista_ids) {
                $estoque = $venda_estoque->estoque;
                $preco_venda = 0;

                if(in_array($venda->formas_pagamentos_id, $formas_pagamentos_avista_ids->toArray()) 
                && ($estoque->preco_venda_avista != null || $estoque->preco_venda_avista > 0))
                {
                    // Calcular preço total da venda com item vendido com desconto à vista
                    $venda->preco_total += $estoque->preco_venda_avista;
                    // Pegando preço da venda com item vendido com desconto à vista
                    $preco_venda = $estoque->preco_venda_avista;
                }
                else if($estoque->preco_venda_desconto != null || $estoque->preco_venda_desconto > 0)
                {
                    // Calcular preço total da venda com item vendido com desconto
                    $venda->preco_total += $estoque->preco_venda_desconto;
                    // Pegando preço da venda com item vendido com desconto
                    $preco_venda = $estoque->preco_venda_desconto;
                }
                else
                {
                    // Calcular preço total da venda com item vendido sem desconto
                    $venda->preco_total += $estoque->preco_venda_original;
                    // Pegando preço da venda com item vendido sem desconto
                    $preco_venda = $estoque->preco_venda_original;
                }

                // Calcular lucro total
                if($estoque->preco_compra_desconto !== null && $estoque->preco_compra_desconto == 0)
                {
                    // Estoque recebido e graça pois o valor de compra é zero

                    if(in_array($venda->formas_pagamentos_id, $formas_pagamentos_avista_ids->toArray()) 
                    && ($estoque->preco_venda_avista != null || $estoque->preco_venda_avista > 0))
                    {
                       // Adicionar o valor de venda com desconto à vista ao lucro total da venda
                        $venda->lucro_total_original += $estoque->preco_venda_desconto;
                    }
                    else if($estoque->preco_venda_desconto != null || $estoque->preco_venda_desconto > 0)
                    {
                        // Adicionar o valor de venda com desconto ao lucro total da venda
                        $venda->lucro_total_original += $estoque->preco_venda_desconto;
                    }
                    else
                    {
                        // Adicionar o valor de venda sem desconto ao lucro total da venda
                        $venda->lucro_total_original += $estoque->preco_venda_original;
                    }
                }
                else if($estoque->preco_compra_desconto != null && $estoque->preco_compra_desconto > 0)
                {
                    // Calcular lucro total da venda com item vendido com desconto
                    $venda->lucro_total_original += $preco_venda - $estoque->preco_compra_desconto;
                }
                else
                {
                    // Calcular lucro total da venda com item vendido sem desconto
                    $venda->lucro_total_original += $preco_venda - $estoque->preco_compra_original;
                }
            });

            if($request->taxa_juros != null && $request->taxa_juros > 0)
            {
                // Descontar taxa da maquina do lucro total
                $desconto = $venda->preco_total * ($request->taxa_juros / 100);
                $venda->lucro_total_desconto = $venda->lucro_total_original - $desconto;
            }

            if($request->quantidade_parcelas != null || $request->quantidade_parcelas > 0)
            {
                // Calcular valor da(s) pacela(s)
                $venda->valor_pacelas = $venda->preco_total / $request->quantidade_parcelas;
            }

            $venda->save();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return array
     */
    public function getById($id)
    {
        return $this->model->with(['cliente', 'colaborador', 'forma_pagamento', 'vendas_estoques.estoque'])
                           ->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array
     */
    public function update($request, $id)
    {
        $venda = $this->model->findOrFail($id);

        return tap($venda, function ($venda) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            // Atualizando dados da venda
            $venda->update($request->except(['criado_por']));

            // Zerar alguns valores antes de calcular
            $venda->preco_total = 0;
            $venda->lucro_total_original = 0;
            $venda->lucro_total_desconto = 0;

            // Pegar id´s dos itens da venda
            $estoques_ids = VendasEstoques::where('vendas_id', $venda->id)->pluck('estoques_id');

            // Deletar todos os itens da venda
            VendasEstoques::where('vendas_id', $venda->id)->delete();

            // Atualizando itens como não vendidos no Estoques
            Estoques::whereIn('id', $estoques_ids)->update(['vendido' => false]);

            // Preparar dados para inserir na tabela VendasEstoques
            $vendasEstoques = collect($request->estoques_ids)->map(function ($estoque_id) use ($venda) {
                return [
                    'vendas_id' => $venda->id,
                    'estoques_id' => $estoque_id,
                    'status' => 'Ativo',
                ];
            });

            // Criando linhas na tabela VendasEstoques
            VendasEstoques::insert($vendasEstoques->toArray());

            // Atualizando itens como vendidos no Estoques
            Estoques::whereIn('id', $request->estoques_ids)->update(['vendido' => true]);

            // Pegar id´s das formas de pagamentos que o tipo de pagamento seja à vista
            $formas_pagamentos_avista_ids = FormasPagamentos::where('tipo_pagamento', 'À Vista')->pluck('id');

            // Buscando venda e os itens da venda
            $venda->load('vendas_estoques.estoque');

            collect($venda->vendas_estoques)->map(function ($venda_estoque) use ($venda, &$formas_pagamentos_avista_ids) {
                $estoque = $venda_estoque->estoque;
                $preco_venda = 0;

                if(in_array($venda->formas_pagamentos_id, $formas_pagamentos_avista_ids->toArray()) 
                && ($estoque->preco_venda_avista != null || $estoque->preco_venda_avista > 0))
                {
                    // Calcular preço total da venda com item vendido com desconto à vista
                    $venda->preco_total += $estoque->preco_venda_avista;
                    // Pegando preço da venda com item vendido com desconto à vista
                    $preco_venda = $estoque->preco_venda_avista;
                }
                else if($estoque->preco_venda_desconto != null || $estoque->preco_venda_desconto > 0)
                {
                    // Calcular preço total da venda com item vendido com desconto
                    $venda->preco_total += $estoque->preco_venda_desconto;
                    // Pegando preço da venda com item vendido com desconto
                    $preco_venda = $estoque->preco_venda_desconto;
                }
                else
                {
                    // Calcular preço total da venda com item vendido sem desconto
                    $venda->preco_total += $estoque->preco_venda_original;
                    // Pegando preço da venda com item vendido sem desconto
                    $preco_venda = $estoque->preco_venda_original;
                }

                // Calcular lucro total
                if($estoque->preco_compra_desconto !== null && $estoque->preco_compra_desconto == 0)
                {
                    // Estoque recebido e graça pois o valor de compra é zero

                    if(in_array($venda->formas_pagamentos_id, $formas_pagamentos_avista_ids->toArray()) 
                    && ($estoque->preco_venda_avista != null || $estoque->preco_venda_avista > 0))
                    {
                       // Adicionar o valor de venda com desconto à vista ao lucro total da venda
                        $venda->lucro_total_original += $estoque->preco_venda_desconto;
                    }
                    else if($estoque->preco_venda_desconto != null || $estoque->preco_venda_desconto > 0)
                    {
                        // Adicionar o valor de venda com desconto ao lucro total da venda
                        $venda->lucro_total_original += $estoque->preco_venda_desconto;
                    }
                    else
                    {
                        // Adicionar o valor de venda sem desconto ao lucro total da venda
                        $venda->lucro_total_original += $estoque->preco_venda_original;
                    }
                }
                else if($estoque->preco_compra_desconto != null && $estoque->preco_compra_desconto > 0)
                {
                    // Calcular lucro total da venda com item vendido com desconto
                    $venda->lucro_total_original += $preco_venda - $estoque->preco_compra_desconto;
                }
                else
                {
                    // Calcular lucro total da venda com item vendido sem desconto
                    $venda->lucro_total_original += $preco_venda - $estoque->preco_compra_original;
                }
            });

            if($request->taxa_juros != null && $request->taxa_juros > 0)
            {
                // Descontar taxa da maquina do lucro total
                $desconto = $venda->preco_total * ($request->taxa_juros / 100);
                $venda->lucro_total_desconto = $venda->lucro_total_original - $desconto;
            }

            if($request->quantidade_parcelas != null || $request->quantidade_parcelas > 0)
            {
                // Calcular valor da(s) pacela(s)
                $venda->valor_pacelas = $venda->preco_total / $request->quantidade_parcelas;
            }

            $venda->save();
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $venda = $this->model->findOrFail($id);

        // Pegar id´s dos itens antigos
        $estoques_ids = VendasEstoques::where('vendas_id', $id)->pluck('estoques_id');  

        // Atualizar disponibilidade dos itens no estoque
        Estoques::whereIn('id', $estoques_ids)->update(['vendido' => false]);

        return $venda->delete();
    }
}
