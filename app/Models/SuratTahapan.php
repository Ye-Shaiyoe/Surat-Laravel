<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratTahapan extends Model
{
    protected $fillable = [
        'surat_id', 'tahap', 'nama_tahap', 'status',
        'diproses_oleh', 'catatan', 'selesai_pada',
    ];

    protected $casts = [
        'selesai_pada' => 'datetime',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }

    public function diprosesByUser()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }
}