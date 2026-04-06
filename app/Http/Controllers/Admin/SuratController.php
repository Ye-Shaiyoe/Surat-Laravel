<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\SuratTahapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SuratStatusNotification;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::with('user')->latest();

        // Filter jenis
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tahap
        if ($request->filled('tahap')) {
            $query->where('tahap_sekarang', $request->tahap);
        }

        // Search judul
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $surats = $query->paginate(15)->withQueryString();
        return view('admin.surat.index', compact('surats'));
    }

    public function show(Surat $surat)
    {
        $surat->load(['user', 'tahapans.diprosesByUser']);
        return view('admin.surat.show', compact('surat'));
    }

    public function setujui(Request $request, Surat $surat)
    {
        $request->validate([
            'catatan'       => 'nullable|string|max:500',
            'nomor_surat'   => 'nullable|string|max:100', // hanya diisi di tahap 5
        ]);

        // Tandai tahap sekarang selesai
        SuratTahapan::where('surat_id', $surat->id)
            ->where('tahap', $surat->tahap_sekarang)
            ->update([
                'status'        => 'selesai',
                'diproses_oleh' => Auth::id(),
                'catatan'       => $request->catatan,
                'selesai_pada'  => now(),
            ]);

        $tahapBerikutnya = $surat->tahap_sekarang + 1;

        if ($tahapBerikutnya > 10) {
            // Semua tahap selesai
            $surat->update(['status' => 'selesai', 'tahap_sekarang' => 10]);
        } else {
            // Maju ke tahap berikutnya
            $updateData = ['tahap_sekarang' => $tahapBerikutnya];

            // Simpan nomor surat jika tahap penomoran (tahap 5)
            if ($surat->tahap_sekarang === 5 && $request->filled('nomor_surat')) {
                $updateData['nomor_surat']    = $request->nomor_surat;
                $updateData['tanggal_surat']  = now()->toDateString();
            }

            $surat->update($updateData);

            // Set tahap berikutnya jadi 'proses'
            SuratTahapan::where('surat_id', $surat->id)
                ->where('tahap', $tahapBerikutnya)
                ->update(['status' => 'proses']);
        }

        return redirect()->route('admin.surat.show', $surat)
                         ->with('success', 'Surat berhasil disetujui dan maju ke tahap berikutnya.');
    }

    public function tolak(Request $request, Surat $surat)
    {
        $request->validate([
            'catatan' => 'required|string|max:500',
        ]);

        SuratTahapan::where('surat_id', $surat->id)
            ->where('tahap', $surat->tahap_sekarang)
            ->update([
                'status'        => 'ditolak',
                'diproses_oleh' => Auth::id(),
                'catatan'       => $request->catatan,
                'selesai_pada'  => now(),
            ]);

        $surat->update(['status' => 'ditolak']);

        // Di Method Setuju()
        $surat->user->notify(new SuratStatusNotification(
            surat: $surat,
            type: 'success',
            title: 'Surat maju ke tahap berikutnya',
            message: "Surat \"{$surat->judul}\" sudah diverifikasi dan lanjut ke tahap {$surat->tahap_sekarang}.",
        ));

        // Di method tolak()
        $surat->user->notify(new SuratStatusNotification(
            surat: $surat,
            type: 'danger',
            title: 'Surat ditolak',
            message: "Surat \"{$surat->judul}\" ditolak. Alasan: {$request->catatan}",
        ));

        return redirect()->route('admin.surat.index')
                         ->with('success', 'Surat telah ditolak dan pengusul akan diberitahu.');
    }
}
