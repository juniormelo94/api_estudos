<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Collections\InstalacoesColaboradoresCollection;
use App\Repositories\InstalacoesColaboradoresRepository;
use App\Http\Requests\InstalacoesColaboradoresRequest;
use App\Http\Resources\InstalacoesColaboradoresResource;
use App\Repositories\LogsRepository;
use Illuminate\Http\Request;
use Throwable;

class InstalacoesColaboradoresController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\InstalacoesColaboradoresRepository  $instalacoesColaboradoresRepository
     * @return void
     */
    public function __construct(protected InstalacoesColaboradoresRepository $instalacoesColaboradoresRepository)
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
            return (new InstalacoesColaboradoresCollection($this->instalacoesColaboradoresRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar os colaboradores da instalação.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\InstalacoesColaboradoresRequest $request
     * @return \App\Http\Resources\InstalacoesColaboradoresResource|\Illuminate\Http\JsonResponse
     */
    public function store(InstalacoesColaboradoresRequest $request)
    {
        DB::beginTransaction();
        try {
            $instalacaoCliente = $this->instalacoesColaboradoresRepository->create($request);

            DB::commit();

            return (new InstalacoesColaboradoresResource($instalacaoCliente))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar associar colaborador a instalação.'], 
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
     * @return \App\Http\Resources\InstalacoesColaboradoresResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $instalacaoCliente = $this->instalacoesColaboradoresRepository->getById($id);

            return (new InstalacoesColaboradoresResource($instalacaoCliente))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {  
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver associação do colaborador a instalação.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\InstalacoesColaboradoresRequest $request
     * @param int $id
     * @return \App\Http\Resources\InstalacoesColaboradoresResource|\Illuminate\Http\JsonResponse
     */
    public function update(InstalacoesColaboradoresRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $instalacaoCliente = $this->instalacoesColaboradoresRepository->update($request, $id);

            DB::commit();
            
            return (new InstalacoesColaboradoresResource($instalacaoCliente))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar associação do colaborador a instalação.'], 
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
            $this->instalacoesColaboradoresRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Colaborador desassociado da instalação com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar desassociar colaborador da instalação.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
