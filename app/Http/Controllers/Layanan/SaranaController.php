<?php

namespace App\Http\Controllers\Layanan;

use App\Http\Controllers\Controller;
use App\Models\LaporanSarana;
use Illuminate\Http\Request;

class SaranaController extends Controller
{
    public function index()
    {
        $riwayat = LaporanSarana::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('layanan.sarana', compact('riwayat'));
    }

    public function kirim(Request $request)
    {
        $request->validate([
            'kategori'           => 'required|string',
            'nama_aset'          => 'required|string',
            'deskripsi'          => 'required|string',
            'prioritas'          => 'required|in:rendah,sedang,tinggi',
            'tanggal_diperlukan' => 'nullable|date',
            'foto'               => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:5120',
        ]);

        $foto_path = null;
        if ($request->hasFile('foto')) {
            $foto_path = $request->file('foto')->store('layanan/sarana', 'public');
        }

        LaporanSarana::create([
            'user_id'            => auth()->id(),
            'kategori'           => $request->kategori,
            'nama_aset'          => $request->nama_aset,
            'deskripsi'          => $request->deskripsi,
            'prioritas'          => $request->prioritas,
            'tanggal_diperlukan' => $request->tanggal_diperlukan,
            'foto'               => $foto_path,
            'status'             => 'diproses',
        ]);

        return redirect()
            ->route('layanan.sarana', ['tab' => 'riwayat'])
            ->with('success', 'Laporan sarana & prasarana berhasil dikirim!');
    }
}