<?php
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Ubah route dashboard bawaan Breeze menjadi ini
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [BoardController::class, 'index'])->name('dashboard');
    
    // Resource untuk Board
    Route::post('/boards', [BoardController::class, 'store'])->name('boards.store');
    Route::get('/boards/{board}', [BoardController::class, 'show'])->name('boards.show');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';