<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use App\Http\Resources\Collections\ProdutosDivulgacoesCollection;
use App\Repositories\ProdutosDivulgacoesRepository;
use App\Http\Requests\ProdutosDivulgacoesRequest;
use App\Http\Resources\ProdutosDivulgacoesResource;
use Illuminate\Http\Request;
use Throwable;

class ProdutosDivulgacoesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\ProdutosDivulgacoesRepository  $produtosDivulgacoesRepository
     * @return void
     */
    public function __construct(protected ProdutosDivulgacoesRepository $produtosDivulgacoesRepository)
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
            return (new ProdutosDivulgacoesCollection($this->produtosDivulgacoesRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar os armazenamentos de divulgações dos produtos.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ProdutosDivulgacoesRequest $request
     * @return \App\Http\Resources\ProdutosDivulgacoesResource|\Illuminate\Http\JsonResponse
     */
    public function store(ProdutosDivulgacoesRequest $request)
    {
        DB::beginTransaction();
        try {
            $produtoDivulgacao = $this->produtosDivulgacoesRepository->create($request);

            DB::commit();

            return (new ProdutosDivulgacoesResource($produtoDivulgacao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar o armazenamento de divulgação do produto.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $produtoId
     * @return \App\Http\Resources\ProdutosDivulgacoesResource|\Illuminate\Http\JsonResponse
     */
    public function show($produtoId)
    {
        try {
            $produtoDivulgacao = $this->produtosDivulgacoesRepository->getById($produtoId);

            return (new ProdutosDivulgacoesResource($produtoDivulgacao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {   
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver o armazenamento de divulgação do produto.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ProdutosDivulgacoesRequest $request
     * @param int $produtoId
     * @return \App\Http\Resources\ProdutosDivulgacoesResource|\Illuminate\Http\JsonResponse
     */
    public function update(ProdutosDivulgacoesRequest $request, $produtoId)
    {
        DB::beginTransaction();
        try {
            $produtoDivulgacao = $this->produtosDivulgacoesRepository->update($request, $produtoId);

            DB::commit();
            
            return (new ProdutosDivulgacoesResource($produtoDivulgacao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar o armazenamento de divulgação do produto.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $produtoId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($produtoId)
    {
        DB::beginTransaction();
        try {
            $this->produtosDivulgacoesRepository->delete($produtoId);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Armazenamento de divulgação do produto deletado com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar o armazenamento de divulgação do produto.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $produtoId
     * @param string $coluna
     * @return \App\Http\Resources\ProdutosDivulgacoesResource|\Illuminate\Http\JsonResponse
     */
    public function getValueColumn($produtoId, $coluna)
    {
        try {
            $valorColuna = $this->produtosDivulgacoesRepository->getValueColumn($produtoId, $coluna);

            return response()->json(
                ['status' => true, 'data' => [$coluna => $valorColuna]], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {   
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver o armazenamento de divulgação do produto.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * There is some value in the column.
     *
     * @param int $produtoId
     * @param string $coluna
     * @return array
     */
    public function existsValueColumn($produtoId, $coluna)
    {
        try {
            $existe = $this->produtosDivulgacoesRepository->existsValueColumn($produtoId, $coluna);

            return response()->json(
                ['status' => true, 'data' => ['exists' => $existe]], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {   
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => "Erro ao tentar verificar se existe algum valor na coluna: {$coluna}."], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $produtoId
     * @return array
     */
    public function exists($produtoId)
    {
        try {
            $existe = $this->produtosDivulgacoesRepository->exists($produtoId);

            return response()->json(
                ['status' => true, 'data' => ['exists' => $existe]], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {   
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar verificar o armazenamento de divulgação do produto.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
