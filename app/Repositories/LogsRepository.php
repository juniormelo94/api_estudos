<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;
use App\Models\Logs;
use App\Interfaces\RepositoryInterface;

class LogsRepository implements RepositoryInterface
{
    protected Logs $model;

    /**
     * Store a newly created resource in storage.
     *
     * @return void
     */
    public function __construct()
    {
        $this->model = new Logs();
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

        if ($request->has('codigo_erro')) {
            $query->where('codigo_erro', $request->codigo_erro);
        }

        if ($request->has('por_pagina')) {
            return $query->orderBy('id', 'desc')
                         ->paginate($request->por_pagina);
        }

        return $query->orderBy('id', 'desc')
                     ->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function create($th)
    {
        $log = $this->model;
    
        $log->mensagem_erro = $th->getMessage();
        $log->codigo_erro = $th->getCode();
        $log->arquivo_erro = $th->getFile();
        $log->linha_erro = $th->getLine();
        $log->rastreamento_erro = $th->getTraceAsString();
        $log->criado_por = Auth::check() ? Auth::user()->colaborador_user?->colaborador->cpf; : 'Usuário não logado';
        $log->save();
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $log = $this->model->findOrFail($id);

        return $log->delete();
    }
}
