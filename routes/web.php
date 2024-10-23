<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


use App\Http\Controllers\LeaderboardController;

Route::get('/', [LeaderboardController::class, 'index'])->name('leaderboard.index');
Route::post('/leaderboard/recalculate', [LeaderboardController::class, 'recalculate'])->name('leaderboard.recalculate');
Route::post('/leaderboard/search', [LeaderboardController::class, 'search'])->name('leaderboard.search');
Route::get('/leaderboard/filter', [LeaderboardController::class, 'filter'])->name('leaderboard.filter');
