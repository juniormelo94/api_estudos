<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientesController;
use App\Http\Controllers\Api\ColaboradoresController;
use App\Http\Controllers\Api\CombosController;
use App\Http\Controllers\Api\DivisoesController;
use App\Http\Controllers\Api\EstoquesController;
use App\Http\Controllers\Api\FormasPagamentosController;
use App\Http\Controllers\Api\InstalacoesController;
use App\Http\Controllers\Api\InstalacoesClientesController;
use App\Http\Controllers\Api\InstalacoesColaboradoresController;
use App\Http\Controllers\Api\InstalacoesMarcasController;
use App\Http\Controllers\Api\InstalacoesProdutosController;
use App\Http\Controllers\Api\LogsController;
use App\Http\Controllers\Api\MaquinasCartaoController;
use App\Http\Controllers\Api\MarcasController;
use App\Http\Controllers\Api\PermissoesController;
use App\Http\Controllers\Api\ProdutosController;
use App\Http\Controllers\Api\ProdutosDivulgacoesController;
use App\Http\Controllers\Api\TiposUsersController;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\VendasController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/login', function () {
    return 'login';
})->name('login');

Route::controller(AuthController::class)->group(function () {
    Route::post('/logar', 'logar');
    Route::post('/registrar', 'registrar');
    Route::post('/deslogar', 'deslogar')->middleware('auth:sanctum');
});

Route::controller(ClientesController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/clientes', 'index');
    Route::post('/clientes', 'store');
    Route::get('/clientes/{id}', 'show');
    Route::put('/clientes/{id}', 'update');
    Route::delete('/clientes/{id}', 'destroy');
});

Route::controller(ColaboradoresController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/colaboradores', 'index');
    Route::post('/colaboradores', 'store');
    Route::get('/colaboradores/{id}', 'show');
    Route::put('/colaboradores/{id}', 'update');
    Route::delete('/colaboradores/{id}', 'destroy');
});

Route::controller(CombosController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/combos', 'index');
    Route::post('/combos', 'store');
    Route::get('/combos/{id}', 'show');
    Route::put('/combos/{id}', 'update');
    Route::delete('/combos/{id}', 'destroy');
});

Route::controller(DivisoesController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/divisoes', 'index');
    Route::post('/divisoes', 'store');
    Route::get('/divisoes/{id}', 'show');
    Route::put('/divisoes/{id}', 'update');
    Route::delete('/divisoes/{id}', 'destroy');
});

Route::controller(EstoquesController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/estoques', 'index');
    Route::get('/estoques/getAllSolds', 'getAllSolds');
    Route::post('/estoques', 'store');
    Route::get('/estoques/{id}', 'show');
    Route::put('/estoques/{id}', 'update');
    Route::delete('/estoques/{id}', 'destroy');
});

Route::controller(FormasPagamentosController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/formaspagamentos', 'index');
    Route::post('/formaspagamentos', 'store');
    Route::get('/formaspagamentos/{id}', 'show');
    Route::put('/formaspagamentos/{id}', 'update');
    Route::delete('/formaspagamentos/{id}', 'destroy');
});

Route::controller(InstalacoesController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/instalacoes', 'index');
    Route::post('/instalacoes', 'store');
    Route::get('/instalacoes/{id}', 'show');
    Route::put('/instalacoes/{id}', 'update');
    Route::delete('/instalacoes/{id}', 'destroy');
});

Route::controller(InstalacoesClientesController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/instalacoesclientes', 'index');
    Route::post('/instalacoesclientes', 'store');
    Route::get('/instalacoesclientes/{id}', 'show');
    Route::put('/instalacoesclientes/{id}', 'update');
    Route::delete('/instalacoesclientes/{id}', 'destroy');
});

Route::controller(InstalacoesColaboradoresController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/instalacoescolaboradores', 'index');
    Route::post('/instalacoescolaboradores', 'store');
    Route::get('/instalacoescolaboradores/{id}', 'show');
    Route::put('/instalacoescolaboradores/{id}', 'update');
    Route::delete('/instalacoescolaboradores/{id}', 'destroy');
});

Route::controller(InstalacoesMarcasController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/instalacoesmarcas', 'index');
    Route::post('/instalacoesmarcas', 'store');
    Route::get('/instalacoesmarcas/{id}', 'show');
    Route::put('/instalacoesmarcas/{id}', 'update');
    Route::delete('/instalacoesmarcas/{id}', 'destroy');
});

Route::controller(InstalacoesProdutosController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/instalacoesprodutos', 'index');
    Route::post('/instalacoesprodutos', 'store');
    Route::get('/instalacoesprodutos/{id}', 'show');
    Route::put('/instalacoesprodutos/{id}', 'update');
    Route::delete('/instalacoesprodutos/{id}', 'destroy');
});

Route::controller(LogsController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/logs', 'index');
    Route::get('/logs/{id}', 'show');
    Route::delete('/logs/{id}', 'destroy');
});

Route::controller(MaquinasCartaoController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/maquinascartao', 'index');
    Route::post('/maquinascartao', 'store');
    Route::get('/maquinascartao/{id}', 'show');
    Route::put('/maquinascartao/{id}', 'update');
    Route::delete('/maquinascartao/{id}', 'destroy');
});

Route::controller(MarcasController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/marcas', 'index')->middleware('permissoes:marcas.listar|marcas.seus');
    Route::post('/marcas', 'store')->middleware('permissoes:marcas.criar|marcas.seus');
    Route::get('/marcas/{id}', 'show')->middleware('permissoes:marcas.detalhar|marcas.seus');
    Route::put('/marcas/{id}', 'update')->middleware('permissoes:marcas.editar|marcas.seus');
    Route::delete('/marcas/{id}', 'destroy')->middleware('permissoes:marcas.deletar|marcas.seus');
});

Route::controller(PermissoesController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/permissoes', 'index');
    Route::post('/permissoes', 'store');
    Route::get('/permissoes/{id}', 'show');
    Route::put('/permissoes/{id}', 'update');
    Route::delete('/permissoes/{id}', 'destroy');
});

Route::controller(ProdutosController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/produtos', 'index');
    Route::post('/produtos', 'store');
    Route::get('/produtos/{id}', 'show');
    Route::put('/produtos/{id}', 'update');
    Route::delete('/produtos/{id}', 'destroy');
});

Route::controller(ProdutosDivulgacoesController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/produtosdivulgacoes', 'index');
    Route::post('/produtosdivulgacoes', 'store');
    Route::get('/produtosdivulgacoes/{produtoId}', 'show');
    Route::put('/produtosdivulgacoes/{produtoId}', 'update');
    Route::delete('/produtosdivulgacoes/{produtoId}', 'destroy');
    Route::get('/produtosdivulgacoes/getValueColumn/{produtoId}/{coluna}', 'getValueColumn');
    Route::get('/produtosdivulgacoes/existsValueColumn/{produtoId}/{coluna}', 'existsValueColumn');
    Route::get('/produtosdivulgacoes/exists/{produtoId}', 'exists');
});

Route::controller(TiposUsersController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/tiposusers', 'index');
    Route::post('/tiposusers', 'store');
    Route::get('/tiposusers/{id}', 'show');
    Route::put('/tiposusers/{id}', 'update');
    Route::delete('/tiposusers/{id}', 'destroy');
});

Route::controller(UsersController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/users', 'index');
    Route::post('/users', 'store');
    Route::get('/users/{id}', 'show');
    Route::put('/users/{id}', 'update');
    Route::delete('/users/{id}', 'destroy');
});

Route::controller(VendasController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/vendas', 'index')->middleware('permissoes:vendas.listar|vendas.seus');
    Route::post('/vendas', 'store')->middleware('permissoes:vendas.criar|vendas.seus');
    Route::get('/vendas/{id}', 'show')->middleware('permissoes:vendas.detalhar|vendas.seus');
    Route::put('/vendas/{id}', 'update')->middleware('permissoes:vendas.editar|vendas.seus');
    Route::delete('/vendas/{id}', 'destroy')->middleware('permissoes:vendas.deletar|vendas.seus');
});
