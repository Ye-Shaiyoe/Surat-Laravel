<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::where('user_id', Auth::id())
                      ->with('tahapans')
                      ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $surats = $query->paginate(10)->withQueryString();

        return view('user.surat.index', compact('surats'));
    }

    public function create()
    {
        /** @var FilesystemAdapter $publicDisk */
        $publicDisk = Storage::disk('public');
        $templates = collect($publicDisk->files('templates'))
            ->map(fn($path) => [
                'nama' => basename($path),
                'url'  => $publicDisk->url($path),
            ])->values();

        return view('user.surat.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'          => 'required|string|max:255',
            'jenis'          => 'required|in:nota_dinas,surat_dinas,surat_keputusan,surat_pernyataan,surat_keterangan',
            'sifat'          => 'required|in:biasa,segera,rahasia',
            'tujuan'         => 'required|string|max:500',
            'file_word'      => 'required|file|mimes:docx,doc|max:10240',
            'file_lampiran'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:20480',
        ]);

        // Upload file
        $fileWord = $request->file('file_word')->store('surat/word', 'public');
        $fileLamp = $request->file('file_lampiran')
                  ? $request->file('file_lampiran')->store('surat/lampiran', 'public')
                  : null;

        // Hitung deadline SLA = 1 hari kerja (skip Sabtu-Minggu)
        $deadline = $this->hitungSLA(now());

        $surat = Surat::create([
            'user_id'       => Auth::id(),
            'judul'         => $request->judul,
            'jenis'         => $request->jenis,
            'sifat'         => $request->sifat,
            'tujuan'        => $request->tujuan,
            'file_word'     => $fileWord,
            'file_lampiran' => $fileLamp,
            'tahap_sekarang'=> 1,
            'status'        => 'proses',
            'deadline_sla'  => $deadline,
        ]);

        // Inisialisasi semua tahapan
        $surat->initTahapan();

        // Set tahap 2 jadi 'proses' (siap diverifikasi arsiparis)
        $surat->tahapans()->where('tahap', 2)->update(['status' => 'proses']);

        return redirect()->route('user.surat.show', $surat)
                         ->with('success', 'Surat berhasil diajukan! Silakan pantau statusnya di bawah.');
    }

    public function show(Surat $surat)
    {
        // Pastikan hanya pemilik yang bisa lihat
        abort_if($surat->user_id !== Auth::id(), 403);

        $surat->load(['tahapans.diprosesByUser']);

        return view('user.surat.show', compact('surat'));
    }

    // Hitung 1 hari kerja (skip Sabtu & Minggu)
    private function hitungSLA(Carbon $dari): Carbon
    {
        $deadline = $dari->copy()->addHours(8); // mulai dari jam kerja
        $hari = 0;
        while ($hari < 1) {
            $deadline->addDay();
            if (!$deadline->isWeekend()) {
                $hari++;
            }
        }
        return $deadline;
    }
}