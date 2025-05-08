<?php

use App\Http\Controllers\AuthorController;
use Illuminate\Support\Facades\Route;

Route::prefix('author')->group(function () {
    Route::get('/get', [AuthorController::class, 'index'])->name('author.index');
    Route::get('/get/{id}', [AuthorController::class, 'show'])->name('author.show');
    Route::post('/create', [AuthorController::class, 'store'])->name('author.store');
    Route::patch('/update/{id}', [AuthorController::class, 'update'])->name('author.update');
    Route::delete('/delete/{id}', [AuthorController::class, 'destroy'])->name('author.destroy');
});
