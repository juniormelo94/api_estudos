<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use App\Repositories\QuestoesRepository;
use App\Http\Requests\QuestoesRequest;
use App\Http\Resources\QuestoesResource;
use Illuminate\Http\Request;
use Throwable;

class QuestoesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\QuestoesRepository $questaoRepository
     * @return void
     */
    public function __construct(protected QuestoesRepository $questaoRepository)
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
                    'data' => QuestoesResource::Collection($this->questaoRepository->getAll())
                ], 
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar as questões.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\QuestoesRequest $request
     * @return \App\Http\Resources\QuestoesResource|\Illuminate\Http\JsonResponse
     */
    public function store(QuestoesRequest $request)
    {
        DB::beginTransaction();
        try {
            $disciplina = $this->questaoRepository->create($request);

            DB::commit();

            return response()->json(
                ['status' => true, 'data' => new QuestoesResource($disciplina)]
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar a questão.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \App\Http\Resources\QuestoesResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $disciplina = $this->questaoRepository->getById($id);

            return response()->json(
                ['status' => true, 'data' => new QuestoesResource($disciplina)]
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver a questão.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\QuestoesRequest $request
     * @param int $id
     * @return \App\Http\Resources\QuestoesResource|\Illuminate\Http\JsonResponse
     */
    public function update(QuestoesRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $disciplina = $this->questaoRepository->update($request, $id);

            DB::commit();

            return response()->json(
                ['status' => true, 'data' => new QuestoesResource($disciplina)]
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar a questão.']
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
            $this->questaoRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Questão deletada com sucesso.']
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar a questão.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }
}
