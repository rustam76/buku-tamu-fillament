<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormBukuTamuController;

Route::get('/', [FormBukuTamuController::class, 'index']);
Route::post('/form-buku-tamu', [FormBukuTamuController::class, 'store'])->name('form-buku.store');


Route::get('/login', function () {
   return redirect('admin/login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
