<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DayController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /** Days route */
    Route::resource('days', DayController::class);
    Route::get('/days/{day}/warning', [DayController::class, 'edit_warning'])->name('days.warning');
    Route::patch('/days/{day}/confirm_warning', [DayController::class, 'confirm_warning'])->name('days.confirm_warning');
    Route::get('/days/{day}/cancel', [DayController::class, 'edit_cancel'])->name('days.cancel');
    Route::patch('/days/{day}/confirm_cancel', [DayController::class, 'confirm_cancel'])->name('days.confirm_cancel');

    /** Games routes */
    Route::resource('games', GameController::class)->except('show', 'destroy');
    Route::get('games/search', [GameController::class, 'searchByCategory']);

    /** Tables routes */
    Route::get('table/{day}/create', [TableController::class, 'create'])->name('table.create');
    Route::post('table/{day}/create', [TableController::class, 'store'])->name('table.store');
    Route::get('table/{table}/edit', [TableController::class, 'edit'])->name('table.edit');
    Route::patch('table/{table}', [TableController::class, 'update'])->name('table.update');
    Route::delete('table/{table}/delete/', [TableController::class, 'destroy'])->name('table.delete');

    /** Subscribing routes */
    Route::get('table/{table}/subscribe', [UserController::class, 'subscribe'])->name('table.subscribe');
    Route::get('table/{table}/unsubscribe', [UserController::class, 'unSubscribe'])->name('table.unsubscribe');
    Route::get('event/{event}/subscribe', [EventController::class, 'subscribe'])->name('event.subscribe');
    Route::get('event/{event}/unsubscribe', [EventController::class, 'unSubscribe'])->name('event.unsubscribe');

    /** Events routes */
    Route::get('event/{day}/create', [EventController::class, 'create'])->name('event.create');
    Route::post('event/{day}/store', [EventController::class, 'store'])->name('event.store');
    Route::get('event/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::put('event/{event}', [EventController::class, 'update'])->name('event.update');
    Route::delete('event/{event}', [EventController::class, 'destroy'])->name('event.destroy');
});

require __DIR__.'/auth.php';
