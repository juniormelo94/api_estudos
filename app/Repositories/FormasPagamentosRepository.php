<?php

namespace App\Repositories;

use App\Models\FormasPagamentos;
use App\Interfaces\RepositoryInterface;

class FormasPagamentosRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\FormasPagamentos $model
     * @return void
     */
    public function __construct(protected FormasPagamentos $model)
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
                          ->orWhere('nome', 'like', "%$request->pesquisar%");
                });
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
        $marca = $this->model;
        
        return tap($marca, function ($marca) use ($request) {
            $marca->nome = $request->nome;
            $marca->tipo_pagamento = $request->tipo_pagamento;
            $marca->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $marca->status = $request->status;
            $marca->save();
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
        $marca = $this->model->findOrFail($id);

        return tap($marca, function ($marca) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            $marca->update($request->except(['criado_por']));
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
        $marca = $this->model->findOrFail($id);

        return $marca->delete();
    }
}
