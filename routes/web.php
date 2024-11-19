<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardGameController;
use App\Http\Controllers\GameRoomController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('/game/lobby');
});

//Route::get('/', [CardGameController::class, 'index'])->name('home');
Route::post('/shuffle', [CardGameController::class, 'shuffle'])->name('shuffle');
Route::post('/deal', [CardGameController::class, 'deal'])->name('deal'); // Rota que direciona para players.blade.php
Route::post('/determineWinner', [CardGameController::class, 'determineWinner'])->name('determineWinner');
Route::post('/selectTrufo', [CardGameController::class, 'selectTrufo'])->name('selectTrufo');

// Route::get('/game/create', [GameRoomController::class, 'create'])->name('game.create'); // Criar sala
// Route::post('/game/join', [GameRoomController::class, 'join'])->name('game.join');    // Entrar na sala
// Route::get('/game/{roomId}', [GameRoomController::class, 'view'])->name('game.view'); // Ver sala
// Route::post('/game/{roomId}/start', [GameRoomController::class, 'start'])->name('game.start'); // Iniciar jogo

Route::get('/game/create', [GameRoomController::class, 'create'])->name('game.create'); // Criar sala
Route::post('/game/join', [GameRoomController::class, 'join'])->name('game.join');    // Entrar na sala
Route::get('/game/{roomId}', [GameRoomController::class, 'view'])->name('game.view'); // Ver sala
Route::get('/game/{roomId}/start', [GameRoomController::class, 'start'])->name('game.start'); // Iniciar jogo
Route::get('/game/{roomId}/play', [GameRoomController::class, 'play'])->name('game.play'); // Exibir mesa
