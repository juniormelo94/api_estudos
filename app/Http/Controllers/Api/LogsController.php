<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Logs;
use App\Http\Resources\Collections\LogsCollection;
use App\Repositories\LogsRepository;
use App\Http\Resources\LogsResource;
use Illuminate\Http\Request;
use Throwable;

class LogsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\LogsRepository  $logsRepository
     * @return void
     */
    public function __construct(protected LogsRepository $logsRepository)
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
            return (new LogsCollection($this->logsRepository->getAll()))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            Logs::create([
                'mensagem_erro' => $th->getMessage(),
                'codigo_erro' => $th->getCode(),
                'arquivo_erro' => $th->getFile(),
                'linha_erro' => $th->getLine(),
                'rastreamento_erro' => $th->getTraceAsString(),
                'criado_por' => Auth::user()->name,
            ]);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar listar os logs.'], 
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
     * @return \App\Http\Resources\LogsResource|\Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $divisao = $this->logsRepository->getById($id);

            return (new LogsResource($divisao))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) { 
            Logs::create([
                'mensagem_erro' => $th->getMessage(),
                'codigo_erro' => $th->getCode(),
                'arquivo_erro' => $th->getFile(),
                'linha_erro' => $th->getLine(),
                'rastreamento_erro' => $th->getTraceAsString(),
                'criado_por' => Auth::user()->name,
            ]);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar ver o log.'], 
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
            $this->logsRepository->delete($id);

            DB::commit();

            return response()->json(
                ['status' => true, 'message' => 'Log deletado com sucesso.'], 
                200,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } catch (Throwable $th) {
            DB::rollBack();

            Logs::create([
                'mensagem_erro' => $th->getMessage(),
                'codigo_erro' => $th->getCode(),
                'arquivo_erro' => $th->getFile(),
                'linha_erro' => $th->getLine(),
                'rastreamento_erro' => $th->getTraceAsString(),
                'criado_por' => Auth::user()->name,
            ]);

            return response()->json(
                ['status' => false, 'message' => 'Erro ao tentar deletar o log.'], 
                500,
                ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }
}
