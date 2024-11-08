<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardGameController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [CardGameController::class, 'index'])->name('home');
Route::post('/shuffle', [CardGameController::class, 'shuffle'])->name('shuffle');
Route::post('/deal', [CardGameController::class, 'deal'])->name('deal'); // Rota que direciona para players.blade.php
Route::post('/determineWinner', [CardGameController::class, 'determineWinner'])->name('determineWinner');
Route::post('/selectTrufo', [CardGameController::class, 'selectTrufo'])->name('selectTrufo');
