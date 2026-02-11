<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\Collections\CombosCollection;
use App\Repositories\CombosRepository;
use App\Http\Requests\CombosRequest;
use App\Http\Resources\CombosResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use Illuminate\Http\Request;
use Throwable;

class CombosController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\CombosRepository  $combosRepository
     * @return void
     */
    public function __construct(protected CombosRepository $combosRepository)
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
            return (new CombosCollection($this->combosRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar os combos.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CombosRequest $request
     * @return \App\Http\Resources\CombosResource|\Illuminate\Http\JsonResponse
     */
    public function store(CombosRequest $request)
    {
        DB::beginTransaction();
        try {
            $combo = $this->combosRepository->create($request);

            DB::commit();

            return (new CombosResource($combo))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar criar o combo.'], 
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
     * @return \App\Http\Resources\DivisoesResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $combo = $this->combosRepository->getById($id);

            return (new CombosResource($combo))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) { 
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver o combo.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\CombosRequest $request
     * @param int $id
     * @return \App\Http\Resources\CombosResource|\Illuminate\Http\JsonResponse
     */
    public function update(CombosRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $combo = $this->combosRepository->update($request, $id);

            DB::commit();
            
            return (new CombosResource($combo))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar o combo.'], 
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
            $this->combosRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Combo deletado com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar o combo.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
