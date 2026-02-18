<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\ModelosProvas;
use App\Interfaces\RepositoryInterface;
use App\Models\Provas;
use App\Models\ProvasQuestoes;
use App\Models\Questoes;

class ProvasRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\Provas $model
     * @return void
     */
    public function __construct(protected Provas $model)
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

        if ($request->has('modelos_provas_id')) {
            $query->where('modelos_provas_id', $request->modelos_provas_id);
        }

        if ($request->has('pesquisar')) {
            $query->where('nome', 'like', "%$request->pesquisar%");
        }

        if ($request->has('por_pagina')) {
            return $query->orderBy('id', 'desc')
                         ->with('provas_questoes')
                         ->paginate($request->por_pagina);
        }

        return $query->orderBy('id', 'desc')
                     ->with('provas_questoes')
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
        return DB::transaction(function () use ($request) {
            $prova = new $this->model;

            $modeloProva = ModelosProvas::with('modelos_provas_disciplinas.disciplina')
                                        ->findOrFail($request->modelos_provas_id);

            $prova->nome = $modeloProva->nome;
            $prova->modelos_provas_id = $request->modelos_provas_id;
            $prova->status = $request->status;
            $prova->criado_por = $request->user()->id;
            $prova->save();

            foreach ($modeloProva->modelos_provas_disciplinas as $modeloProvaDisciplina) {

                $query = Questoes::where('disciplinas_id', $modeloProvaDisciplina->disciplinas_id);

                // Se não permitir repetir questões já usadas em outras provas com o mesmo modelo
                if ($request->boolean('questoes_repetidas') === false) {
                    $query->whereNotIn('id', function ($q) use ($request) {
                        $q->select('pq.questoes_id')
                          ->from('provas_questoes as pq')
                          ->join('provas as p', 'p.id', '=', 'pq.provas_id')
                          ->where('p.modelos_provas_id', $request->modelos_provas_id);
                    });
                }

                // Buscar questões dessa disciplina aleatoriamente para a prova
                $questoes = $query->inRandomOrder()
                                  ->limit($modeloProvaDisciplina->qtd_questoes)
                                  ->get();

                // Verificar se a quantidade de questões disponiveis dessa disicplinas
                if ($questoes->count() < $modeloProvaDisciplina->qtd_questoes) {
                    return [
                        'status' => false,
                        'message' => "Não há questões suficientes para disciplina {$modeloProvaDisciplina->disciplina->nome}"
                    ];
                }

                // Preparar dados para inserir na tabela ProvasQuestoes
                $provasQuestoes = collect($questoes)->map(function ($questao) use ($prova) {
                    return [
                        'provas_id' => $prova->id,
                        'questoes_id' => $questao->id,
                    ];
                });

                if ($provasQuestoes->isNotEmpty()) {
                    // Criando linhas na tabela ProvasQuestoes
                    ProvasQuestoes::insert($provasQuestoes->toArray());
                }
            }

            return [
                'status' => true,
                'data' => $prova->load('provas_questoes')
            ];
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
        return $this->model->with('provas_questoes')
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
        return DB::transaction(function () use ($request, $id) {
            $prova = $this->model->findOrFail($id);

            $modeloProva = ModelosProvas::with('modelos_provas_disciplinas.disciplina')
                                        ->findOrFail($request->modelos_provas_id);

            $request->merge([
                'atualizado_por' => $request->user()->id,
            ]);

            // Atualizando dados da prova
            $prova->update($request->except(['criado_por', 'modelos_provas_id']));

            foreach ($modeloProva->modelos_provas_disciplinas as $modeloProvaDisciplina) {

                $query = Questoes::where('disciplinas_id', $modeloProvaDisciplina->disciplinas_id);

                // Se não permitir repetir questões já usadas em outras provas
                if ($request->boolean('questoes_repetidas') === false) {
                    $query->whereNotIn('id', function ($q) use ($request) {
                        $q->select('pq.questoes_id')
                          ->from('provas_questoes as pq')
                          ->join('provas as p', 'p.id', '=', 'pq.provas_id')
                          ->where('p.modelos_provas_id', $request->modelos_provas_id);
                    });
                }

                // Questões atuais dessa disciplina na prova
                $questoesAtuaisIds = ProvasQuestoes::where('provas_id', $prova->id)
                    ->whereIn('questoes_id', function ($q) use ($modeloProvaDisciplina) {
                        $q->select('id')
                        ->from('questoes')
                        ->where('disciplinas_id', $modeloProvaDisciplina->disciplinas_id);
                    })
                    ->pluck('questoes_id');

                // Verificar se quantidade atual é igual a do modelo de prova
                if ($questoesAtuaisIds->count() >= $modeloProvaDisciplina->qtd_questoes) {
                    continue;
                }

                $qtdeQuestoesFaltante = $modeloProvaDisciplina->qtd_questoes - $questoesAtuaisIds->count();

                // Buscar questões dessa disciplina aleatoriamente para a prova
                $questoes = $query->inRandomOrder()
                                  ->limit($qtdeQuestoesFaltante)
                                  ->get();

                // Verificar se a quantidade de questões disponiveis dessa disicplinas
                if ($questoes->count() < $qtdeQuestoesFaltante) {
                    return [
                        'status' => false,
                        'message' => "Não há questões suficientes para disciplina {$modeloProvaDisciplina->disciplina->nome}"
                    ];
                }

                // Preparar dados para inserir na tabela ProvasQuestoes
                $provasQuestoes = collect($questoes)->map(function ($questao) use ($prova) {
                    return [
                        'provas_id' => $prova->id,
                        'questoes_id' => $questao->id,
                    ];
                });

                if ($provasQuestoes->isNotEmpty()) {
                    // Criando linhas na tabela ProvasQuestoes
                    ProvasQuestoes::insert($provasQuestoes->toArray());
                }
            }

            return [
                'status' => true,
                'data' => $prova->load('provas_questoes')
            ];
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
        $prova = $this->model->findOrFail($id);

        return $prova->delete();
    }
}