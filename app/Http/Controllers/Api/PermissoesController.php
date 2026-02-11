<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Collections\PermissoesCollection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use App\Repositories\PermissoesRepository;
use App\Http\Requests\PermissoesRequest;
use App\Http\Resources\PermissoesResource;
use Illuminate\Http\Request;
use Throwable;

class PermissoesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\PermissoesRepository  $permissoesRepository
     * @return void
     */
    public function __construct(protected PermissoesRepository $permissoesRepository)
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
            return (new PermissoesCollection($this->permissoesRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar as permissões.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\PermissoesRequest $request
     * @return \App\Http\Resources\PermissoesResource|\Illuminate\Http\JsonResponse
     */
    public function store(PermissoesRequest $request)
    {
        DB::beginTransaction();
        try {
            $permissao = $this->permissoesRepository->create($request);

            DB::commit();

            return (new PermissoesResource($permissao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar a permissão.'], 
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
     * @return \App\Http\Resources\PermissoesResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $permissao = $this->permissoesRepository->getById($id);

            return (new PermissoesResource($permissao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) { 
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver a permissão.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\PermissoesRequest $request
     * @param int $id
     * @return \App\Http\Resources\PermissoesResource|\Illuminate\Http\JsonResponse
     */
    public function update(PermissoesRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $permissao = $this->permissoesRepository->update($request, $id);

            DB::commit();
            
            return (new PermissoesResource($permissao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar a permissão.'], 
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
            $this->permissoesRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Permissão deletada com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar a permissão.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
