<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ScadenzarioController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth', [AuthController::class, 'auth']);
Route::middleware('auth:sanctum')->get('/auth/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/auth/generate/2fa', [AuthController::class, 'generate2Fa']);

// App
Route::middleware('auth:sanctum')->get('/me', [UserController::class,'me']);
Route::middleware('auth:sanctum')->get('/dashboard', [DashboardController::class,'index']);
Route::middleware('auth:sanctum')->put('scadenzario/{id}/check', [ScadenzarioController::class, 'check']);
Route::middleware('auth:sanctum')->resource('scadenzario', ScadenzarioController::class);
