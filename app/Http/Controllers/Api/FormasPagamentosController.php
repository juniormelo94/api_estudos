<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Collections\FormasPagamentosCollection;
use App\Repositories\FormasPagamentosRepository;
use App\Http\Requests\FormasPagamentosRequest;
use App\Http\Resources\FormasPagamentosResource;
use App\Repositories\LogsRepository;
use Illuminate\Http\Request;
use Throwable;

class FormasPagamentosController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\FormasPagamentosRepository  $formasPagamentosRepository
     * @return void
     */
    public function __construct(protected FormasPagamentosRepository $formasPagamentosRepository)
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
            return (new FormasPagamentosCollection($this->formasPagamentosRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar as formas de pagamento.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\FormasPagamentosRequest $request
     * @return \App\Http\Resources\FormasPagamentosResource|\Illuminate\Http\JsonResponse
     */
    public function store(FormasPagamentosRequest $request)
    {
        DB::beginTransaction();
        try {
            $divisao = $this->formasPagamentosRepository->create($request);

            DB::commit();

            return (new FormasPagamentosResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar a forma de pagamento.'], 
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
     * @return \App\Http\Resources\FormasPagamentosResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $divisao = $this->formasPagamentosRepository->getById($id);

            return (new FormasPagamentosResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) { 
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver a forma de pagamento.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\FormasPagamentosRequest $request
     * @param int $id
     * @return \App\Http\Resources\FormasPagamentosResource|\Illuminate\Http\JsonResponse
     */
    public function update(FormasPagamentosRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $divisao = $this->formasPagamentosRepository->update($request, $id);

            DB::commit();
            
            return (new FormasPagamentosResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar a forma de pagamento.'], 
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
            $this->formasPagamentosRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Forma de pagamento deletada com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar a forma de pagamento.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
