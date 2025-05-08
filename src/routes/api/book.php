<?php

use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::prefix('book')->group(function () {
    Route::get('/get', [BookController::class, 'index'])->name('book.index');
    Route::get('/get/{id}', [BookController::class, 'show'])->name('book.show');
    Route::get('/search', [BookController::class, 'search'])->name('book.search');
    Route::post('/create', [BookController::class, 'store'])->name('book.store');
    Route::patch('/update/{id}', [BookController::class, 'update'])->name('book.update');
    Route::delete('/delete/{id}', [BookController::class, 'destroy'])->name('book.destroy');
});
