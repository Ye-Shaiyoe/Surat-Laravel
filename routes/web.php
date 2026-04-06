<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SuratController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\TemplateSuratController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\NotifikasiController;
use App\Http\Controllers\User\SuratController as UserSurat;
use App\Http\Controllers\User\TemplateController as UserTemplateController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ===== USER =====
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');

    Route::get('/notifikasi/{id}/baca', [NotifikasiController::class, 'read'])->name('user.notif.read');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'readAll'])->name('user.notif.readAll');
    Route::get('/template', [UserTemplateController::class, 'index'])->name('user.template.index');
});

Route::prefix('surat')->name('user.surat.')->group(function () {
    Route::get('/',          [UserSurat::class, 'index'])->name('index');
    Route::get('/ajukan',    [UserSurat::class, 'create'])->name('create');
    Route::post('/ajukan',   [UserSurat::class, 'store'])->name('store');
    Route::get('/{surat}',   [UserSurat::class, 'show'])->name('show');
});


// ===== ADMIN =====
Route::prefix('Admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function () {

    Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/Surat', [SuratController::class, 'index'])->name('surat.index');
    Route::get('/Surat/{surat}', [SuratController::class, 'show'])->name('surat.show');
    Route::post('/Surat/{surat}/setujui', [SuratController::class, 'setujui'])->name('surat.setujui');
    Route::post('/Surat/{surat}/tolak', [SuratController::class, 'tolak'])->name('surat.tolak');

    Route::get('/Laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/Laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    Route::get('/Template', [TemplateSuratController::class, 'index'])->name('template.index');
    Route::post('/Template', [TemplateSuratController::class, 'store'])->name('template.store');
    Route::delete('/Template', [TemplateSuratController::class, 'destroy'])->name('template.destroy');

    // Users (placeholder dulu, nanti diisi)
    Route::redirect('/Users', '/Admin/Dashboard')->name('users.index');
});

// ===== PROFILE (Breeze) =====
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';