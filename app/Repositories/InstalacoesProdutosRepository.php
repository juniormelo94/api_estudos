<?php

namespace App\Repositories;

use App\Models\InstalacoesProdutos;
use App\Interfaces\RepositoryInterface;

use App\Models\Estoques;
use Illuminate\Support\Facades\DB;

class InstalacoesProdutosRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\InstalacoesProdutos $model
     * @return void
     */
    public function __construct(protected InstalacoesProdutos $model)
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
            $query->where('instalacoes_produtos.criado_por', $request->criado_por);
        }

        if ($request->has('instalacoes_id')) {
            $query->where('instalacoes_produtos.instalacoes_id', $request->instalacoes_id);
        }

        if ($request->has('criado_de') && $request->has('criado_ate')) {
            $query->whereDate('instalacoes_produtos.created_at', '>=', $request->criado_de)
                  ->whereDate('instalacoes_produtos.created_at', '<=', $request->criado_ate);
        }

        if ($request->has('status')) {
            $query->where('instalacoes_produtos.status', $request->status);
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
            return $query->select('instalacoes_produtos.*', 'produtos.nome as produto_nome')
                         ->join('produtos', 'instalacoes_produtos.produtos_id', '=', 'produtos.id')
                         ->orderBy('produto_nome')
                         ->with(['produto', 'estoque' => function ($subQuery) use ($request) {
                            if ($request->has('vendido')) {
                                $subQuery->where('vendido', $request->vendido);
                            }
                            if ($request->has('instalacoes_id')) {
                                $subQuery->where('instalacoes_id', $request->instalacoes_id);
                            }
                         }])
                         ->paginate($request->por_pagina);
        }
        
        return $query->select('instalacoes_produtos.*', 'produtos.nome as produto_nome')
                     ->join('produtos', 'instalacoes_produtos.produtos_id', '=', 'produtos.id')
                     ->orderBy('produto_nome')
                     ->with(['produto', 'estoque' => function ($subQuery) use ($request) {
                        if ($request->has('vendido')) {
                            $subQuery->where('vendido', $request->vendido);
                        }
                        if ($request->has('instalacoes_id')) {
                            $subQuery->where('instalacoes_id', $request->instalacoes_id);
                        }
                     }])
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
        $instalacaoProduto = $this->model;

        return tap($instalacaoProduto, function ($instalacaoProduto) use ($request) {
            $instalacaoProduto->observacoes = $request->observacoes;
            $instalacaoProduto->instalacoes_id = $request->instalacoes_id;
            $instalacaoProduto->produtos_id = $request->produtos_id;
            $instalacaoProduto->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $instalacaoProduto->status = $request->status;
            $instalacaoProduto->save();

            $instalacaoProduto->load('produto');

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
        $request = request();

        return $this->model->with(['produto', 'estoque' => function ($query) use ($request) {
            if ($request->has('vendido')) {
                $query->where('vendido', $request->vendido);
            }
            if ($request->has('instalacoes_id')) {
                $query->where('instalacoes_id', $request->instalacoes_id);
            }
        }])
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
        $instalacaoProduto = $this->model->findOrFail($id);

        return tap($instalacaoProduto, function ($instalacaoProduto) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            $instalacaoProduto->update($request->except(['criado_por']));
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
        $instalacaoProduto = $this->model->findOrFail($id);

        return $instalacaoProduto->delete();
    }
}
