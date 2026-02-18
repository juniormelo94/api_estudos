<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use App\Repositories\ModelosProvasRepository;
use App\Http\Requests\ModelosProvasRequest;
use App\Http\Resources\ModelosProvasResource;
use Illuminate\Http\Request;
use Throwable;

class ModelosProvasController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\ModelosProvasRepository $modeloProvaRepository
     * @return void
     */
    public function __construct(protected ModelosProvasRepository $modeloProvaRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            return response()->json(
                [
                    'status' => true, 
                    'data' => ModelosProvasResource::Collection($this->modeloProvaRepository->getAll())
                ], 
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar os modelos de provas.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ModelosProvasRequest $request
     * @return \App\Http\Resources\ModelosProvasResource|\Illuminate\Http\JsonResponse
     */
    public function store(ModelosProvasRequest $request)
    {
        DB::beginTransaction();
        try {
            $modeloProva = $this->modeloProvaRepository->create($request);

            DB::commit();
            
            return response()->json(
                ['status' => true, 'data' => new ModelosProvasResource($modeloProva)]
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar o modelo de prova.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \App\Http\Resources\ModelosProvasResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $modeloProva = $this->modeloProvaRepository->getById($id);

            return response()->json(
                ['status' => true, 'data' => new ModelosProvasResource($modeloProva)]
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver o modelo de prova.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ModelosProvasRequest $request
     * @param int $id
     * @return \App\Http\Resources\ModelosProvasResource|\Illuminate\Http\JsonResponse
     */
    public function update(ModelosProvasRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $modeloProva = $this->modeloProvaRepository->update($request, $id);

            DB::commit();
            
            return response()->json(
                ['status' => true, 'data' => new ModelosProvasResource($modeloProva)]
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar o modelo de prova.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $this->modeloProvaRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Modelo de prova deletado com sucesso.'] 
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar o modelo de prova.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }
}
