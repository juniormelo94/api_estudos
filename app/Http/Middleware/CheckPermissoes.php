<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissoes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permissoesRequest): Response
    {
        // Permissões do usuário
        $permissoes_user = $request->user()->colaborador_user?->tipo_user?->tipos_users_permissoes
            ?->map(fn ($p) => $p->permissao->chave)
            ?->toArray() ?? [];

        // CPF do colaborador
        $cpf = $request->user()->colaborador_user?->colaborador->cpf ?? null;

        // Divide parâmetros separados por vírgula ou pipe
        $permissoes = preg_split('/[,|]/', $permissoesRequest);

        foreach ($permissoes as $permissao) {
            $permissao = trim($permissao); 
            // Verificar se tem permissão para executar essa ação
            $temPermissao = in_array($permissao, $permissoes_user);

            // Verificar se esse usuário só pode ter acesso a dados criados por ele
            if (str_contains($permissao, '.seus')) {
                if ($temPermissao) {
                    // Verificar se é buscar de dados
                    if ($request->isMethod('GET')) {
                        if ($cpf) {
                            // Adiciona filtro ao request
                            $request->merge(['criado_por' => $cpf]);
                        }
                    }
                }
                continue;
            }

            // Verificar se tem permissão para executar essa ação
            if (!$temPermissao) {
                return response()->json(
                    ['status' => false, 'message' => 'Permissão negada.'], 
                    403,
                    ['Content-Type' => 'application/json; charset=UTF-8', 'charset' => 'utf-8'],
                    JSON_UNESCAPED_UNICODE
                );
            }
        }

        return $next($request);
    }
}
