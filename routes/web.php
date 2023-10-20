<?php

use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TableController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /** Days route */
    Route::resource('days', App\Http\Controllers\DayController::class);

    /** Games routes */
    Route::resource('games', GameController::class)->except('show', 'destroy');
    Route::get('games/search', [GameController::class, 'searchByCategory']);

    /** Tables routes */
    Route::get('table/{day}/create', [TableController::class, 'create'])->name('table.create');
    Route::post('table/{day}/create', [TableController::class, 'store'])->name('table.store');
    Route::get('table/{table}/edit', [TableController::class, 'edit'])->name('table.edit');
    Route::patch('table/{table}', [TableController::class, 'update'])->name('table.update');
    Route::get('table/{table}/subscribe/{user}', [TableController::class, 'subscribe'])->name('table.subscribe');
    Route::get('table/{table}/unsubscribe/{user}', [TableController::class, 'unSubscribe'])->name('table.unsubscribe');
    Route::delete('table/{table}/delete/', [TableController::class, 'destroy'])->name('table.delete');
});

require __DIR__.'/auth.php';
