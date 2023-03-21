<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\HTTP\Controllers\AuthController;
use App\HTTP\Controllers\UserController;
use App\HTTP\Controllers\RollDiceController;

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

Route::middleware('auth:api')->group( function () {
    Route::post('/logout',[AuthController::class, 'logout']);
    //Route::get('/profile',[UserController::class, 'show']);
    Route::put('/players/{id}',[UserController::class, 'rename']);
    Route::get('/players/{id}/games',[RollDiceController::class, 'index']);
    Route::post('/players/{id}/games',[RollDiceController::class, 'store']);
    Route::delete('/players/{id}/games',[RollDiceController::class, 'destroy']);
   
});
Route::middleware(['auth:api','isAdmin'])->group( function () {
    Route::get('/players/ranking',[UserController::class, 'ranking']);
    Route::get('/players/ranking/loser',[UserController::class, 'last']);
    Route::get('/players/ranking/winner',[UserController::class, 'first']);
    Route::get('/players',[UserController::class, 'index']);
});
Route::post('/players',[AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']);

   