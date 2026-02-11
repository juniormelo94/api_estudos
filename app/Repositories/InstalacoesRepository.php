<?php

namespace App\Repositories;

use App\Models\Instalacoes;
use App\Interfaces\RepositoryInterface;

class InstalacoesRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\Instalacoes $model
     * @return void
     */
    public function __construct(protected Instalacoes $model)
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

        if ($request->has('divisoes_id')) {
            $query->where('divisoes_id', $request->divisoes_id);
        }

        if ($request->has('pesquisar')) {
            $query->where(function ($query) use ($request) {
                    $query->where('id', 'like', "%$request->pesquisar%")
                          ->orWhere('endereco', 'like', "%$request->pesquisar%")
                          ->orWhere('bairro', 'like', "%$request->pesquisar%")
                          ->orWhere('complemento', 'like', "%$request->pesquisar%")
                          ->orWhere('cidade', 'like', "%$request->pesquisar%")
                          ->orWhere('uf', 'like', "%$request->pesquisar%")
                          ->orWhere('cep', 'like', "%$request->pesquisar%");
                });
        }

        if ($request->has('por_pagina')) {
            return $query->orderBy('endereco', 'asc')
                         ->paginate($request->por_pagina);
        }

        return $query->orderBy('endereco', 'asc')
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
        $instalacao = $this->model;

        return tap($instalacao, function ($instalacao) use ($request) {
            $instalacao->endereco = strtoupper($request->endereco);
            $instalacao->bairro = strtoupper($request->bairro);
            $instalacao->numero = strtoupper($request->numero);
            $instalacao->complemento = strtoupper($request->complemento);
            $instalacao->cep = $request->cep;
            $instalacao->cidade = strtoupper($request->cidade);
            $instalacao->uf = strtoupper($request->uf);
            $instalacao->email = $request->email;
            $instalacao->telefone = $request->telefone;
            $instalacao->celular = $request->celular;
            $instalacao->whatsapp = $request->whatsapp;
            $instalacao->instagram = $request->instagram;
            $instalacao->facebook = $request->facebook;
            $instalacao->divisoes_id = $request->divisoes_id;
            $instalacao->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $instalacao->status = $request->status;
            $instalacao->save();
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
        $instalacao = $this->model->findOrFail($id);

        return tap($instalacao, function ($instalacao) use ($request) {
            $request->merge([
                'endereco' => strtoupper($request->endereco),
                'bairro' => strtoupper($request->bairro),
                'numero' => strtoupper($request->numero),
                'complemento' => strtoupper($request->complemento),
                'cidade' => strtoupper($request->cidade),
                'uf' => strtoupper($request->uf),
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            $instalacao->update($request->except(['criado_por']));
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
        $instalacao = $this->model->findOrFail($id);

        return $instalacao->delete();
    }
}
