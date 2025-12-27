<?php
use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [BoardController::class, 'index'])->name('dashboard');
    
    Route::post('/boards', [BoardController::class, 'store'])->name('boards.store');
    Route::get('/boards/{board}', [BoardController::class, 'show'])->name('boards.show');

    Route::post('/lists', [BoardController::class, 'storeList'])->name('lists.store');
    Route::patch('/lists/reorder', [BoardController::class, 'reorderLists'])->name('lists.reorder');

    Route::delete('/cards/{card}', [CardController::class, 'destroy'])->name('cards.destroy');
    Route::delete('/lists/{list}', [BoardController::class, 'destroyList'])->name('lists.destroy');

    Route::post('/cards', [CardController::class, 'store'])->name('cards.store');
    Route::get('/cards/{card}', [CardController::class, 'show'])->name('cards.show');
    Route::put('/cards/{card}', [CardController::class, 'update'])->name('cards.update');
    Route::patch('/cards/{card}/move', [CardController::class, 'move'])->name('cards.move');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';