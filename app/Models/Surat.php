<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Surat extends Model
{
    protected $fillable = [
        'user_id', 'judul', 'jenis', 'sifat', 'tujuan',
        'file_word', 'file_lampiran', 'nomor_surat', 'tanggal_surat',
        'tahap_sekarang', 'status', 'perlu_follow_up', 'catatan_follow_up',
        'deadline_sla',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'deadline_sla'  => 'datetime',
        'perlu_follow_up' => 'boolean',
    ];

    // Label tampilan
    const JENIS_LABEL = [
        'nota_dinas'       => 'Nota Dinas',
        'surat_dinas'      => 'Surat Dinas',
        'surat_keputusan'  => 'Surat Keputusan',
        'surat_pernyataan' => 'Surat Pernyataan',
        'surat_keterangan' => 'Surat Keterangan',
    ];

    const NAMA_TAHAP = [
        1  => 'Usulan Diajukan',
        2  => 'Verifikasi Arsiparis',
        3  => 'Verifikasi Kasubbag TU',
        4  => 'Persetujuan Kepala Balai',
        5  => 'Penomoran Surat',
        6  => 'Tanda Tangan (DS)',
        7  => 'Pengiriman via TNDe',
        8  => 'Pengiriman via Srikandi',
        9  => 'Pengarsipan',
        10 => 'Follow Up / Selesai',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tahapans()
    {
        return $this->hasMany(SuratTahapan::class)->orderBy('tahap');
    }

    public function tahapanSekarang()
    {
        return $this->hasOne(SuratTahapan::class)->where('tahap', $this->tahap_sekarang);
    }

    // Helpers
    public function getJenisLabelAttribute(): string
    {
        return self::JENIS_LABEL[$this->jenis] ?? $this->jenis;
    }

    public function getNamaTahapAttribute(): string
    {
        return self::NAMA_TAHAP[$this->tahap_sekarang] ?? '-';
    }

    public function getProsesPersenAttribute(): int
    {
        return (int) round(($this->tahap_sekarang / 10) * 100);
    }

    public function getSlaStatusAttribute(): string
    {
        if (!$this->deadline_sla || $this->status === 'selesai') return 'ok';
        return now()->gt($this->deadline_sla) ? 'terlambat' : 'ok';
    }

    public function getSisaJamAttribute(): string
    {
        if (!$this->deadline_sla) return '-';
        if (now()->gt($this->deadline_sla)) return 'Terlambat';
        $diff = now()->diff($this->deadline_sla);
        return $diff->h . 'j ' . $diff->i . 'm';
    }

    // Inisialisasi semua tahapan saat surat dibuat
    public function initTahapan(): void
    {
        foreach (self::NAMA_TAHAP as $tahap => $nama) {
            $this->tahapans()->create([
                'tahap'      => $tahap,
                'nama_tahap' => $nama,
                'status'     => $tahap === 1 ? 'selesai' : 'menunggu',
            ]);
        }
    }
}