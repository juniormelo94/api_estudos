<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LogsController;
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

Route::controller(LogsController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/logs', 'index');
    Route::get('/logs/{id}', 'show');
    Route::delete('/logs/{id}', 'destroy');
});

Route::controller(UsersController::class)->middleware('auth:sanctum')->group(function () {
    Route::get('/users', 'index');
    Route::post('/users', 'store');
    Route::get('/users/{id}', 'show');
    Route::put('/users/{id}', 'update');
    Route::delete('/users/{id}', 'destroy');
});
