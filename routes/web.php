<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ProfileController;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

/** Days route */
Route::resource('days', App\Http\Controllers\DayController::class)->only('index', 'create', 'store', 'show');

/** Games routes */
Route::resource('games', GameController::class)->except('show', 'destroy');


/** Tables routes */
Route::get('table/{day}/create', [TableController::class, 'create'])->name('table.create');
Route::post('table/{day}/create', [TableController::class, 'store'])->name('table.store');
Route::get('table/{table}/subscribe/{user}', [TableController::class, 'subscribe'])->name('table.subscribe');
Route::get('table/{table}/unsubscribe/{user}', [TableController::class, 'unSubscribe'])->name('table.unsubscribe');
