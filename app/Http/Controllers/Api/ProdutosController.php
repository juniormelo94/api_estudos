<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use App\Http\Resources\Collections\ProdutosCollection;
use App\Repositories\ProdutosRepository;
use App\Http\Requests\ProdutosRequest;
use App\Http\Resources\ProdutosResource;
use Illuminate\Http\Request;
use Throwable;

class ProdutosController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\ProdutosRepository  $produtosRepository
     * @return void
     */
    public function __construct(protected ProdutosRepository $produtosRepository)
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
            return (new ProdutosCollection($this->produtosRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar os produtos.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ProdutosRequest $request
     * @return \App\Http\Resources\ProdutosResource|\Illuminate\Http\JsonResponse
     */
    public function store(ProdutosRequest $request)
    {
        DB::beginTransaction();
        try {
            $divisao = $this->produtosRepository->create($request);

            DB::commit();

            return (new ProdutosResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar o produto.'], 
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
     * @return \App\Http\Resources\ProdutosResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $divisao = $this->produtosRepository->getById($id);

            return (new ProdutosResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {   
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver o produto.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ProdutosRequest $request
     * @param int $id
     * @return \App\Http\Resources\ProdutosResource|\Illuminate\Http\JsonResponse
     */
    public function update(ProdutosRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $divisao = $this->produtosRepository->update($request, $id);

            DB::commit();
            
            return (new ProdutosResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar o produto.'], 
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
            $this->produtosRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Produto deletado com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar o produto.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
