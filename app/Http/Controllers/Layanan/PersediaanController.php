<?php

namespace App\Http\Controllers\Layanan;

use App\Http\Controllers\Controller;
use App\Models\PermintaanPersediaan;
use App\Models\StokPersediaan;
use Illuminate\Http\Request;

class PersediaanController extends Controller
{
    public function index()
    {
        $riwayat = PermintaanPersediaan::where('user_id', auth()->id())
            ->latest()
            ->get();

        $stok = StokPersediaan::orderBy('kategori')
            ->orderBy('nama_barang')
            ->get();

        return view('layanan.persediaan', compact('riwayat', 'stok'));
    }

    public function kirim(Request $request)
    {
        $request->validate([
            'kategori'           => 'required|string',
            'nama_barang'        => 'required|string',
            'jumlah'             => 'required|integer|min:1',
            'satuan'             => 'required|string',
            'keperluan'          => 'required|string',
            'tanggal_diperlukan' => 'nullable|date',
        ]);

        PermintaanPersediaan::create([
            'user_id'            => auth()->id(),
            'kategori'           => $request->kategori,
            'nama_barang'        => $request->nama_barang,
            'jumlah'             => $request->jumlah,
            'satuan'             => $request->satuan,
            'keperluan'          => $request->keperluan,
            'tanggal_diperlukan' => $request->tanggal_diperlukan,
            'status'             => 'diproses',
        ]);

        return redirect()
            ->route('layanan.persediaan', ['tab' => 'riwayat'])
            ->with('success', 'Permintaan persediaan berhasil dikirim!');
    }
}