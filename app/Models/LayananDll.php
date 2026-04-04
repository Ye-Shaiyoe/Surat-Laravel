<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LayananDll extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul',
        'deskripsi',
        'status', // misal: diproses, selesai, dsb
    ];
}