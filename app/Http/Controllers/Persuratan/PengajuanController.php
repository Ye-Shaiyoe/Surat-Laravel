<?php

namespace App\Http\Controllers\Persuratan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index()
    {
        return view('persuratan.pengajuan');
    }

    public function kirim(Request $request)
    {
        // sementara
        return back()->with('success', 'Pengajuan dikirim');
    }

    public function tracking($id)
    {
        return "Tracking ID: " . $id;
    }
}