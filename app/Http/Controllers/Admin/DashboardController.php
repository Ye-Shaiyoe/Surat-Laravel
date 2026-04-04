<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        // Statistik
        $totalBulanIni = Surat::whereMonth('created_at', $bulanIni)
                              ->whereYear('created_at', $tahunIni)
                              ->count();

        $totalSelesai  = Surat::whereMonth('created_at', $bulanIni)
                              ->whereYear('created_at', $tahunIni)
                              ->where('status', 'selesai')->count();

        $totalProses   = Surat::whereMonth('created_at', $bulanIni)
                              ->whereYear('created_at', $tahunIni)
                              ->where('status', 'proses')->count();

        $totalTerlambat = Surat::where('status', 'proses')
                               ->whereNotNull('deadline_sla')
                               ->where('deadline_sla', '<', now())
                               ->count();

        // Antrian: surat yang butuh aksi berdasarkan role user ini
        // Sesuaikan kondisi tahap dengan role masing-masing:
        // Arsiparis    → tahap 2
        // Kasubbag TU  → tahap 3
        // Kabalai      → tahap 4
        // Persuratan   → tahap 5,6,7,8,9,10
        // Admin lihat semua
        $antrian = Surat::where('status', 'proses')
                        ->with('user')
                        ->orderByRaw("CASE WHEN deadline_sla < NOW() THEN 0 ELSE 1 END")
                        ->orderBy('created_at')
                        ->limit(10)
                        ->get();

        // Rekap per jenis surat bulan ini
        $rekapJenis = Surat::whereMonth('created_at', $bulanIni)
                           ->whereYear('created_at', $tahunIni)
                           ->selectRaw('jenis, COUNT(*) as jumlah')
                           ->groupBy('jenis')
                           ->pluck('jumlah', 'jenis');

        // Surat terbaru
        $suratTerbaru = Surat::with('user')
                             ->latest()
                             ->limit(5)
                             ->get();

        // Jumlah antrian untuk badge sidebar (share ke layout)
        $antrianCount = $antrian->count();

        return view('admin.dashboard', compact(
            'totalBulanIni', 'totalSelesai', 'totalProses', 'totalTerlambat',
            'antrian', 'rekapJenis', 'suratTerbaru', 'antrianCount'
        ));
    }
}