<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\TopicsController;
use App\Http\Controllers\QuestionsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [UserController::class, 'store']); // 👈 public route (no token needed)
Route::post('/login', [AuthController::class, 'login']);   // 👈 public route

// 🔒 Protected routes (require token)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/me', [UserController::class,'me']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('/subjects', SubjectsController::class);
    Route::apiResource('/topics', TopicsController::class);
    Route::apiResource('/questions', QuestionsController::class);
});




