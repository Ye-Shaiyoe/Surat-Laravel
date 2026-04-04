<?php

namespace App\Http\Controllers\Persuratan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormatController extends Controller
{
    public function index()
    {
        return view('persuratan.format');
    }
}