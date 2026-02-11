<?php

namespace App\Repositories;

use App\Models\Permissoes;
use App\Interfaces\RepositoryInterface;

class PermissoesRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\Permissoes $model
     * @return void
     */
    public function __construct(protected Permissoes $model)
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

        if ($request->has('pesquisar')) {
            $query->where(function ($query) use ($request) {
                    $query->where('chave', 'like', "%$request->pesquisar%")
                          ->orWhere('grupo', 'like', "%$request->pesquisar%")
                          ->orWhere('descricao', 'like', "%$request->pesquisar%");
                });
        }

        if ($request->has('por_pagina')) {
            return $query->orderBy('grupo', 'asc')
                         ->orderBy('chave', 'asc')
                         ->paginate($request->por_pagina);
        }

        return $query->orderBy('grupo', 'asc')
                     ->orderBy('chave', 'asc')
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
        $permissao = $this->model;

        return tap($permissao, function ($permissao) use ($request) {
            $permissao->chave = $request->chave;
            $permissao->grupo = $request->grupo;
            $permissao->descricao = $request->descricao;
            $permissao->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $permissao->status = $request->status;
            $permissao->save();
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
        $permissao = $this->model->findOrFail($id);

        return tap($permissao, function ($permissao) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            $permissao->update($request->except(['criado_por']));
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
        $permissao = $this->model->findOrFail($id);

        return $permissao->delete();
    }
}
