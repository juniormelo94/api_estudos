<?php

namespace App\Repositories;

use App\Models\ModelosProvas;
use App\Models\ModelosProvasDisciplinas;
use App\Interfaces\RepositoryInterface;

class ModelosProvasRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\ModelosProvas $model
     * @return void
     */
    public function __construct(protected ModelosProvas $model)
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

        if ($request->has('criado_de') && $request->has('criado_ate')) {
            $query->whereDate('created_at', '>=', $request->criado_de)
                  ->whereDate('created_at', '<=', $request->criado_ate);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('pesquisar')) {
            $query->where('nome', 'like', "%$request->pesquisar%");
        }

        if ($request->has('por_pagina')) {
            return $query->orderBy('nome', 'asc')
                         ->with('modelos_provas_disciplinas.disciplina')
                         ->paginate($request->por_pagina);
        }

        return $query->orderBy('nome', 'asc')
                     ->with('modelos_provas_disciplinas.disciplina')
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
        $modeloProva = $this->model;

        return tap($modeloProva, function ($modeloProva) use ($request) {
            $modeloProva->nome = $request->nome;
            $modeloProva->status = $request->status;
            $modeloProva->criado_por = $request->user()->id;
            $modeloProva->save();

            // Preparar dados para inserir na tabela ModelosProvasDisciplinas
            $modelosProvasDisciplinas = collect($request->modelos_provas_disciplinas)->map(function ($modeloProvaDisciplina) use ($request, $modeloProva) {
                return [
                    'modelos_provas_id' => $modeloProva->id,
                    'disciplinas_id' => $modeloProvaDisciplina['disciplinas_id'],
                    'qtd_questoes' => $modeloProvaDisciplina['qtd_questoes'],
                    'status' => $modeloProvaDisciplina['status'],
                    'criado_por' => $request->user()->id,
                ];
            });

            if ($modelosProvasDisciplinas->isNotEmpty()) {
                // Criando linhas na tabela ModelosProvasDisciplinas
                ModelosProvasDisciplinas::insert($modelosProvasDisciplinas->toArray());
            }

            // Buscando modelo de prova e as disciplinas
            $modeloProva->load('modelos_provas_disciplinas.disciplina');
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
        return $this->model->with('modelos_provas_disciplinas.disciplina')
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
        $modeloProva = $this->model->findOrFail($id);

        return tap($modeloProva, function ($modeloProva) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->id,
            ]);

            // Atualizando dados do modelo de prova
            $modeloProva->update($request->except(['criado_por']));

            // Deletar todas as disciplinas do modelo de prova
            ModelosProvasDisciplinas::where('modelos_provas_id', $modeloProva->id)->delete();

            // Preparar dados para inserir na tabela ModelosProvasDisciplinas
            $modelosProvasDisciplinas = collect($request->modelos_provas_disciplinas)->map(function ($modeloProvaDisciplina) use ($request, $modeloProva) {
                return [
                    'modelos_provas_id' => $modeloProva->id,
                    'disciplinas_id' => $modeloProvaDisciplina['disciplinas_id'],
                    'qtd_questoes' => $modeloProvaDisciplina['qtd_questoes'],
                    'status' => $modeloProvaDisciplina['status'],
                    'criado_por' => $request->user()->id,
                ];
            });

            if ($modelosProvasDisciplinas->isNotEmpty()) {
                // Criando linhas na tabela ModelosProvasDisciplinas
                ModelosProvasDisciplinas::insert($modelosProvasDisciplinas->toArray());
            }

            // Buscando modelo de prova e as disciplinas
            $modeloProva->load('modelos_provas_disciplinas.disciplina');
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
        $modeloProva = $this->model->findOrFail($id);

        return $modeloProva->delete();
    }
}