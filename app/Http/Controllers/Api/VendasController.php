<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use Illuminate\Http\Request;
use Throwable;
use App\Http\Resources\Collections\VendasCollection;
use App\Repositories\VendasRepository;
use App\Http\Requests\VendasRequest;
use App\Http\Resources\VendasResource;

class VendasController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\VendasRepository  $vendasRepository
     * @return void
     */
    public function __construct(protected VendasRepository $vendasRepository)
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
            return (new VendasCollection($this->vendasRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar as vendas.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\VendasRequest $request
     * @return \App\Http\Resources\VendasResource|\Illuminate\Http\JsonResponse
     */
    public function store(VendasRequest $request)
    {
        DB::beginTransaction();
        try {
            $divisao = $this->vendasRepository->create($request);

            DB::commit();

            return (new VendasResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar a venda.'], 
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
     * @return \App\Http\Resources\VendasResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $divisao = $this->vendasRepository->getById($id);

            return (new VendasResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {  
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver a venda.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\VendasRequest $request
     * @param int $id
     * @return \App\Http\Resources\VendasResource|\Illuminate\Http\JsonResponse
     */
    public function update(VendasRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $divisao = $this->vendasRepository->update($request, $id);

            DB::commit();
            
            return (new VendasResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar a venda.'], 
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
            $this->vendasRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Venda deletada com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar a venda.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
