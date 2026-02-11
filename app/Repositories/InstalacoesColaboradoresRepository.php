<?php

namespace App\Repositories;

use App\Models\InstalacoesColaboradores;
use App\Interfaces\RepositoryInterface;

class InstalacoesColaboradoresRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\InstalacoesColaboradores $model
     * @return void
     */
    public function __construct(protected InstalacoesColaboradores $model)
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
            $query->where('instalacoes_colaboradores.criado_por', $request->criado_por);
        }

        if ($request->has('instalacoes_id')) {
            $query->where('instalacoes_colaboradores.instalacoes_id', $request->instalacoes_id);
        }

        if ($request->has('criado_de') && $request->has('criado_ate')) {
            $query->whereDate('instalacoes_colaboradores.created_at', '>=', $request->criado_de)
                  ->whereDate('instalacoes_colaboradores.created_at', '<=', $request->criado_ate);
        }

        if ($request->has('status')) {
            $query->where('instalacoes_colaboradores.status', $request->status);
        }

        if ($request->has('pesquisar')) {
            $query->whereHas('colaborador', function ($subQuery) use ($request) {
                $subQuery->where('id', 'like', "%$request->pesquisar%")
                         ->orWhere('nome_completo', 'like', "%$request->pesquisar%")
                         ->orWhere('apelido', 'like', "%$request->pesquisar%")
                         ->orWhere('cpf', 'like', "%$request->pesquisar%");
            });
        }

        if ($request->has('por_pagina')) {
            return $query->select('instalacoes_colaboradores.*', 'colaboradores.nome_completo as colaborador_nome_completo')
                         ->join('colaboradores', 'instalacoes_colaboradores.colaboradores_id', '=', 'colaboradores.id')
                         ->orderBy('colaborador_nome_completo')
                         ->with('colaborador')
                         ->paginate($request->por_pagina);
        }

        return $query->select('instalacoes_colaboradores.*', 'colaboradores.nome_completo as colaborador_nome_completo')
                     ->join('colaboradores', 'instalacoes_colaboradores.colaboradores_id', '=', 'colaboradores.id')
                     ->orderBy('colaborador_nome_completo')
                     ->with('colaborador')
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
        $instalacaoColaborador = $this->model;

        return tap($instalacaoColaborador, function ($instalacaoColaborador) use ($request) {
            $instalacaoColaborador->observacoes = $request->observacoes;
            $instalacaoColaborador->instalacoes_id = $request->instalacoes_id;
            $instalacaoColaborador->colaboradores_id = $request->colaboradores_id;
            $instalacaoColaborador->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $instalacaoColaborador->status = $request->status;
            $instalacaoColaborador->save();

            $instalacaoColaborador->load('colaborador');
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
        $instalacaoColaborador = $this->model->findOrFail($id);

        return tap($instalacaoColaborador, function ($instalacaoColaborador) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            $instalacaoColaborador->update($request->except(['criado_por']));
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
        $instalacaoColaborador = $this->model->findOrFail($id);

        return $instalacaoColaborador->delete();
    }
}
