<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register',    [AuthController::class, 'register']);
Route::post('/login',       [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/categories',               [CategoryController::class, 'index']);
    Route::post('/categories',              [CategoryController::class, 'store']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

    Route::get('/expenses/summary',               [ExpenseController::class, 'summary']);
    Route::get('/expenses',              [ExpenseController::class, 'index']);
    Route::post('/expenses',             [ExpenseController::class, 'store']);
    Route::put('/expenses/{expense}',    [ExpenseController::class, 'update']);
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy']);
});
