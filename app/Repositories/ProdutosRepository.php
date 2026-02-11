<?php

namespace App\Repositories;

use App\Models\Produtos;
use App\Interfaces\RepositoryInterface;

class ProdutosRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\Produtos $model
     * @return void
     */
    public function __construct(protected Produtos $model)
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

        $query = $query->withExists('produto_divulgacao as divulgacao_existe');

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

        if ($request->has('marcas_id')) {
            $query->where('marcas_id', $request->marcas_id);
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

        if ($request->has('instalacoes_id')) {
            $query->with(['instalacao_produtos' => function ($subQuery) use ($request) {
                $subQuery->where('instalacoes_id', $request->instalacoes_id);
            }]);
        }

        if ($request->has('por_pagina')) {
            return $query->orderBy('nome', 'asc')
                         ->paginate($request->por_pagina);
        }

        return $query->orderBy('nome', 'asc')
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
        $produto = $this->model;
        
        return tap($produto, function ($produto) use ($request) {
            $produto->nome = strtoupper($request->nome);
            $produto->tipo = strtoupper($request->tipo);
            $produto->descricao = $request->descricao;
            $produto->codigo_barras = $request->codigo_barras;
            $produto->qr_code = $request->qr_code;
            $produto->img_1 = $request->img_1;
            $produto->img_2 = $request->img_2;
            $produto->img_3 = $request->img_3;
            $produto->marcas_id = $request->marcas_id;
            $produto->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $produto->status = $request->status;
            $produto->save();
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

        return $this->model->with(['instalacao_produtos' => function ($query) use ($request) {
            if ($request->has('instalacoes_id')) {
                $query->where('instalacoes_id', $request->instalacoes_id);
            }
        }])
        ->withExists('produto_divulgacao as divulgacao_existe')
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
        $produto = $this->model->findOrFail($id);

        return tap($produto, function ($produto) use ($request) {
            $request->merge([
                'nome' => strtoupper($request->nome),
                'tipo' => strtoupper($request->tipo),
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            $produto->update($request->except(['criado_por']));
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
        $produto = $this->model->findOrFail($id);

        return $produto->delete();
    }
}
