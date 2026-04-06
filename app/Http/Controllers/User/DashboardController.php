<?php
// =============================================
// app/Http/Controllers/User/DashboardController.php
// =============================================

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        if ($userId === null) {
            abort(403);
        }

        $totalSurat   = Surat::where('user_id', $userId)->count();
        $suratSelesai = Surat::where('user_id', $userId)->where('status', 'selesai')->count();
        $suratProses  = Surat::where('user_id', $userId)->where('status', 'proses')->count();
        $suratDitolak = Surat::where('user_id', $userId)->where('status', 'ditolak')->count();

        // Surat terbaru + tahapan untuk tracking
        $suratTerbaru = Surat::where('user_id', $userId)
                             ->with(['tahapans.diprosesByUser'])
                             ->latest()
                             ->limit(5)
                             ->get();

        // Surat aktif untuk SLA bar
        $suratAktif = Surat::where('user_id', $userId)
                           ->where('status', 'proses')
                           ->latest()
                           ->limit(3)
                           ->get();

        // Template surat dari storage
        /** @var FilesystemAdapter $publicDisk */
        $publicDisk = Storage::disk('public');
        $templates = collect($publicDisk->files('templates'))
            ->map(fn($path) => [
                'nama' => basename($path),
                'url'  => $publicDisk->url($path),
            ])
            ->values();

        return view('dashboard', compact(
            'totalSurat', 'suratSelesai', 'suratProses', 'suratDitolak',
            'suratTerbaru', 'suratAktif', 'templates'
        ));
    }
}