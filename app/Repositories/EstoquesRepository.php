<?php

namespace App\Repositories;

use App\Models\Estoques;
use App\Interfaces\RepositoryInterface;

class EstoquesRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\Estoques $model
     * @return void
     */
    public function __construct(protected Estoques $model)
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
            $query->where('estoques.criado_por', $request->criado_por);
        }

        if ($request->has('instalacoes_id')) {
            $query->where('estoques.instalacoes_id', $request->instalacoes_id);
        }

        if ($request->has('produtos_id')) {
            $query->where('estoques.produtos_id', $request->produtos_id);
        }

        if ($request->has('criado_de') && $request->has('criado_ate')) {
            $query->whereDate('estoques.created_at', '>=', $request->criado_de)
                  ->whereDate('estoques.created_at', '<=', $request->criado_ate);
        }

        if ($request->has('status')) {
            $query->where('estoques.status', $request->status);
        }

        if ($request->has('vendido')) {
            $query->where('estoques.vendido', $request->vendido);
        }

        if ($request->has('vencimento_de') && $request->has('vencimento_ate')) {
            $query->whereDate('estoques.vencimento', '>=', $request->vencimento_de)
                  ->whereDate('estoques.vencimento', '<=', $request->vencimento_ate);
        }
    
        if ($request->has('preco_venda_avista')) {
            $query->whereNotNull('estoques.preco_venda_avista');
        }

        if ($request->has('marcas_id')) {
            $query->whereHas('produto', function ($subQuery) use ($request) {
                $subQuery->where('marcas_id', $request->marcas_id);
            });
        }

        if ($request->has('pesquisar')) {
            $query->whereHas('produto', function ($subQuery) use ($request) {
                $subQuery->where('id', 'like', "%$request->pesquisar%")
                         ->orWhere('nome', 'like', "%$request->pesquisar%")
                         ->orWhere('tipo', 'like', "%$request->pesquisar%")
                         ->orWhere('descricao', 'like', "%$request->pesquisar%")
                         ->orWhere('codigo_barras', 'like', "%$request->pesquisar%")
                         ->orWhere('qr_code', 'like', "%$request->pesquisar%");
            });
        }

        if ($request->has('leitor')) {
            $query->whereHas('produto', function ($subQuery) use ($request) {
                $subQuery->where('codigo_barras', 'like', "%$request->leitor%")
                         ->orWhere('qr_code', 'like', "%$request->leitor%");
            });
        }

        if ($request->has('ordenar_coluna')) {
            $ordenarOrdem = 'asc';
            if ($request->has('ordenar_ordem') && in_array(strtolower($request->ordenar_ordem), ['asc', 'desc'])) {
                $ordenarOrdem = $request->ordenar_ordem;
            }
            
            $ordenarColuna = $request->ordenar_coluna;
            $query->orderBy($ordenarColuna, $ordenarOrdem);
        }
        else{
            $query->select('estoques.*', 'produtos.nome as produto_nome')
                  ->join('produtos', 'estoques.produtos_id', '=', 'produtos.id')
                  ->orderBy('produto_nome');
        }

        if ($request->has('por_pagina')) {
            return $query->with('produto')
                         ->paginate($request->por_pagina);
        }

        return $query->with('produto')
                     ->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function getAllSolds()
    {
        $request = request();

        $query = $this->model->query();

        // Filtros da venda

        if ($request->has('criado_por')) {
            $query->whereHas('venda_estoque.venda', function ($subQuery) use ($request) {
                $subQuery->where('criado_por', $request->criado_por);
            });
        }

        if ($request->has('instalacoes_id')) {
            $query->whereHas('venda_estoque.venda', function ($subQuery) use ($request) {
                $subQuery->where('instalacoes_id', $request->instalacoes_id);
            });
        }

        if ($request->has('formas_pagamentos_id')) {
            $query->whereHas('venda_estoque.venda', function ($subQuery) use ($request) {
                $subQuery->where('formas_pagamentos_id', $request->formas_pagamentos_id);
            });
        }

        if ($request->has('clientes_id')) {
            $query->whereHas('venda_estoque.venda', function ($subQuery) use ($request) {
                $subQuery->where('clientes_id', $request->clientes_id);
            });
        }

        if ($request->has('colaboradores_id')) {
            $query->whereHas('venda_estoque.venda', function ($subQuery) use ($request) {
                $subQuery->where('colaboradores_id', $request->colaboradores_id);
            });
        }

        if ($request->has('criado_de') && $request->has('criado_ate')) {
            $query->whereHas('venda_estoque.venda', function ($subQuery) use ($request) {
                $subQuery->whereDate('created_at', '>=', $request->criado_de)
                         ->whereDate('created_at', '<=', $request->criado_ate);
            });
        }

        if ($request->has('status')) {
            $query->whereHas('venda_estoque.venda', function ($subQuery) use ($request) {
                $subQuery->where('status', $request->status);
            });
        }

        // Filtros do estoque

        if ($request->has('produtos_id')) {
            $query->where('estoques.produtos_id', $request->produtos_id);
        }

        if ($request->has('marcas_id')) {
            $query->whereHas('produto', function ($subQuery) use ($request) {
                $subQuery->where('marcas_id', $request->marcas_id);
            });
        }

        if ($request->has('pesquisar')) {
            $query->whereHas('produto', function ($subQuery) use ($request) {
                $subQuery->where('id', 'like', "%$request->pesquisar%")
                         ->orWhere('nome', 'like', "%$request->pesquisar%")
                         ->orWhere('tipo', 'like', "%$request->pesquisar%")
                         ->orWhere('descricao', 'like', "%$request->pesquisar%")
                         ->orWhere('codigo_barras', 'like', "%$request->pesquisar%")
                         ->orWhere('qr_code', 'like', "%$request->pesquisar%");
            });
        }

        if ($request->has('leitor')) {
            $query->whereHas('produto', function ($subQuery) use ($request) {
                $subQuery->where('codigo_barras', 'like', "%$request->leitor%")
                         ->orWhere('qr_code', 'like', "%$request->leitor%");
            });
        }

        if ($request->has('por_pagina')) {
            return $query->select('estoques.*', 'vendas.created_at as data_venda')
                         ->join('vendas_estoques', 'estoques.id', '=', 'vendas_estoques.estoques_id')
                         ->join('vendas', 'vendas_estoques.vendas_id', '=', 'vendas.id')
                         ->orderBy('data_venda', 'desc')
                         ->with(['produto', 'venda_estoque.venda'])
                         ->paginate($request->por_pagina);
        }

        return $query->select('estoques.*', 'vendas.created_at as data_venda')
                     ->join('vendas_estoques', 'estoques.id', '=', 'vendas_estoques.estoques_id')
                     ->join('vendas', 'vendas_estoques.vendas_id', '=', 'vendas.id')
                     ->orderBy('data_venda', 'desc')
                     ->with(['produto', 'venda_estoque.venda'])
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
        $estoque = $this->model;

        return tap($estoque, function ($estoque) use ($request) {
            $estoque->desconto_compra = $request->desconto_compra;
            $estoque->preco_compra_original = $request->preco_compra_original;
            $estoque->preco_compra_desconto = $request->preco_compra_desconto;
            $estoque->desconto_venda = $request->desconto_venda;
            $estoque->preco_venda_original = $request->preco_venda_original;
            $estoque->preco_venda_desconto = $request->preco_venda_desconto;
            $estoque->preco_venda_avista = $request->preco_venda_avista;
            $estoque->vendido = $request->vendido;
            $estoque->desconto_pagamento_avista = $request->desconto_pagamento_avista;
            $estoque->vencimento = $request->vencimento;
            $estoque->produtos_id = $request->produtos_id;
            $estoque->instalacoes_id = $request->instalacoes_id;
            $estoque->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $estoque->status = $request->status;
            $estoque->save();

            $estoque->load('produto');
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
        return $this->model->findOrFail($id);
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
        $estoque = $this->model->findOrFail($id);

        return tap($estoque, function ($estoque) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            $estoque->update($request->except(['criado_por']));
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
        $estoque = $this->model->findOrFail($id);

        return $estoque->delete();
    }
}
