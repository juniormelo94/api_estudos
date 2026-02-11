<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Collections\InstalacoesClientesCollection;
use App\Repositories\InstalacoesClientesRepository;
use App\Http\Requests\InstalacoesClientesRequest;
use App\Http\Resources\InstalacoesClientesResource;
use App\Repositories\LogsRepository;
use Illuminate\Http\Request;
use Throwable;

class InstalacoesClientesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\InstalacoesClientesRepository  $instalacoesClientesRepository
     * @return void
     */
    public function __construct(protected InstalacoesClientesRepository $instalacoesClientesRepository)
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
            return (new InstalacoesClientesCollection($this->instalacoesClientesRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar os clientes da instalação.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\InstalacoesClientesRequest $request
     * @return \App\Http\Resources\InstalacoesClientesResource|\Illuminate\Http\JsonResponse
     */
    public function store(InstalacoesClientesRequest $request)
    {
        DB::beginTransaction();
        try {
            $instalacaoCliente = $this->instalacoesClientesRepository->create($request);

            DB::commit();

            return (new InstalacoesClientesResource($instalacaoCliente))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar associar cliente a instalação.'], 
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
     * @return \App\Http\Resources\InstalacoesClientesResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $instalacaoCliente = $this->instalacoesClientesRepository->getById($id);

            return (new InstalacoesClientesResource($instalacaoCliente))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver associação do cliente a instalação.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\InstalacoesClientesRequest $request
     * @param int $id
     * @return \App\Http\Resources\InstalacoesClientesResource|\Illuminate\Http\JsonResponse
     */
    public function update(InstalacoesClientesRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $instalacaoCliente = $this->instalacoesClientesRepository->update($request, $id);

            DB::commit();
            
            return (new InstalacoesClientesResource($instalacaoCliente))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar associação do cliente a instalação.'], 
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
            $this->instalacoesClientesRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Cliente desassociado da instalação com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar desassociar cliente da instalação.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
