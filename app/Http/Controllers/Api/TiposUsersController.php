<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Collections\TiposUsersCollection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use App\Repositories\TiposUsersRepository;
use App\Http\Requests\TiposUsersRequest;
use App\Http\Resources\TiposUsersResource;
use Illuminate\Http\Request;
use Throwable;

class TiposUsersController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\TiposUsersRepository  $tiposUsersRepository
     * @return void
     */
    public function __construct(protected TiposUsersRepository $tiposUsersRepository)
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
            return (new TiposUsersCollection($this->tiposUsersRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar os tipos de usuários.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\TiposUsersRequest $request
     * @return \App\Http\Resources\TiposUsersResource|\Illuminate\Http\JsonResponse
     */
    public function store(TiposUsersRequest $request)
    {
        DB::beginTransaction();
        try {
            $tipoUser = $this->tiposUsersRepository->create($request);

            DB::commit();

            return (new TiposUsersResource($tipoUser))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar o tipo de usuário.'], 
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
     * @return \App\Http\Resources\TiposUsersResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $tipoUser = $this->tiposUsersRepository->getById($id);

            return (new TiposUsersResource($tipoUser))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) { 
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver o tipo de usuário.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\TiposUsersRequest $request
     * @param int $id
     * @return \App\Http\Resources\TiposUsersResource|\Illuminate\Http\JsonResponse
     */
    public function update(TiposUsersRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $tipoUser = $this->tiposUsersRepository->update($request, $id);

            DB::commit();
            
            return (new TiposUsersResource($tipoUser))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar o tipo de usuário.'], 
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
            $this->tiposUsersRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Tipo de usuário deletado com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar o tipo de usuário.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
