<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\DebugController;

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

Route::get('/', [CounterController::class, 'index'])->name('counter.index');

Route::post('/increment', [CounterController::class, 'increment'])->name('counter.increment');
Route::post('/reset', [CounterController::class, 'reset'])->name('counter.reset');

Route::get('/debug/session', [DebugController::class, 'showSession'])->name('debug.session');
