<?php

namespace App\Repositories;

use App\Models\Clientes;
use App\Interfaces\RepositoryInterface;

class ClientesRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\Clientes $model
     * @return void
     */
    public function __construct(protected Clientes $model)
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
                    $query->where('id', 'like', "%$request->pesquisar%")
                          ->orWhere('nome_completo', 'like', "%$request->pesquisar%")
                          ->orWhere('apelido', 'like', "%$request->pesquisar%")
                          ->orWhere('cpf', 'like', "%$request->pesquisar%");
                });
        }

        if ($request->has('instalacoes_id')) {
            $query->with(['instalacao_clientes' => function ($subQuery) use ($request) {
                $subQuery->where('instalacoes_id', $request->instalacoes_id);
            }]);
        }

        if ($request->has('por_pagina')) {
            return $query->orderBy('nome_completo', 'asc')
                         ->paginate($request->por_pagina);
        }

        return $query->orderBy('nome_completo', 'asc')
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
        $cliente = $this->model;

        return tap($cliente, function ($cliente) use ($request) {
            $cliente->nome_completo = strtoupper($request->nome_completo);
            $cliente->primeiro_nome = strtoupper($request->primeiro_nome);
            $cliente->ultimo_nome = strtoupper($request->ultimo_nome);
            $cliente->apelido = empty($request->apelido) ? $request->apelido : strtoupper($request->apelido);
            $cliente->cpf = $request->cpf;
            $cliente->data_nascimento = $request->data_nascimento;
            $cliente->rg = $request->rg;
            $cliente->sexo = $request->sexo;
            $cliente->estado_civil = $request->estado_civil;
            $cliente->img = $request->img;
            $cliente->email_pessoal = $request->email_pessoal;
            $cliente->telefone_pessoal = $request->telefone_pessoal;
            $cliente->celular_pessoal = $request->celular_pessoal;
            $cliente->whatsapp_pessoal = $request->whatsapp_pessoal;
            $cliente->instagram_pessoal = $request->instagram_pessoal;
            $cliente->facebook_pessoal = $request->facebook_pessoal;
            $cliente->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $cliente->status = $request->status;
            $cliente->save();
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

        return $this->model->with(['instalacao_clientes' => function ($query) use ($request) {
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
        $cliente = $this->model->findOrFail($id);

        return tap($cliente, function ($cliente) use ($request) {
            $request->merge([
                'nome_completo' => strtoupper($request->nome_completo),
                'primeiro_nome' => strtoupper($request->primeiro_nome),
                'ultimo_nome' => strtoupper($request->ultimo_nome),
                'apelido' => empty($request->apelido) ? $request->apelido : strtoupper($request->apelido),
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            $cliente->update($request->except(['criado_por']));
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
        $cliente = $this->model->findOrFail($id);

        return $cliente->delete();
    }
}
