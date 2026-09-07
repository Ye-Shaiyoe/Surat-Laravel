<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SuratController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\TemplateSuratController;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\SuratController as UserSurat;
use App\Http\Controllers\User\TemplateController as UserTemplateController;
use App\Http\Controllers\User\StatistikController as UserStatistik;
use App\Http\Controllers\User\SlaMonitoringController as UserSlaMonitoring;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationApiController;
use Illuminate\Http\Request;
use App\Http\Controllers\WelcomeController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Panduan Pengguna (Public — no login required)
Route::get('/panduan', function () {
    return view('panduan');
})->name('panduan');

// Verifikasi Surat (Public)
Route::get('/v/{uuid}', [\App\Http\Controllers\VerifikasiSuratController::class, 'index'])->name('surat.verifikasi');


// ===== USER =====
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');
    Route::get('/dashboard/live-data', [UserDashboard::class, 'liveData'])->name('dashboard.liveData');

    Route::get('/template', [UserTemplateController::class, 'index'])->name('user.template.index');
    Route::get('/template/download/{nama}', [UserSurat::class, 'templateDownload'])->name('user.template.download');
    Route::get('/about', function () {
        return view('user.about.index', ['title' => 'Tentang Aplikasi']);
    })->name('user.about.index');

    Route::get('/faq', [UserDashboard::class, 'faq'])->name('user.faq.index');
    Route::get('/statistik', [UserStatistik::class, 'index'])->name('user.statistik.index');
    Route::get('/monitoring-sla', [UserSlaMonitoring::class, 'index'])->name('user.sla.index');
    Route::get('/agenda', [\App\Http\Controllers\User\AgendaController::class, 'index'])->name('user.agenda.index');
    Route::get('/agenda/events', [\App\Http\Controllers\User\AgendaController::class, 'events'])->name('user.agenda.events');
    Route::get('/notifikasi', [\App\Http\Controllers\User\NotifikasiController::class, 'index'])->name('user.notifikasi.index');
    
    // Activity Log
    Route::prefix('activity-log')->name('user.activity-log.')->group(function () {
        Route::get('/', [\App\Http\Controllers\User\ActivityLogController::class, 'index'])->name('index');
        Route::get('/export', [\App\Http\Controllers\User\ActivityLogController::class, 'export'])->name('export');
        Route::delete('/destroy-all', [\App\Http\Controllers\User\ActivityLogController::class, 'destroyAll'])->name('destroyAll');
        Route::get('/{log}', [\App\Http\Controllers\User\ActivityLogController::class, 'show'])->name('show');
    });
    
    // Aspirasi routes dengan rate limiting
    Route::get('/aspirasi', [\App\Http\Controllers\User\AspirasiController::class, 'index'])->name('user.aspirasi.index');
    Route::post('/aspirasi', [\App\Http\Controllers\User\AspirasiController::class, 'store'])
        ->middleware('throttle:10,1') // Max 10 submissions per minute
        ->name('user.aspirasi.store');
    Route::delete('/aspirasi/{aspirasi}', [\App\Http\Controllers\User\AspirasiController::class, 'destroy'])->name('user.aspirasi.destroy');

    // Fitur Cari Pegawai (Direktori)
    Route::prefix('pegawai')->name('user.pegawai.')->group(function () {
        Route::get('/', [\App\Http\Controllers\User\PegawaiController::class, 'index'])->name('index');
        Route::get('/{user:uuid}', [\App\Http\Controllers\User\PegawaiController::class, 'show'])->name('show');
    });

    Route::prefix('surat')->name('user.surat.')->group(function () {
        Route::get('/', [UserSurat::class, 'index'])->name('index');
        Route::get('/tabel', [UserSurat::class, 'table'])->name('table');
        Route::get('/export-excel', [UserSurat::class, 'exportExcel'])->name('exportExcel');
        Route::get('/ajukan', [UserSurat::class, 'create'])->name('create');
        Route::post('/ajukan', [UserSurat::class, 'store'])
            ->middleware('throttle:5,1') // Maks 5 pengajuan per menit per user
            ->name('store');
        Route::get('/manajemen/file-fisik-surat', [UserSurat::class, 'fileIndex'])->name('file_index');
        Route::get('/{surat}', [UserSurat::class, 'show'])->name('show');
        Route::get('/{surat}/edit', [UserSurat::class, 'edit'])->name('edit');
        Route::patch('/{surat}', [UserSurat::class, 'update'])
            ->middleware('throttle:5,1') // Maks 5 update draft per menit
            ->name('update');
        Route::patch('/{surat}/metadata', [UserSurat::class, 'updateMetadata'])->name('updateMetadata');
        Route::get('/{surat}/preview/{tipe}', [UserSurat::class, 'preview'])->name('preview');
        Route::get('/{surat}/download/{tipe}', [UserSurat::class, 'download'])->name('download');
        Route::post('/{surat}/reupload', [UserSurat::class, 'reuploadFile'])
            ->middleware('throttle:5,1') // Maks 5 reupload per menit
            ->name('reupload');
        Route::post('/{surat}/purge-files', [UserSurat::class, 'purgeFiles'])->name('purgeFiles');
        Route::post('/{surat}/rate', [UserSurat::class, 'rate'])->name('rate');
        Route::delete('/{surat}', [UserSurat::class, 'requestDelete'])->name('requestDelete');
    });
});


// ===== ADMIN =====
Route::prefix('Admin')->middleware(['auth', 'verified', 'admin'])->name('admin.')->group(function () {

    // Role Selection (tanpa middleware admin.role.check, karena ini tempat pilih role)
    Route::get('/Role-Selection', [\App\Http\Controllers\Admin\AdminRoleSelectionController::class, 'show'])->name('role.select');
    Route::post('/Role-Selection', [\App\Http\Controllers\Admin\AdminRoleSelectionController::class, 'store'])->name('role.store');

    // Download routes (tanpa admin.role.check agar binary gak corrupt)
    Route::get('/Surat/{surat}/preview/{tipe}', [\App\Http\Controllers\Admin\SuratController::class, 'preview'])->name('surat.preview');
    Route::get('/Surat/{surat}/download/{tipe}', [\App\Http\Controllers\Admin\SuratController::class, 'download'])->name('surat.download');


    // Dashboard & other routes (dengan middleware admin.role.check)
    Route::middleware(['admin.role.check'])->group(function () {
        Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/Dashboard/live-data', [DashboardController::class, 'liveData'])->name('dashboard.liveData');
        Route::get('/Sidebar/counts', [\App\Http\Controllers\Admin\SidebarController::class, 'counts'])->name('sidebar.counts');

        Route::get('/Surat', [SuratController::class, 'index'])->name('surat.index');
        Route::get('/Semua-Surat', [SuratController::class, 'semua'])->name('surat.semua');
        Route::get('/Surat-Masuk', [SuratController::class, 'masuk'])->name('surat.masuk');
        Route::get('/Surat-Proses', [SuratController::class, 'proses'])->name('surat.proses');
        Route::get('/Surat-Selesai', [SuratController::class, 'selesai'])->name('surat.selesai');
        Route::get('/Surat-Revisi', [SuratController::class, 'revisi'])->name('surat.revisi');
        Route::get('/Surat-Export-Excel', [SuratController::class, 'exportExcel'])->name('surat.exportExcel');
        Route::get('/Surat/{surat}', [SuratController::class, 'show'])->name('surat.show');
        Route::post('/Surat/{surat}/setujui', [SuratController::class, 'setujui'])->name('surat.setujui');
        Route::post('/Surat/{surat}/tolak', [SuratController::class, 'tolak'])->name('surat.tolak');
        Route::post('/Surat/{surat}/upload-file-admin', [SuratController::class, 'uploadFileAdmin'])->name('surat.uploadFileAdmin');
        Route::post('/Surat/delete-request/{deleteRequest}/approve', [SuratController::class, 'approveDelete'])->name('surat.approveDelete');
        Route::post('/Surat/delete-request/{deleteRequest}/reject', [SuratController::class, 'rejectDelete'])->name('surat.rejectDelete');

        Route::get('/Laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/Laporan/export', [LaporanController::class, 'export'])->name('laporan.export');
        Route::get('/Laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.exportExcel');

        Route::get('/Chart', [\App\Http\Controllers\Admin\ChartController::class, 'index'])->name('chart.index');
        Route::get('/Chart/data', [\App\Http\Controllers\Admin\ChartController::class, 'data'])->name('chart.data');

        // Analytics & SLA
        Route::prefix('Analytics')->group(function () {
            Route::get('/SLA', [\App\Http\Controllers\Admin\AnalyticsController::class, 'sla'])->name('analytics.sla');
        });

        // SLA Persurat (Monitoring Durasi & Pemroses Tiap Tahap)
        Route::get('/SLA-Persurat', [\App\Http\Controllers\Admin\SlaController::class, 'index'])->name('sla.index');
        Route::get('/SLA-Persurat/export', [\App\Http\Controllers\Admin\SlaController::class, 'export'])->name('sla.export');

        Route::get('/FAQ', function () {
            return view('admin.faq.index', ['title' => 'FAQ & Bantuan']);
        })->name('faq.index');

        // Bug Report / IT Support
        Route::get('/Bantuan-IT-Support', [\App\Http\Controllers\Admin\BugReportController::class, 'index'])->name('bug-report.index');
        Route::post('/Bantuan-IT-Support', [\App\Http\Controllers\Admin\BugReportController::class, 'store'])->name('bug-report.store');
        Route::delete('/Bantuan-IT-Support/{aspirasi}', [\App\Http\Controllers\Admin\BugReportController::class, 'destroy'])->name('bug-report.destroy');

        // Riwayat Pemrosesan Surat
        Route::get('/Riwayat', [\App\Http\Controllers\Admin\RiwayatController::class, 'index'])->name('riwayat.index');

        Route::get('/Template', [TemplateSuratController::class, 'index'])->name('template.index');
        Route::get('/Template/download/{nama}', [TemplateSuratController::class, 'download'])->name('template.download');
        Route::post('/Template', [TemplateSuratController::class, 'store'])->name('template.store');
        Route::delete('/Template', [TemplateSuratController::class, 'destroy'])->name('template.destroy');

        // Settings Group
        Route::prefix('Settings')->group(function () {
            // Users / Pegawai
            Route::get('/Users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
            Route::post('/Users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
            // Route 'create' eksplisit harus di atas {user} agar tidak tertangkap sebagai wildcard
            Route::get('/Users/create', fn() => redirect()->route('admin.users.index'))->name('users.create');
            Route::get('/Users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
            Route::patch('/Users/{user}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole'])->name('users.updateRole');
            Route::delete('/Users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

            // Log System admin 
            Route::get('/Logs', [\App\Http\Controllers\Admin\LogController::class, 'index'])->name('logs.index');
            Route::get('/Logs/download/{file}', [\App\Http\Controllers\Admin\LogController::class, 'download'])->name('logs.download');
            Route::post('/Logs/clear/{file}', [\App\Http\Controllers\Admin\LogController::class, 'clear'])->name('logs.clear');
            Route::delete('/Logs/delete/{file}', [\App\Http\Controllers\Admin\LogController::class, 'delete'])->name('logs.delete');

            // Manajemen File Fisik Surat
            Route::get('/File-Surat', [\App\Http\Controllers\Admin\FileSuratController::class, 'index'])->name('file.index');
            Route::delete('/File-Surat/{surat}', [\App\Http\Controllers\Admin\FileSuratController::class, 'destroy'])->name('file.destroy');
            Route::post('/File-Surat/mass-delete', [\App\Http\Controllers\Admin\FileSuratController::class, 'massDelete'])->name('file.massDelete');
        });

        // Notifikasi Admin
        Route::get('/Notifikasi', [\App\Http\Controllers\Admin\NotifikasiController::class, 'index'])->name('notifikasi.index');

        // Manajemen Aspirasi
        Route::get('/Aspirasi', [\App\Http\Controllers\Admin\AspirasiController::class, 'index'])->name('aspirasi.index');
        Route::patch('/Aspirasi/{aspirasi}', [\App\Http\Controllers\Admin\AspirasiController::class, 'update'])->name('aspirasi.update');
        Route::post('/Aspirasi/{aspirasi}/read', [\App\Http\Controllers\Admin\AspirasiController::class, 'markAsRead'])->name('aspirasi.read');
        Route::delete('/Aspirasi/{aspirasi}', [\App\Http\Controllers\Admin\AspirasiController::class, 'destroy'])->name('aspirasi.destroy');

        Route::post('/Notifikasi/read/{id}', [\App\Http\Controllers\Admin\NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
        Route::post('/Notifikasi/read-all', [\App\Http\Controllers\Admin\NotifikasiController::class, 'markAllAsRead'])->name('notifikasi.readAll');
        Route::delete('/Notifikasi/{id}', [\App\Http\Controllers\Admin\NotifikasiController::class, 'destroy'])->name('notifikasi.delete');
        Route::delete('/Notifikasi', [\App\Http\Controllers\Admin\NotifikasiController::class, 'destroyAll'])->name('notifikasi.deleteAll');
    });
});

// ===== IT SUPPORT =====
Route::middleware(['auth', 'verified'])->group(function () {
    // Secret route to become IT Support
    Route::get('/become-it-support', [\App\Http\Controllers\ITSupportController::class, 'becomeITSupport'])
        ->name('itsupport.become')
        ->middleware('throttle:3,1');

    Route::middleware(['it_support'])->prefix('IT-Support')->name('itsupport.')->group(function () {
        Route::get('/Dashboard', [\App\Http\Controllers\ITSupportController::class, 'dashboard'])->name('dashboard');
        Route::patch('/Aspirasi/{aspirasi}', [\App\Http\Controllers\ITSupportController::class, 'updateAspirasi'])->name('aspirasi.update');
        
        Route::get('/Notification/Create', [\App\Http\Controllers\ITSupportController::class, 'createNotification'])->name('notification.create');
        Route::post('/Notification', [\App\Http\Controllers\ITSupportController::class, 'storeNotification'])->name('notification.store');
    });
});

Route::middleware(['auth', 'verified'])->prefix('notif')->name('notif.')->group(function () {
    Route::get('/poll', [\App\Http\Controllers\NotificationApiController::class, 'poll'])->name('poll');
    Route::get('/read/{id}', [\App\Http\Controllers\User\NotifikasiController::class, 'read'])->name('read');
    Route::post('/read-all', [\App\Http\Controllers\User\NotifikasiController::class, 'readAll'])->name('readAll');
    Route::post('/mark-read/{id}', [\App\Http\Controllers\User\NotifikasiController::class, 'markAsRead'])->name('markRead');
    Route::post('/delete/{id}', [\App\Http\Controllers\User\NotifikasiController::class, 'destroy'])->name('delete');
    Route::post('/delete-all', [\App\Http\Controllers\User\NotifikasiController::class, 'destroyAll'])->name('deleteAll');
});

// ===== PROFILE (Breeze) =====
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/sessions/{sessionId}', [ProfileController::class, 'revokeSession'])->name('profile.sessions.revoke');
    Route::post('/profile/sessions/revoke-others', [ProfileController::class, 'revokeAllOtherSessions'])->name('profile.sessions.revoke-others');
    Route::patch('/profile/tte', [ProfileController::class, 'updateTte'])->name('profile.tte.update');
});

require __DIR__ . '/auth.php';




Route::prefix('test-errors')->group(function () {
    
    // 1. Akses lewat browser: localhost:8000/test-errors/403
    Route::get('/403', function () {
        return view('errors.403');
    });

    // 2. Akses lewat browser: localhost:8000/test-errors/404
    Route::get('/404', function () {
        return view('errors.404');
    });

    // 3. Akses lewat browser: localhost:8000/test-errors/419
    Route::get('/419', function () {
        return view('errors.419');
    });

    // 4. Akses lewat browser: localhost:8000/test-errors/429
    Route::get('/429', function () {
        return view('errors.429');
    });

    // 5. Akses lewat browser: localhost:8000/test-errors/500
    Route::get('/500', function () {
        return view('errors.500');
    });

    // 6. Akses lewat browser: localhost:8000/test-errors/503
    Route::get('/503', function () {
        return view('errors.503');
    });

    // 7. Akses lewat browser: localhost:8000/test-errors/layout
    Route::get('/layout', function () {
        return view('errors.layout');
    });

});