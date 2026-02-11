<?php

namespace App\Repositories;

use App\Models\InstalacoesClientes;
use App\Interfaces\RepositoryInterface;

class InstalacoesClientesRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\InstalacoesClientes $model
     * @return void
     */
    public function __construct(protected InstalacoesClientes $model)
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
            $query->where('instalacoes_clientes.criado_por', $request->criado_por);
        }

        if ($request->has('instalacoes_id')) {
            $query->where('instalacoes_clientes.instalacoes_id', $request->instalacoes_id);
        }

        if ($request->has('criado_de') && $request->has('criado_ate')) {
            $query->whereDate('instalacoes_clientes.created_at', '>=', $request->criado_de)
                  ->whereDate('instalacoes_clientes.created_at', '<=', $request->criado_ate);
        }

        if ($request->has('status')) {
            $query->where('instalacoes_clientes.status', $request->status);
        }

        if ($request->has('pesquisar')) {
            $query->whereHas('cliente', function ($subQuery) use ($request) {
                $subQuery->where('id', 'like', "%$request->pesquisar%")
                         ->orWhere('nome_completo', 'like', "%$request->pesquisar%")
                         ->orWhere('apelido', 'like', "%$request->pesquisar%")
                         ->orWhere('cpf', 'like', "%$request->pesquisar%");
            });
        }

        if ($request->has('por_pagina')) {
            return $query->select('instalacoes_clientes.*', 'clientes.nome_completo as cliente_nome_completo')
                         ->join('clientes', 'instalacoes_clientes.clientes_id', '=', 'clientes.id')
                         ->orderBy('cliente_nome_completo')
                         ->with('cliente')
                         ->paginate($request->por_pagina);
        }

        return $query->select('instalacoes_clientes.*', 'clientes.nome_completo as cliente_nome_completo')
                     ->join('clientes', 'instalacoes_clientes.clientes_id', '=', 'clientes.id')
                     ->orderBy('cliente_nome_completo')
                     ->with('cliente')
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
        $instalacaoCliente = $this->model;

        return tap($instalacaoCliente, function ($instalacaoCliente) use ($request) {
            $instalacaoCliente->observacoes = $request->observacoes;
            $instalacaoCliente->instalacoes_id = $request->instalacoes_id;
            $instalacaoCliente->clientes_id = $request->clientes_id;
            $instalacaoCliente->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $instalacaoCliente->status = $request->status;
            $instalacaoCliente->save();

            $instalacaoCliente->load('cliente');
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
        $instalacaoCliente = $this->model->findOrFail($id);

        return tap($instalacaoCliente, function ($instalacaoCliente) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            $instalacaoCliente->update($request->except(['criado_por']));
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
        $instalacaoCliente = $this->model->findOrFail($id);

        return $instalacaoCliente->delete();
    }
}
