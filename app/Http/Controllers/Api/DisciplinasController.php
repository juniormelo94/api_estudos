<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use App\Repositories\DisciplinasRepository;
use App\Http\Requests\DisciplinasRequest;
use App\Http\Resources\DisciplinasResource;
use Illuminate\Http\Request;
use Throwable;

class DisciplinasController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\DisciplinasRepository $disciplinaRepository
     * @return void
     */
    public function __construct(protected DisciplinasRepository $disciplinaRepository)
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
                    'data' => DisciplinasResource::Collection($this->disciplinaRepository->getAll())
                ], 
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar as disciplinas.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\DisciplinasRequest $request
     * @return \App\Http\Resources\DisciplinasResource|\Illuminate\Http\JsonResponse
     */
    public function store(DisciplinasRequest $request)
    {
        DB::beginTransaction();
        try {
            $disciplina = $this->disciplinaRepository->create($request);

            DB::commit();

            return response()->json(
                ['status' => true, 'data' => new DisciplinasResource($disciplina)]
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar a disciplina.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \App\Http\Resources\DisciplinasResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $disciplina = $this->disciplinaRepository->getById($id);

            return response()->json(
                ['status' => true, 'data' => new DisciplinasResource($disciplina)]
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver a disciplina.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\DisciplinasRequest $request
     * @param int $id
     * @return \App\Http\Resources\DisciplinasResource|\Illuminate\Http\JsonResponse
     */
    public function update(DisciplinasRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $disciplina = $this->disciplinaRepository->update($request, $id);

            DB::commit();

            return response()->json(
                ['status' => true, 'data' => new DisciplinasResource($disciplina)]
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar a disciplina.']
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
            $this->disciplinaRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Disciplina deletada com sucesso.']
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar a disciplina.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }
}
