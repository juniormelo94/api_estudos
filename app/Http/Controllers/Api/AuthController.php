<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AuthResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Repositories\LogsRepository;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Throwable;
use App\Repositories\UserRepository;

class AuthController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Repositories\UserRepository  $userRepository
     * @return void
     */
    public function __construct(protected UserRepository $userRepository)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AuthRequest $request
     * @return \App\Http\Resources\AuthResource|\Illuminate\Http\JsonResponse
     */
    public function registrar(AuthRequest $request)
    {
        DB::beginTransaction();
        try {
            if ($request->input('hash') != '12378910') {
                return response()->json(
                        ['status' => false, 'message' => 'Hash de permissão ausente ou incorreto.'], 
                        422,
                        ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                        JSON_UNESCAPED_UNICODE
                    );
            }

            $user = $this->userRepository->create($request);

            DB::commit();

            return (new AuthResource($user))
                ->response()
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);
    
            return response()->json(
                    ['status' => false, 'message' => 'Erro ao tentar cadastrar o usuário.'], 
                    500,
                    ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                    JSON_UNESCAPED_UNICODE
                );
        }
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \App\Http\Requests\AuthRequest $request
     * @return \App\Http\Resources\AuthResource|\Illuminate\Http\JsonResponse
     */
    public function logar(AuthRequest $request)
    {
        DB::beginTransaction();
        try {
            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                if (!empty($request->user()->tokens()->first())) {
                    $request->user()->tokens()->delete();
                }
                
                DB::commit();

                return (new AuthResource(Auth::user()))
                    ->response()
                    ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            }

            return response()->json(
                    ['status' => false, 'message' => 'Credenciais inválidas.'], 
                    422,
                    ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                    JSON_UNESCAPED_UNICODE
                );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                    ['status' => false, 'message' => 'Erro ao tentar autenticar o usuário.'], 
                    500,
                    ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                    JSON_UNESCAPED_UNICODE
                );
        }
    }

    /**
     * User logged out.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deslogar(Request $request)
    {
        DB::beginTransaction();
        try {
            if (empty($request->user()->tokens()->first())) {
                return response()->json(
                        ['status' => false, 'message' => 'Token inválido.'], 
                        422,
                        ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                        JSON_UNESCAPED_UNICODE
                    );
            }

            $request->user()->currentAccessToken()->delete();

            DB::commit(); 

            return response()->json(
                    ['status' => true, 'message' => 'Deslogado com sucesso.'], 
                    200,
                    ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                    JSON_UNESCAPED_UNICODE
                );
        } catch (Throwable $th) {
            DB::rollBack();

            (new LogsRepository)->create($th);

            return response()->json(
                    ['status' => false, 'message' => 'Erro ao tentar deslogar o usuário.'], 
                    500,
                    ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                    JSON_UNESCAPED_UNICODE
                );
        }
    }
}
