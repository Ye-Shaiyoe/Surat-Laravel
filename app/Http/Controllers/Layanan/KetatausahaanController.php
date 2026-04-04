<?php

namespace App\Http\Controllers\Layanan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LayananKetatausahaan;

class KetatausahaanController extends Controller
{
    public function index()
    {
        $items = LayananKetatausahaan::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('layanan.ketatausahaan', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        LayananKetatausahaan::create([
            'user_id' => auth()->id(),
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'status' => 'aktif',
        ]);

        return redirect()->route('layanan.ketatausahaan')->with('success', 'Data berhasil ditambahkan');
    }
}