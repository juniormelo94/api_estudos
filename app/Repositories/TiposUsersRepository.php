<?php

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\TiposUsers;
use App\Models\TiposUsersPermissoes;

class TiposUsersRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\TiposUsers $model
     * @return void
     */
    public function __construct(protected TiposUsers $model)
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
                    $query->where('tipo', 'like', "%$request->pesquisar%")
                          ->orWhere('descricao', 'like', "%$request->pesquisar%");
                });
        }

        if ($request->has('por_pagina')) {
            return $query->orderBy('tipo', 'asc')
                         ->paginate($request->por_pagina);
        }

        return $query->orderBy('tipo', 'asc')
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
        $tipoUser = $this->model;

        return tap($tipoUser, function ($tipoUser) use ($request) {
            $tipoUser->tipo = $request->tipo;
            $tipoUser->descricao = $request->descricao;
            $tipoUser->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $tipoUser->status = $request->status;
            $tipoUser->save();

            // Preparar dados para inserir na tabela TiposUsersPermissoes
            $tiposUsersPermissoes = collect($request->permissoes_ids)->map(function ($permissao_id) use ($tipoUser) {
                return [
                    'tipos_users_id' => $tipoUser->id,
                    'permissoes_id' => $permissao_id,
                ];
            });

            if ($tiposUsersPermissoes->isNotEmpty()) {
                // Criando linhas na tabela TiposUsersPermissoes
                TiposUsersPermissoes::insert($tiposUsersPermissoes->toArray());
            }

            // Buscando tipo de usuário e as permissões
            $tipoUser->load('tipos_users_permissoes');
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
        return $this->model->with(['tipos_users_permissoes.permissao'])
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
        $tipoUser = $this->model->findOrFail($id);

        return tap($tipoUser, function ($tipoUser) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            // Atualizando dados da tipo de usuário
            $tipoUser->update($request->except(['criado_por']));

            // Deletar todos as permissões do tipo de usuário
            TiposUsersPermissoes::where('tipos_users_id', $tipoUser->id)->delete();

            // Preparar dados para inserir na tabela TiposUsersPermissoes
            $tiposUsersPermissoes = collect($request->permissoes_ids)->map(function ($permissao_id) use ($tipoUser) {
                return [
                    'tipos_users_id' => $tipoUser->id,
                    'permissoes_id' => $permissao_id,
                ];
            });

            if ($tiposUsersPermissoes->isNotEmpty()) {
                // Criando linhas na tabela TiposUsersPermissoes
                TiposUsersPermissoes::insert($tiposUsersPermissoes->toArray());
            }

            // Buscando tipo de usuário e as permissões
            $tipoUser->load('tipos_users_permissoes');
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
        $tipoUser = $this->model->findOrFail($id);

        return $tipoUser->delete();
    }
}
