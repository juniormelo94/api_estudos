<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use App\Repositories\ProvasRepository;
use App\Http\Requests\ProvasRequest;
use App\Http\Resources\ProvasResource;
use Illuminate\Http\Request;
use Throwable;

class ProvasController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\ProvasRepository $provaRepository
     * @return void
     */
    public function __construct(protected ProvasRepository $provaRepository)
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
            return response()->json(
                [
                    'status' => true, 
                    'data' => ProvasResource::Collection($this->provaRepository->getAll())
                ], 
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar as provas.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ProvasRequest $request
     * @return \App\Http\Resources\ProvasResource|\Illuminate\Http\JsonResponse
     */
    public function store(ProvasRequest $request)
    {
        DB::beginTransaction();
        try {
            $result = $this->provaRepository->create($request);

            if (!$result['status']) {
                DB::rollBack();

                return response()->json(
                    ['status' => false, 'message' => $result['message']]
                )->setStatusCode(422)
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            }

            DB::commit();

            return response()->json(
                ['status' => true, 'data' => new ProvasResource($result['data'])]
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar a prova.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \App\Http\Resources\ProvasResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $prova = $this->provaRepository->getById($id);

            return response()->json(
                ['status' => true, 'data' => new ProvasResource($prova)]
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver a prova.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ProvasRequest $request
     * @param int $id
     * @return \App\Http\Resources\ProvasResource|\Illuminate\Http\JsonResponse
     */
    public function update(ProvasRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $result = $this->provaRepository->update($request, $id);

            if (!$result['status']) {
                DB::rollBack();

                return response()->json(
                    ['status' => false, 'message' => $result['message']]
                )->setStatusCode(422)
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            }

            DB::commit();

            return response()->json(
                ['status' => true, 'data' => new ProvasResource($result['data'])]
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar a prova.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
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
            $this->provaRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Prova deletada com sucesso.']
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar a prova.']
            )->setStatusCode(500)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }
}
