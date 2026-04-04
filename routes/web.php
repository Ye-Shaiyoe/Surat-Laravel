<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\Surat;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//User
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin routes
Route::prefix('Admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function () {
    Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::redirect('/Surat', '/Admin/Dashboard')->name('surat.index');
    Route::get('/Surat/{surat}', function (Surat $surat) {
        return redirect()->route('admin.dashboard');
    })->name('surat.show');

    Route::redirect('/Laporan', '/Admin/Dashboard')->name('laporan.index');
    Route::redirect('/Template', '/Admin/Dashboard')->name('template.index');
    Route::redirect('/Users', '/Admin/Dashboard')->name('users.index');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
