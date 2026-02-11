<?php

namespace App\Repositories;

use App\Models\InstalacoesMarcas;
use App\Interfaces\RepositoryInterface;

class InstalacoesMarcasRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\InstalacoesMarcas $model
     * @return void
     */
    public function __construct(protected InstalacoesMarcas $model)
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
            $query->where('instalacoes_marcas.criado_por', $request->criado_por);
        }

        if ($request->has('instalacoes_id')) {
            $query->where('instalacoes_marcas.instalacoes_id', $request->instalacoes_id);
        }

        if ($request->has('criado_de') && $request->has('criado_ate')) {
            $query->whereDate('instalacoes_marcas.created_at', '>=', $request->criado_de)
                  ->whereDate('instalacoes_marcas.created_at', '<=', $request->criado_ate);
        }

        if ($request->has('status')) {
            $query->where('instalacoes_marcas.status', $request->status);
        }

        if ($request->has('pesquisar')) {
            $query->whereHas('marca', function ($subQuery) use ($request) {
                $subQuery->where('id', 'like', "%$request->pesquisar%")
                         ->orWhere('nome', 'like', "%$request->pesquisar%")
                         ->orWhere('ramo', 'like', "%$request->pesquisar%")
                         ->orWhere('cnpj', 'like', "%$request->pesquisar%");
            });
        }

        if ($request->has('por_pagina')) {
            return $query->select('instalacoes_marcas.*', 'marcas.nome as marca_nome')
                         ->join('marcas', 'instalacoes_marcas.marcas_id', '=', 'marcas.id')
                         ->orderBy('marca_nome')
                         ->with('marca')
                         ->paginate($request->por_pagina);
        }

        return $query->select('instalacoes_marcas.*', 'marcas.nome as marca_nome')
                     ->join('marcas', 'instalacoes_marcas.marcas_id', '=', 'marcas.id')
                     ->orderBy('marca_nome')
                     ->with('marca')
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
        $instalacaoMarca = $this->model;

        return tap($instalacaoMarca, function ($instalacaoMarca) use ($request) {
            $instalacaoMarca->observacoes = $request->observacoes;
            $instalacaoMarca->instalacoes_id = $request->instalacoes_id;
            $instalacaoMarca->marcas_id = $request->marcas_id;
            $instalacaoMarca->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $instalacaoMarca->status = $request->status;
            $instalacaoMarca->save();

            $instalacaoMarca->load('marca');
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
        $instalacaoMarca = $this->model->findOrFail($id);

        return tap($instalacaoMarca, function ($instalacaoMarca) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            $instalacaoMarca->update($request->except(['criado_por']));
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
        $instalacaoMarca = $this->model->findOrFail($id);

        return $instalacaoMarca->delete();
    }
}
