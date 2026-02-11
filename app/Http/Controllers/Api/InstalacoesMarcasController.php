<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Collections\InstalacoesMarcasCollection;
use App\Repositories\InstalacoesMarcasRepository;
use App\Http\Requests\InstalacoesMarcasRequest;
use App\Http\Resources\InstalacoesMarcasResource;
use App\Repositories\LogsRepository;
use Illuminate\Http\Request;
use Throwable;

class InstalacoesMarcasController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\InstalacoesMarcasRepository  $instalacoesMarcasRepository
     * @return void
     */
    public function __construct(protected InstalacoesMarcasRepository $instalacoesMarcasRepository)
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
            return (new InstalacoesMarcasCollection($this->instalacoesMarcasRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar as marcas da instalação.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\InstalacoesMarcasRequest $request
     * @return \App\Http\Resources\InstalacoesMarcasResource|\Illuminate\Http\JsonResponse
     */
    public function store(InstalacoesMarcasRequest $request)
    {
        DB::beginTransaction();
        try {
            $instalacaoMarca = $this->instalacoesMarcasRepository->create($request);

            DB::commit();

            return (new InstalacoesMarcasResource($instalacaoMarca))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar associar marca a instalação.'], 
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
     * @return \App\Http\Resources\InstalacoesMarcasResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $instalacaoMarca = $this->instalacoesMarcasRepository->getById($id);

            return (new InstalacoesMarcasResource($instalacaoMarca))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {   
            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver associação da marca a instalação.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\InstalacoesMarcasRequest $request
     * @param int $id
     * @return \App\Http\Resources\InstalacoesMarcasResource|\Illuminate\Http\JsonResponse
     */
    public function update(InstalacoesMarcasRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $instalacaoMarca = $this->instalacoesMarcasRepository->update($request, $id);

            DB::commit();
            
            return (new InstalacoesMarcasResource($instalacaoMarca))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar atualizar associação da marca a instalação.'], 
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
            $this->instalacoesMarcasRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Marca desassociada da instalação com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar desassociar marca da instalação.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
