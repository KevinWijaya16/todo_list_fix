<?php

use App\Http\Controllers\TodoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TodoController::class, 'index']);
Route::post('/store', [TodoController::class, 'store']);
Route::patch('/edit/{todo}', [TodoController::class, 'update']);
Route::delete('/delete/{todo}', [TodoController::class, 'destroy']);
Route::patch('/complete/{id}', [TodoController::class, 'toggleComplete']);




