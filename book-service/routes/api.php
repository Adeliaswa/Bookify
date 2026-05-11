<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::get('/products', [BookController::class, 'index']);
Route::post('/products', [BookController::class, 'store']);
Route::get('/products/{id}', [BookController::class, 'show']);
Route::put('/products/{id}', [BookController::class, 'update']);
Route::delete('/products/{id}', [BookController::class, 'destroy']);