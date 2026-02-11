<?php

namespace App\Repositories;

use App\Models\MaquinasCartao;
use App\Interfaces\RepositoryInterface;

class MaquinasCartaoRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\MaquinasCartao $model
     * @return void
     */
    public function __construct(protected MaquinasCartao $model)
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

        if ($request->has('instalacoes_id')) {
            $query->where('instalacoes_id', $request->instalacoes_id);
        }

        if ($request->has('pesquisar')) {
            $query->where(function ($query) use ($request) {
                    $query->where('id', 'like', "%$request->pesquisar%")
                          ->orWhere('modelo', 'like', "%$request->pesquisar%")
                          ->orWhere('empresa_responsavel', 'like', "%$request->pesquisar%");
                });
        }

        if ($request->has('por_pagina')) {
            return $query->orderBy('modelo', 'asc')
                         ->paginate($request->por_pagina);
        }

        return $query->orderBy('modelo', 'asc')
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
        $maquinaCartao = $this->model;

        return tap($maquinaCartao, function ($maquinaCartao) use ($request) {
            $maquinaCartao->modelo = strtoupper($request->modelo);
            $maquinaCartao->empresa_responsavel = strtoupper($request->empresa_responsavel);
            $maquinaCartao->bandeiras_aceitas = $request->bandeiras_aceitas;
            $maquinaCartao->taxa_debito = $request->taxa_debito;
            $maquinaCartao->taxas_parcelas = $request->taxas_parcelas;
            $maquinaCartao->taxas_links_parcelas = $request->taxas_links_parcelas;
            $maquinaCartao->taxa_pix = $request->taxa_pix;
            $maquinaCartao->instalacoes_id = $request->instalacoes_id;
            $maquinaCartao->criado_por = $request->user()->colaborador_user?->colaborador->cpf;
            $maquinaCartao->status = $request->status;
            $maquinaCartao->save();
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
        $maquinaCartao = $this->model->findOrFail($id);

        return tap($maquinaCartao, function ($maquinaCartao) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->colaborador_user?->colaborador->cpf,
            ]);

            $maquinaCartao->update($request->except(['criado_por']));
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
        $maquinaCartao = $this->model->findOrFail($id);

        return $maquinaCartao->delete();
    }
}
