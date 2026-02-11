<?php

namespace App\Repositories;

use App\Models\ProdutosDivulgacoes;
use App\Interfaces\RepositoryInterface;

class ProdutosDivulgacoesRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\ProdutosDivulgacoes $model
     * @return void
     */
    public function __construct(protected ProdutosDivulgacoes $model)
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

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('produtos_id')) {
            $query->where('produtos_id', $request->produtos_id);
        }

        if ($request->has('por_pagina')) {
            return $query->paginate($request->por_pagina);
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
        $produtoDivulgacao = $this->model;
        
        return tap($produtoDivulgacao, function ($produtoDivulgacao) use ($request) {
            $produtoDivulgacao->img_padrao_1 = $request->img_padrao_1;
            $produtoDivulgacao->img_padrao_2 = $request->img_padrao_2;
            $produtoDivulgacao->img_padrao_3 = $request->img_padrao_3;
            $produtoDivulgacao->img_promocao_1 = $request->img_promocao_1;
            $produtoDivulgacao->img_promocao_2 = $request->img_promocao_2;
            $produtoDivulgacao->img_promocao_3 = $request->img_promocao_3;
            $produtoDivulgacao->produtos_id = $request->produtos_id;
            $produtoDivulgacao->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $produtoDivulgacao->status = $request->status;
            $produtoDivulgacao->save();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param int $produtoId
     * @return array
     */
    public function getById($produtoId)
    {
        $request = request();

        return $this->model->where('produtos_id', $produtoId)->first();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $produtoId
     * @return array
     */
    public function update($request, $produtoId)
    {
        $produtoDivulgacao = $this->model->where('produtos_id', $produtoId)->firstOrFail();

        return tap($produtoDivulgacao, function ($produtoDivulgacao) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            $produtoDivulgacao->update($request->except(['criado_por']));
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $produtoId
     * @return bool
     */
    public function delete($produtoId)
    {
        $produtoDivulgacao = $this->model->where('produtos_id', $produtoId)->firstOrFail();

        return $produtoDivulgacao->delete();
    }

    /**
     * Display the specified resource.
     *
     * @param int $produtoId
     * @param string $coluna
     * @return array
     */
    public function getValueColumn($produtoId, $coluna)
    {
        $request = request();

        return $this->model->where('produtos_id', $produtoId)->value($coluna);
    }

    /**
     * There is some value in the column.
     *
     * @param int $produtoId
     * @param string $coluna
     * @return bool
     */
    public function existsValueColumn($produtoId, $coluna)
    {
        $request = request();

        return $this->model->where('produtos_id', $produtoId)->whereNotNull($coluna)->exists();
    }

    /**
     * Check if exists.
     *
     * @param int $produtoId
     * @return bool
     */
    public function exists($produtoId)
    {
        $request = request();

        return $this->model->where('produtos_id', $produtoId)->exists();
    }
}
