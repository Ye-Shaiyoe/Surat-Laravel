@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

{{-- STAT CARDS --}}
<div class="stat-grid">
    <div class="stat-card blue">
        <div class="stat-label">Total Surat Bulan Ini</div>
        <div class="stat-value">{{ $totalBulanIni }}</div>
        <div class="stat-sub">{{ now()->translatedFormat('F Y') }}</div>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Selesai</div>
        <div class="stat-value">{{ $totalSelesai }}</div>
        <div class="stat-sub">Sudah diarsipkan</div>
    </div>
    <div class="stat-card amber">
        <div class="stat-label">Sedang Proses</div>
        <div class="stat-value">{{ $totalProses }}</div>
        <div class="stat-sub">Menunggu tindak lanjut</div>
    </div>
    <div class="stat-card red">
        <div class="stat-label">Melewati SLA</div>
        <div class="stat-value">{{ $totalTerlambat }}</div>
        <div class="stat-sub">Harus segera ditangani</div>
    </div>
</div>

<div class="dashboard-grid">

    {{-- ANTRIAN VERIFIKASI --}}
    <div class="card" style="grid-column:1/-1;">
        <div class="section-header">
            <div>
                <h2>📥 Antrian Menunggu Aksi</h2>
                <small>Surat yang perlu diproses sekarang</small>
            </div>
            <a href="{{ route('admin.surat.index') }}" class="btn btn-sm">Lihat Semua →</a>
        </div>

        @if($antrian->isEmpty())
            <div style="text-align:center; padding:32px; color:#9ca3af; font-size:13px;">
                ✅ Tidak ada antrian saat ini
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Judul Surat</th>
                            <th>Pengusul</th>
                            <th>Jenis</th>
                            <th>Sifat</th>
                            <th>Tahap Sekarang</th>
                            <th>SLA</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($antrian as $surat)
                        <tr>
                            <td>
                                <div style="font-weight:500; color:#111827; max-width:220px;">
                                    {{ \Illuminate\Support\Str::limit($surat->judul, 45) }}
                                </div>
                                <div style="font-size:11px; color:#9ca3af; margin-top:2px;">
                                    {{ $surat->created_at?->diffForHumans() ?? 'Tanpa tanggal' }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size:13px;">{{ $surat->user?->name ?? '—' }}</div>
                            </td>
                            <td>
                                <span class="badge badge-purple">{{ $surat->jenis_label }}</span>
                            </td>
                            <td>
                                @if($surat->sifat === 'segera')
                                    <span class="badge badge-red">Segera</span>
                                @elseif($surat->sifat === 'rahasia')
                                    <span class="badge badge-amber">Rahasia</span>
                                @else
                                    <span class="badge badge-gray">Biasa</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:12px; font-weight:500; color:#1d4ed8;">
                                    Tahap {{ $surat->tahap_sekarang }}/10
                                </div>
                                <div style="font-size:11px; color:#6b7280; margin-top:2px;">
                                    {{ $surat->nama_tahap }}
                                </div>
                                <div class="progress-bar" style="margin-top:5px; width:100px;">
                                    <div
                                        class="progress-fill"
                                        @style(['width' => min(100, max(0, (int) $surat->proses_persen)).'%'])
                                    ></div>
                                </div>
                            </td>
                            <td>
                                @if($surat->sla_status === 'terlambat')
                                    <span class="badge badge-red">⚠ Terlambat</span>
                                @else
                                    <span class="badge badge-green">⏱ {{ $surat->sisa_jam }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.surat.show', $surat) }}"
                                   class="btn btn-sm btn-primary">Proses →</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- REKAP PER JENIS --}}
    <div class="card">
        <div class="section-header">
            <h2>📊 Rekap Per Jenis</h2>
        </div>
        @forelse($rekapJenis as $jenis => $jumlah)
            <div style="display:flex; align-items:center; justify-content:space-between; padding:8px 0; border-bottom:1px solid #f3f4f6;">
                <span style="font-size:13px; color:#374151;">
                    {{ \App\Models\Surat::JENIS_LABEL[$jenis] ?? $jenis }}
                </span>
                <span class="badge badge-blue">{{ $jumlah }}</span>
            </div>
        @empty
            <div style="text-align:center; padding:24px; color:#9ca3af; font-size:13px;">
                Belum ada surat bulan ini
            </div>
        @endforelse
    </div>

    {{-- SURAT TERBARU --}}
    <div class="card">
        <div class="section-header">
            <h2>🕐 Surat Terbaru</h2>
        </div>
        @forelse($suratTerbaru as $surat)
            <div style="display:flex; align-items:flex-start; gap:10px; padding:8px 0; border-bottom:1px solid #f3f4f6;">
                <div style="flex:1; min-width:0;">
                    <div style="font-size:13px; font-weight:500; color:#111827; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $surat->judul }}
                    </div>
                    <div style="font-size:11px; color:#9ca3af; margin-top:2px;">
                        {{ $surat->user?->name ?? '—' }} · {{ $surat->created_at?->diffForHumans() ?? 'Tanpa tanggal' }}
                    </div>
                </div>
                @if($surat->status === 'selesai')
                    <span class="badge badge-green">Selesai</span>
                @elseif($surat->status === 'ditolak')
                    <span class="badge badge-red">Ditolak</span>
                @else
                    <span class="badge badge-amber">Proses</span>
                @endif
            </div>
        @empty
            <div style="text-align:center; padding:24px; color:#9ca3af; font-size:13px;">
                Belum ada data surat
            </div>
        @endforelse
    </div>

</div>

@endsection