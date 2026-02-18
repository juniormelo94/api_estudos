<?php

namespace App\Repositories;

use App\Models\Alternativas;
use App\Interfaces\RepositoryInterface;
use App\Models\Questoes;

class QuestoesRepository implements RepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @param App\Models\Questoes $model
     * @return void
     */
    public function __construct(protected Questoes $model)
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

        if ($request->has('disciplinas_id')) {
            $query->where('disciplinas_id', $request->disciplinas_id);
        }

        if ($request->has('pesquisar')) {
            $query->where('texto', 'like', "%$request->pesquisar%");
        }

        if ($request->has('por_pagina')) {
            return $query->orderBy('id', 'desc')
                         ->with('alternativas')
                         ->paginate($request->por_pagina);
        }

        return $query->orderBy('id', 'desc')
                     ->with('alternativas')
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
        $questao = $this->model;

        return tap($questao, function ($questao) use ($request) {
            $questao->texto = $request->texto;
            $questao->img = $request->img;
            $questao->disciplinas_id = $request->disciplinas_id;
            $questao->status = $request->status;
            $questao->criado_por = $request->user()->id;
            $questao->save();

            // Preparar dados para inserir na tabela Alternativas
            $alternativas = collect($request->alternativas)->map(function ($alternativa) use ($request, $questao) {
                return [
                    'texto' => $alternativa['texto'],
                    'img' => $alternativa['img'],
                    'resposta_correta' => $alternativa['resposta_correta'],
                    'questoes_id' => $questao->id,
                    'status' => $alternativa['status'],
                    'criado_por' => $request->user()->id,
                ];
            });

            if ($alternativas->isNotEmpty()) {
                // Criando linhas na tabela Alternativas
                Alternativas::insert($alternativas->toArray());
            }

            // Buscando questoes e as alternativas
            $questao->load('alternativas');
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
        return $this->model->with('alternativas')
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
        $questao = $this->model->findOrFail($id);

        return tap($questao, function ($questao) use ($request) {
            $request->merge([
                'atualizado_por' => $request->user()->id,
            ]);

            // Atualizando dados do modelo de prova
            $questao->update($request->except(['criado_por']));

            // Deletar todas as alternativas da questão
            Alternativas::where('questoes_id', $questao->id)->delete();

            // Preparar dados para inserir na tabela Alternativas
            $alternativas = collect($request->alternativas)->map(function ($alternativa) use ($request, $questao) {
                return [
                    'texto' => $alternativa['texto'],
                    'img' => $alternativa['img'],
                    'resposta_correta' => $alternativa['resposta_correta'],
                    'questoes_id' => $questao->id,
                    'status' => $alternativa['status'],
                    'criado_por' => $request->user()->id,
                ];
            });

            if ($alternativas->isNotEmpty()) {
                // Criando linhas na tabela Alternativas
                Alternativas::insert($alternativas->toArray());
            }

            // Buscando questoes e as alternativas
            $questao->load('alternativas');
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
        $questao = $this->model->findOrFail($id);

        return $questao->delete();
    }
}