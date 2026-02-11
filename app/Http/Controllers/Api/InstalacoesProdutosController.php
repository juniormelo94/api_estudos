<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Collections\InstalacoesProdutosCollection;
use App\Repositories\InstalacoesProdutosRepository;
use App\Http\Requests\InstalacoesProdutosRequest;
use App\Http\Resources\InstalacoesProdutosResource;
use App\Repositories\LogsRepository;
use Illuminate\Http\Request;
use Throwable;

class InstalacoesProdutosController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\InstalacoesProdutosRepository  $instalacoesProdutosRepository
     * @return void
     */
    public function __construct(protected InstalacoesProdutosRepository $instalacoesProdutosRepository)
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
            return (new InstalacoesProdutosCollection($this->instalacoesProdutosRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar os produtos da instalação.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\InstalacoesProdutosRequest $request
     * @return \App\Http\Resources\InstalacoesProdutosResource|\Illuminate\Http\JsonResponse
     */
    public function store(InstalacoesProdutosRequest $request)
    {
        DB::beginTransaction();
        try {
            $instalacaoProduto = $this->instalacoesProdutosRepository->create($request);

            DB::commit();

            return (new InstalacoesProdutosResource($instalacaoProduto))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar associar produto a instalação.'], 
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
     * @return \App\Http\Resources\InstalacoesProdutosResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $instalacaoProduto = $this->instalacoesProdutosRepository->getById($id);

            return (new InstalacoesProdutosResource($instalacaoProduto))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {   
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver associação do produto a instalação.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\InstalacoesProdutosRequest $request
     * @param int $id
     * @return \App\Http\Resources\InstalacoesProdutosResource|\Illuminate\Http\JsonResponse
     */
    public function update(InstalacoesProdutosRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $instalacaoProduto = $this->instalacoesProdutosRepository->update($request, $id);

            DB::commit();
            
            return (new InstalacoesProdutosResource($instalacaoProduto))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar associação do produto a instalação.'], 
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
            $this->instalacoesProdutosRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Produto desassociado da instalação com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);
            
            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar desassociar produto da instalação.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
