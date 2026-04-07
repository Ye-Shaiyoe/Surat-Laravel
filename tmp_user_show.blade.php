@extends('layouts.admin')

@section('title', 'Detail Pegawai')

@section('content')
<div class="space-y-24">

    {{-- BACK BUTTON & HEADER --}}
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>{{ $user->name }}</h1>
            <p style="color: #6b7280; margin-top: 4px;">
                <strong>Email:</strong> {{ $user->email }} | 
                <strong>Role:</strong> {{ ucfirst($user->role) }} |
                <strong>Daftar:</strong> {{ $user->created_at->format('d M Y') }}
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            ← Kembali
        </a>
    </div>

    {{-- USER STATISTICS --}}
    <div class="stat-grid">
        <div class="stat-card blue">
            <div class="stat-label">Total Surat</div>
            <div class="stat-value">{{ $stats['total_surats'] }}</div>
        </div>
        <div class="stat-card green">
            <div class="stat-label">Selesai</div>
            <div class="stat-value">{{ $stats['surats_selesai'] }}</div>
        </div>
        <div class="stat-card amber">
            <div class="stat-label">Dalam Proses</div>
            <div class="stat-value">{{ $stats['surats_proses'] }}</div>
        </div>
        <div class="stat-card red">
            <div class="stat-label">Ditolak</div>
            <div class="stat-value">{{ $stats['surats_ditolak'] }}</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-label">Rata-rata Hari Proses</div>
            <div class="stat-value">{{ $stats['avg_processing_days'] }}</div>
            <div class="stat-sub">Untuk surat selesai</div>
        </div>
    </div>

    {{-- SURAT HISTORY --}}
    <div class="card">
        <div class="section-header" style="margin-bottom: 16px;">
            <h2>📄 Riwayat Surat</h2>
            <small>Total: {{ $user->surats->count() }} surat</small>
        </div>

        @if($user->surats->count() > 0)
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 20%;">Judul</th>
                            <th style="width: 15%;">Jenis</th>
                            <th style="width: 12%;">Status</th>
                            <th style="width: 12%; text-align: center;">Tahap</th>
                            <th style="width: 12%; text-align: center;">Deadline</th>
                            <th style="width: 10%; text-align: center;">Dibuat</th>
                            <th style="width: 9%; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->surats as $surat)
                            <tr>
                                <td>
                                    <strong>{{ Str::limit($surat->judul, 30) }}</strong>
                                </td>
                                <td>
                                    <span style="font-size: 11px; color: #6b7280;">
                                        {{ str_replace('_', ' ', $surat->jenis) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ match($surat->status) {
                                        'selesai' => 'badge-green',
                                        'proses' => 'badge-blue',
                                        'ditolak' => 'badge-red',
                                        default => 'badge-gray'
                                    } }}">
                                        {{ ucfirst($surat->status) }}
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span style="font-size: 12px; color: #6b7280;">
                                        {{ $surat->tahap_sekarang }}/10
                                    </span>
                                </td>
                                <td style="text-align: center; font-size: 12px;">
                                    @if($surat->deadline_sla)
                                        <span style="color: {{ now()->diffInDays($surat->deadline_sla) <= 3 ? '#b91c1c' : '#6b7280' }};">
                                            {{ $surat->deadline_sla->format('d M Y') }}
                                        </span>
                                    @else
                                        <span style="color: #9ca3af;">-</span>
                                    @endif
                                </td>
                                <td style="text-align: center; font-size: 12px; color: #6b7280;">
                                    {{ $surat->created_at->format('d M Y') }}
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('admin.surat.show', $surat->id) }}" class="btn btn-sm" title="Lihat detail">
                                        👁️
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div style="text-align: center; padding: 40px 20px; color: #9ca3af;">
                <p style="font-size: 14px;">Pengguna ini belum mengajukan surat apapun</p>
            </div>
        @endif
    </div>

</div>

<style>
    @media (max-width: 768px) {
        div[style*="display: flex"] {
            flex-direction: column !important;
            gap: 16px !important;
        }
        
        table { font-size: 11px; }
        thead th { padding: 8px 6px; }
        tbody td { padding: 8px 6px; }
    }
</style>
@endsection
