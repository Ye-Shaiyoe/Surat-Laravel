<?php

namespace App\Http\Controllers\Layanan;

use App\Http\Controllers\Controller;
use App\Models\LayananDll;
use Illuminate\Http\Request;

class DllController extends Controller
{
    public function index()
    {
        $riwayat = LayananDll::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('layanan.dll', compact('riwayat'));
    }

    public function kirim(Request $request)
    {
        $request->validate([
            'jenis'          => 'required|string',
            'tanggal_mulai'  => 'required|date',
            'tanggal_selesai'=> 'nullable|date|after_or_equal:tanggal_mulai',
            'keterangan'     => 'required|string',
            'dokumen'        => 'nullable|file|mimes:pdf,docx,doc,jpg,png|max:5120',
        ]);

        $dokumen_path = null;
        if ($request->hasFile('dokumen')) {
            $dokumen_path = $request->file('dokumen')->store('layanan/dll', 'public');
        }

        LayananDll::create([
            'user_id'         => auth()->id(),
            'jenis'           => $request->jenis,
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan'      => $request->keterangan,
            'dokumen'         => $dokumen_path,
            'status'          => 'diproses',
        ]);

        return redirect()
            ->route('layanan.dll', ['tab' => 'riwayat'])
            ->with('success', 'Pengajuan berhasil dikirim!');
    }
}