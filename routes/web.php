<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;

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
    
    Route::get('/boards/{board}', [BoardController::class, 'show'])->name('boards.show');
    Route::post('/boards/{board}/lists', [BoardController::class, 'storeList'])->name('lists.store');
    Route::delete('/lists/{list}', [BoardController::class, 'destroyList'])->name('lists.destroy');
    Route::post('/lists/{list}/cards', [BoardController::class, 'storeCard'])->name('cards.store');
    Route::post('/boards/reorder', [BoardController::class, 'updatePositions'])->name('boards.reorder');
    Route::delete('/cards/{card}', [BoardController::class, 'destroyCard'])->name('cards.destroy');
});

require __DIR__.'/auth.php';