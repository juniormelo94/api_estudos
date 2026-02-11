<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Collections\ClientesCollection;
use App\Repositories\ClientesRepository;
use App\Http\Requests\ClientesRequest;
use App\Http\Resources\ClientesResource;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use Illuminate\Http\Request;
use Throwable;

class ClientesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\ClientesRepository  $clientesRepository
     * @return void
     */
    public function __construct(protected ClientesRepository $clientesRepository)
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
            return (new ClientesCollection($this->clientesRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar os clientes.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ClientesRequest $request
     * @return \App\Http\Resources\ClientesResource|\Illuminate\Http\JsonResponse
     */
    public function store(ClientesRequest $request)
    {
        DB::beginTransaction();
        try {
            $divisao = $this->clientesRepository->create($request);

            DB::commit();

            return (new ClientesResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar o cliente.'], 
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
     * @return \App\Http\Resources\ClientesResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $divisao = $this->clientesRepository->getById($id);

            return (new ClientesResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) { 
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver o cliente.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ClientesRequest $request
     * @param int $id
     * @return \App\Http\Resources\ClientesResource|\Illuminate\Http\JsonResponse
     */
    public function update(ClientesRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $divisao = $this->clientesRepository->update($request, $id);

            DB::commit();
            
            return (new ClientesResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar o cliente.'], 
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
            $this->clientesRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Cliente deletado com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar o cliente.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
