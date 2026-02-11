<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\DivisoesCollection;
use App\Repositories\DivisoesRepository;
use App\Http\Requests\DivisoesRequest;
use App\Http\Resources\DivisoesResource;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use Illuminate\Http\Request;
use Throwable;

class DivisoesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\DivisoesRepository  $divisoesRepository
     * @return void
     */
    public function __construct(protected DivisoesRepository $divisoesRepository)
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
            return (new DivisoesCollection($this->divisoesRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar as divisões.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\DivisoesRequest $request
     * @return \App\Http\Resources\DivisoesResource|\Illuminate\Http\JsonResponse
     */
    public function store(DivisoesRequest $request)
    {
        DB::beginTransaction();
        try {
            $divisao = $this->divisoesRepository->create($request);

            DB::commit();

            return (new DivisoesResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar a divisão.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \App\Http\Resources\DivisoesResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $divisao = $this->divisoesRepository->getById($id);

            return (new DivisoesResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) { 
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver a divisão.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\DivisoesRequest $request
     * @param int $id
     * @return \App\Http\Resources\DivisoesResource|\Illuminate\Http\JsonResponse
     */
    public function update(DivisoesRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $divisao = $this->divisoesRepository->update($request, $id);

            DB::commit();
            
            return (new DivisoesResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar a divisão.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
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
            $this->divisoesRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Divisão deletada com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar a divisão.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
