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
use App\Http\Controllers\NotificationApiController;

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
    Route::get('/Surat/{surat}/preview/{tipe}', [SuratController::class, 'preview'])->name('surat.preview');
    Route::get('/Surat/{surat}/download/{tipe}', [SuratController::class, 'download'])->name('surat.download');
    Route::post('/Surat/{surat}/setujui', [SuratController::class, 'setujui'])->name('surat.setujui');
    Route::post('/Surat/{surat}/tolak', [SuratController::class, 'tolak'])->name('surat.tolak');

    Route::get('/Laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/Laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

    Route::get('/Chart', [\App\Http\Controllers\Admin\ChartController::class, 'index'])->name('chart.index');
    Route::get('/Chart/data', [\App\Http\Controllers\Admin\ChartController::class, 'data'])->name('chart.data');

    Route::get('/Template', [TemplateSuratController::class, 'index'])->name('template.index');
    Route::post('/Template', [TemplateSuratController::class, 'store'])->name('template.store');
    Route::delete('/Template', [TemplateSuratController::class, 'destroy'])->name('template.destroy');

    // Users / Pegawai
    Route::get('/Users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/Users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
    Route::delete('/Users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
});

Route::middleware(['auth', 'verified'])->prefix('notif')->name('notif.')->group(function () {
    Route::get('/poll',           [NotificationApiController::class, 'poll'])       ->name('poll');
    Route::post('/read/{id}',     [NotificationApiController::class, 'markRead'])   ->name('read');
    Route::post('/read-all',      [NotificationApiController::class, 'markAllRead'])->name('readAll');
    Route::post('/delete/{id}',   [NotificationApiController::class, 'destroy'])    ->name('delete');
    Route::post('/delete-all',    [NotificationApiController::class, 'destroyAll']) ->name('deleteAll');
});

// ===== PROFILE (Breeze) =====
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';