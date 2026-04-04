<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanSarana extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori',
        'nama_aset',
        'deskripsi',
        'prioritas',
        'tanggal_diperlukan',
        'foto',
        'status',
    ];
}