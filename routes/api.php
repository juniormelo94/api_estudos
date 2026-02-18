<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DisciplinasController;
use App\Http\Controllers\Api\LogsController;
use App\Http\Controllers\Api\ModelosProvasController;
use App\Http\Controllers\Api\ProvasController;
use App\Http\Controllers\Api\QuestoesController;
use App\Http\Controllers\Api\UsersController;

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

Route::controller(DisciplinasController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/disciplinas', 'index');
    Route::post('/disciplinas', 'store');
    Route::get('/disciplinas/{id}', 'show');
    Route::put('/disciplinas/{id}', 'update');
    Route::delete('/disciplinas/{id}', 'destroy');
});

Route::controller(LogsController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/logs', 'index');
    Route::get('/logs/{id}', 'show');
    Route::delete('/logs/{id}', 'destroy');
});

Route::controller(ModelosProvasController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/modelosprovas', 'index');
    Route::post('/modelosprovas', 'store');
    Route::get('/modelosprovas/{id}', 'show');
    Route::put('/modelosprovas/{id}', 'update');
    Route::delete('/modelosprovas/{id}', 'destroy');
});

Route::controller(ProvasController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/provas', 'index');
    Route::post('/provas', 'store');
    Route::get('/provas/{id}', 'show');
    Route::put('/provas/{id}', 'update');
    Route::delete('/provas/{id}', 'destroy');
});

Route::controller(QuestoesController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/questoes', 'index');
    Route::post('/questoes', 'store');
    Route::get('/questoes/{id}', 'show');
    Route::put('/questoes/{id}', 'update');
    Route::delete('/questoes/{id}', 'destroy');
});

Route::controller(UsersController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/users', 'index');
    Route::post('/users', 'store');
    Route::get('/users/{id}', 'show');
    Route::put('/users/{id}', 'update');
    Route::delete('/users/{id}', 'destroy');
});
