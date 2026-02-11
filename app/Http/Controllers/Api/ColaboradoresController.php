<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Collections\ColaboradoresCollection;
use App\Repositories\ColaboradoresRepository;
use App\Http\Requests\ColaboradoresRequest;
use App\Http\Resources\ColaboradoresResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use Illuminate\Http\Request;
use Throwable;

class ColaboradoresController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\ColaboradoresRepository  $colaboradoresRepository
     * @return void
     */
    public function __construct(protected ColaboradoresRepository $colaboradoresRepository)
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
            return (new ColaboradoresCollection($this->colaboradoresRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar os colaboradores.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ColaboradoresRequest $request
     * @return \App\Http\Resources\ColaboradoresResource|\Illuminate\Http\JsonResponse
     */
    public function store(ColaboradoresRequest $request)
    {
        DB::beginTransaction();
        try {
            $colaborador = $this->colaboradoresRepository->create($request);

            DB::commit();

            return (new ColaboradoresResource($colaborador))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar o colaborador.'], 
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
     * @return \App\Http\Resources\ColaboradoresResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $colaborador = $this->colaboradoresRepository->getById($id);

            return (new ColaboradoresResource($colaborador))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) { 
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver o colaborador.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ColaboradoresRequest $request
     * @param int $id
     * @return \App\Http\Resources\ColaboradoresResource|\Illuminate\Http\JsonResponse
     */
    public function update(ColaboradoresRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $colaborador = $this->colaboradoresRepository->update($request, $id);

            DB::commit();
            
            return (new ColaboradoresResource($colaborador))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar o colaborador.'], 
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
            $this->colaboradoresRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Colaborador deletado com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar o colaborador.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
