<?php

namespace App\Repositories;

use App\Models\Colaboradores;
use App\Interfaces\RepositoryInterface;

class ColaboradoresRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\Colaboradores $model
     * @return void
     */
    public function __construct(protected Colaboradores $model)
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
            $query->with(['instalacao_colaboradores' => function ($subQuery) use ($request) {
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
        $colaborador = $this->model;

        return tap($colaborador, function ($colaborador) use ($request) {
            $colaborador->nome_completo = strtoupper($request->nome_completo);
            $colaborador->primeiro_nome = strtoupper($request->primeiro_nome);
            $colaborador->ultimo_nome = strtoupper($request->ultimo_nome);
            $colaborador->apelido = empty($request->apelido) ? $request->apelido : strtoupper($request->apelido);
            $colaborador->cpf = $request->cpf;
            $colaborador->data_nascimento = $request->data_nascimento;
            $colaborador->rg = $request->rg;
            $colaborador->sexo = $request->sexo;
            $colaborador->estado_civil = $request->estado_civil;
            $colaborador->img = $request->img;
            $colaborador->email_pessoal = $request->email_pessoal;
            $colaborador->telefone_pessoal = $request->telefone_pessoal;
            $colaborador->celular_pessoal = $request->celular_pessoal;
            $colaborador->whatsapp_pessoal = $request->whatsapp_pessoal;
            $colaborador->instagram_pessoal = $request->instagram_pessoal;
            $colaborador->facebook_pessoal = $request->facebook_pessoal;
            $colaborador->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $colaborador->status = $request->status;
            $colaborador->save();
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

        return $this->model->with(['instalacao_colaboradores' => function ($query) use ($request) {
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
        $colaborador = $this->model->findOrFail($id);

        return tap($colaborador, function ($colaborador) use ($request) {
            $request->merge([
                'nome_completo' => strtoupper($request->nome_completo),
                'primeiro_nome' => strtoupper($request->primeiro_nome),
                'ultimo_nome' => strtoupper($request->ultimo_nome),
                'apelido' => empty($request->apelido) ? $request->apelido : strtoupper($request->apelido),
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            $colaborador->update($request->except(['criado_por']));
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
        $colaborador = $this->model->findOrFail($id);

        return $colaborador->delete();
    }
}
