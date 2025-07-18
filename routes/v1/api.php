<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('api')->group(function() {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });
    
    Route::prefix('v1')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
            Route::post('login', [\App\Http\Controllers\Api\AuthController::class, 'login'])->name('login');
            Route::middleware('auth:sanctum')->post('logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
        });
    
        Route::middleware(['contest.access'])->group(function () {
            Route::get('contests', [\App\Http\Controllers\Api\ContestController::class, 'index']);
            Route::get('contests/{contest}', [\App\Http\Controllers\Api\ContestController::class, 'show']);
            
            Route::middleware('auth:sanctum')->group(function () {
                Route::post('contests/{contest}/participate', [\App\Http\Controllers\Api\ParticipationController::class, 'store']);
                Route::post('contests/{contest}/submit', [\App\Http\Controllers\Api\ParticipationController::class, 'update']);
                Route::get('leaderboard/{contest}', [\App\Http\Controllers\Api\LeaderboardController::class, 'show']);
            });
        });
    
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('history', [\App\Http\Controllers\Api\HistoryController::class, 'index']);
            Route::get('prizes', [\App\Http\Controllers\Api\PrizeController::class, 'index']);
        });
    
        Route::middleware(['auth:sanctum','admin'])->prefix('admin')->group(function () {
            Route::get('users', [\App\Http\Controllers\Api\AdminUserController::class, 'index']);
            Route::patch('users/{user}', [\App\Http\Controllers\Api\AdminUserController::class, 'update']);
            Route::post('users', [\App\Http\Controllers\Api\AdminUserController::class, 'store']);
            // contest create
            Route::post('contests', [\App\Http\Controllers\Api\ContestController::class, 'store']);
            Route::post('contests/{contest}/questions', [\App\Http\Controllers\Api\AdminQuestionController::class, 'store']);
            Route::patch('questions/{question}', [\App\Http\Controllers\Api\AdminQuestionController::class, 'update']);
            Route::delete('questions/{question}', [\App\Http\Controllers\Api\AdminQuestionController::class, 'destroy']);
            Route::get('prizes', [\App\Http\Controllers\Api\PrizeController::class, 'index']);
        });
    });
});
