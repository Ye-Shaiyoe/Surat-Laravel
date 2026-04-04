<?php

namespace App\Http\Controllers\Persuratan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        return view('persuratan.laporan');
    }

    public function export()
    {
        return "Export laporan (sementara)";
    }
}