<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokPersediaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'jumlah',
        'satuan',
        'status', // misal: 'tersedia', 'habis', dsb
    ];
}