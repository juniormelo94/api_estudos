<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\Combos;
use App\Models\CombosProdutos;
use App\Models\Estoques;

class CombosRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\Combos $model
     * @return void
     */
    public function __construct(protected Combos $model)
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

        if ($request->has('criado_de') && $request->has('criado_ate')) {
            $query->whereDate('created_at', '>=', $request->criado_de)
                  ->whereDate('created_at', '<=', $request->criado_ate);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('instalacoes_id')) {
            $query->where('instalacoes_id', $request->instalacoes_id);
        }

        if ($request->has('pesquisar')) {
            $query->where(function ($query) use ($request) {
                    $query->where('id', 'like', "%$request->pesquisar%")
                          ->orWhere('nome', 'like', "%$request->pesquisar%")
                          ->orWhere('tipo', 'like', "%$request->pesquisar%")
                          ->orWhere('descricao', 'like', "%$request->pesquisar%")
                          ->orWhere('codigo_barras', 'like', "%$request->pesquisar%")
                          ->orWhere('qr_code', 'like', "%$request->pesquisar%");
                });
        }

        if ($request->has('leitor')) {
            $query->where(function ($query) use ($request) {
                    $query->where('codigo_barras', 'like', "%$request->leitor%")
                          ->orWhere('qr_code', 'like', "%$request->leitor%");
                });
        }

        $combos;
        if ($request->has('por_pagina')) {
            $combos = $query->with(['combo_produtos.produto:id,nome,tipo,descricao,codigo_barras,qr_code,marcas_id,status,created_at,updated_at'])
                            ->paginate($request->por_pagina);
        }
        else
        {
            $combos = $query->with(['combo_produtos.produto:id,nome,tipo,descricao,codigo_barras,qr_code,marcas_id,status,created_at,updated_at'])
                            ->get();
        }
  
        // Verificar se é paginado
        $paginado = $combos instanceof \Illuminate\Pagination\LengthAwarePaginator;

        // Se for paginado pegar somente a coleção para modificar
        $colecao = $paginado ? $combos->getCollection() : $combos;

        $colecaoModificada = $colecao->map(function ($combo) {
            $estoquesIds = [];

            $produtosModificados = $combo->combo_produtos->map(function ($combo_produto) use ($combo, &$estoquesIds) {
                // Clonar a instância do produto para evitar referência compartilhada
                $produtoClonado = clone $combo_produto->produto;

                // Buscar um estoque único para este produto
                $estoque = Estoques::where('produtos_id', $produtoClonado->id)
                                    ->where('instalacoes_id', $combo->instalacoes_id)
                                    ->where('vendido', false)
                                    ->whereNotIn('id', $estoquesIds)
                                    ->orderBy('vencimento')
                                    ->first();

                if ($estoque) {
                    // Salvar ids dos estoques utilizados para que não sejam utilizados novamente
                    $estoquesIds[] = $estoque->id;
                    // Setar estoque no produto
                    $produtoClonado->setRelation('estoque', $estoque);
                } else {
                    // Setar estoque nulo para o produto
                    $produtoClonado->setRelation('estoque', null);
                }

                // Substituir a instância original do produto pela clonada
                $combo_produto->setRelation('produto', $produtoClonado);

                return $combo_produto;
            });

            // Substituir os produtos antigos pelos modificados no modelo $combo
            $combo->setRelation('combo_produtos', $produtosModificados);

            return $combo;
        });

        if ($paginado) {
            // Se for paginado devolver coleção para paginação
            $combos->setCollection($colecaoModificada);
            return $combos;
        }

        return $colecaoModificada;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function create($request)
    {
        $combo = $this->model;

        return tap($combo, function ($combo) use ($request) {
            $combo->nome = strtoupper($request->nome);
            $combo->tipo = strtoupper($request->tipo);
            $combo->descricao = $request->descricao;
            $combo->codigo_barras = $request->codigo_barras;
            $combo->qr_code = $request->qr_code;
            $combo->img_1 = $request->img_1;
            $combo->img_2 = $request->img_2;
            $combo->img_3 = $request->img_3;
            $combo->instalacoes_id = $request->instalacoes_id;
            $combo->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $combo->status = $request->status;
            $combo->save();

            // Preparar dados para inserir na tabela CombosProdutos
            $combosProdutos = collect($request->produtos_ids)->map(function ($produto_id) use ($combo) {
                return [
                    'combos_id' => $combo->id,
                    'produtos_id' => $produto_id,
                    'status' => 'Ativo',
                ];
            });

            // Criando linhas na tabela CombosProdutos
            CombosProdutos::insert($combosProdutos->toArray());

            // Buscando combo e os itens do combo
            $combo->load('combo_produtos.produto');

            $estoquesIds = [];

            $produtosModificados = $combo->combo_produtos->map(function ($combo_produto) use ($combo, &$estoquesIds) {
                // Clonar a instância do produto para evitar referência compartilhada
                $produtoClonado = clone $combo_produto->produto;

                // Buscar um estoque único para este produto
                $estoque = Estoques::where('produtos_id', $produtoClonado->id)
                                    ->where('instalacoes_id', $combo->instalacoes_id)
                                    ->where('vendido', false)
                                    ->whereNotIn('id', $estoquesIds)
                                    ->orderBy('vencimento')
                                    ->first();

                if ($estoque) {
                    // Salvar ids dos estoques utilizados para que não sejam utilizados novamente
                    $estoquesIds[] = $estoque->id;
                    // Setar estoque no produto
                    $produtoClonado->setRelation('estoque', $estoque);
                }
                else
                {
                    // Setar estoque nulo para o produto
                    $produtoClonado->setRelation('estoque', null);
                }

                // Substituir a instância original do produto pela clonada
                $combo_produto->setRelation('produto', $produtoClonado);

                return $combo_produto;
            });

            // Substituir os produtos antigos pelos modificados no modelo $combo
            $combo->setRelation('combo_produtos', $produtosModificados);

            $combo->save();
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
        $combo = $this->model->with(['combo_produtos.produto'])
                             ->findOrFail($id);

        $estoquesIds = [];

        $produtosModificados = $combo->combo_produtos->map(function ($combo_produto) use ($combo, &$estoquesIds) {
            // Clonar a instância do produto para evitar referência compartilhada
            $produtoClonado = clone $combo_produto->produto;

            // Buscar um estoque único para este produto
            $estoque = Estoques::where('produtos_id', $produtoClonado->id)
                                ->where('instalacoes_id', $combo->instalacoes_id)
                                ->where('vendido', false)
                                ->whereNotIn('id', $estoquesIds)
                                ->orderBy('vencimento')
                                ->first();

            if ($estoque) {
                // Salvar ids dos estoques utilizados para que não sejam utilizados novamente
                $estoquesIds[] = $estoque->id;
                // Setar estoque no produto
                $produtoClonado->setRelation('estoque', $estoque);
            }
            else
            {
                // Setar estoque nulo para o produto
                $produtoClonado->setRelation('estoque', null);
            }

            // Substituir a instância original do produto pela clonada
            $combo_produto->setRelation('produto', $produtoClonado);

            return $combo_produto;
        });

        // Substituir os produtos antigos pelos modificados no modelo $combo
        $combo->setRelation('combo_produtos', $produtosModificados);

        return $combo;
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
        $combo = $this->model->findOrFail($id);

        return tap($combo, function ($combo) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            // Atualizando dados da combo
            $combo->update($request->except(['criado_por']));

            // Deletar todos os itens do combo
            CombosProdutos::where('combos_id', $combo->id)->delete();

            // Preparar dados para inserir na tabela CombosProdutos
            $combosProdutos = collect($request->produtos_ids)->map(function ($produto_id) use ($combo) {
                return [
                    'combos_id' => $combo->id,
                    'produtos_id' => $produto_id,
                    'status' => 'Ativo',
                ];
            });

            // Criando linhas na tabela CombosProdutos
            CombosProdutos::insert($combosProdutos->toArray());

            // Buscando combo e os itens do combo
            $combo->load('combo_produtos.produto');

            $estoquesIds = [];

            $produtosModificados = $combo->combo_produtos->map(function ($combo_produto) use ($combo, &$estoquesIds) {
                // Clonar a instância do produto para evitar referência compartilhada
                $produtoClonado = clone $combo_produto->produto;

                // Buscar um estoque único para este produto
                $estoque = Estoques::where('produtos_id', $produtoClonado->id)
                                    ->where('instalacoes_id', $combo->instalacoes_id)
                                    ->where('vendido', false)
                                    ->whereNotIn('id', $estoquesIds)
                                    ->orderBy('vencimento')
                                    ->first();

                if ($estoque) {
                    // Salvar ids dos estoques utilizados para que não sejam utilizados novamente
                    $estoquesIds[] = $estoque->id;
                    // Setar estoque no produto
                    $produtoClonado->setRelation('estoque', $estoque);
                }
                else
                {
                    // Setar estoque nulo para o produto
                    $produtoClonado->setRelation('estoque', null);
                }

                // Substituir a instância original do produto pela clonada
                $combo_produto->setRelation('produto', $produtoClonado);

                return $combo_produto;
            });

            // Substituir os produtos antigos pelos modificados no modelo $combo
            $combo->setRelation('combo_produtos', $produtosModificados);

            $combo->save();
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
        $combo = $this->model->findOrFail($id);

        return $combo->delete();
    }
}
