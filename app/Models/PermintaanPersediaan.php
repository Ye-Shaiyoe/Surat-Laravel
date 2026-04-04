<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanPersediaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_barang',
        'jumlah',
        'deskripsi',
        'status', // misal: 'diproses', 'selesai', dsb
    ];
}