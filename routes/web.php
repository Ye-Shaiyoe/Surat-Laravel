<?php

<<<<<<< HEAD
use App\Http\Controllers\ProfileController;
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
    Route::get('/Dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
=======
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Persuratan\FormatController;
use App\Http\Controllers\Persuratan\PengajuanController;
use App\Http\Controllers\Persuratan\LaporanController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Layanan\KetatausahaanController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Layanan\SaranaController;
use App\Http\Controllers\Layanan\PersediaanController;
use App\Http\Controllers\Layanan\DllController;

Route::middleware('guest')->group(function () {
    // Tampilkan halaman register
    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->name('register');

    // Proses register
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::middleware(['auth'])->group(function () {



    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Persuratan
    Route::prefix('persuratan')->name('persuratan.')->group(function () {

        // Format
        Route::get('/format', [FormatController::class, 'index'])
            ->name('format');

        // Pengajuan
        Route::get('/pengajuan', [PengajuanController::class, 'index'])
            ->name('pengajuan');
        Route::post('/pengajuan/kirim', [PengajuanController::class, 'kirim'])
            ->name('pengajuan.kirim');
        Route::get('/pengajuan/{id}/tracking', [PengajuanController::class, 'tracking'])
            ->name('pengajuan.tracking');

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])
            ->name('laporan');
        Route::get('/laporan/export', [LaporanController::class, 'export'])
            ->name('laporan.export');
    });

    // Layanan Ketatausahaan
Route::prefix('layanan')->name('layanan.')->group(function () {

    // Ketatausahaan
    Route::get('/ketatausahaan', [KetatausahaanController::class, 'index'])
        ->name('ketatausahaan');
    Route::post('/ketatausahaan/kirim', [KetatausahaanController::class, 'kirim'])
        ->name('ketatausahaan.kirim');

    // Sarana & Prasarana
    Route::get('/sarana', [SaranaController::class, 'index'])
        ->name('sarana');
    Route::post('/sarana/kirim', [SaranaController::class, 'kirim'])
        ->name('sarana.kirim');

    // Persediaan
    Route::get('/persediaan', [PersediaanController::class, 'index'])
        ->name('persediaan');
    Route::post('/persediaan/kirim', [PersediaanController::class, 'kirim'])
        ->name('persediaan.kirim');

    // Dll
    Route::get('/dll', [DllController::class, 'index'])
        ->name('dll');
    Route::post('/dll/kirim', [DllController::class, 'kirim'])
        ->name('dll.kirim');
});

    Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');
});

});
>>>>>>> ae1b02b (Add full Laravel project fresh)
