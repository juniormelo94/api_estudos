<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use App\Http\Resources\Collections\MaquinasCartaoCollection;
use App\Repositories\MaquinasCartaoRepository;
use App\Http\Requests\MaquinasCartaoRequest;
use App\Http\Resources\MaquinasCartaoResource;
use Illuminate\Http\Request;
use Throwable;

class MaquinasCartaoController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\MaquinasCartaoRepository  $maquinasCartaoRepository
     * @return void
     */
    public function __construct(protected MaquinasCartaoRepository $maquinasCartaoRepository)
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
            return (new MaquinasCartaoCollection($this->maquinasCartaoRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar as maquinas de cartão.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\MaquinasCartaoRequest $request
     * @return \App\Http\Resources\MaquinasCartaoResource|\Illuminate\Http\JsonResponse
     */
    public function store(MaquinasCartaoRequest $request)
    {
        DB::beginTransaction();
        try {
            $divisao = $this->maquinasCartaoRepository->create($request);

            DB::commit();

            return (new MaquinasCartaoResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar a maquina de cartão.'], 
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
     * @return \App\Http\Resources\MaquinasCartaoResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $divisao = $this->maquinasCartaoRepository->getById($id);

            return (new MaquinasCartaoResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {  
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver a maquina de cartão.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\MaquinasCartaoRequest $request
     * @param int $id
     * @return \App\Http\Resources\MaquinasCartaoResource|\Illuminate\Http\JsonResponse
     */
    public function update(MaquinasCartaoRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $divisao = $this->maquinasCartaoRepository->update($request, $id);

            DB::commit();
            
            return (new MaquinasCartaoResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar a maquina de cartão.'], 
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
            $this->maquinasCartaoRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Maquina de cartão deletada com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar a maquina de cartão.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
