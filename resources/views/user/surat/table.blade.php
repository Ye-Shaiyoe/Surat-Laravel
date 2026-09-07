@extends('layouts.user')
@section('title', $title)

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 animate-in">
    <div>
        <h5 class="fw-bold mb-0" style="color:#1e3a5f;">📊 {{ $title }}</h5>
        <small class="text-muted">{{ request('status') === 'draft' ? 'Data draf surat dalam format tabel detail' : 'Data surat dalam format tabel detail' }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('user.surat.exportExcel', request()->all()) }}" class="btn btn-success d-flex align-items-center gap-2"
           style="border-radius:9px;font-size:13px;font-weight:600;background:#10b981;border-color:#10b981;">
            <i class="bi bi-file-earmark-excel-fill"></i> Export Excel
        </a>
        <a href="{{ route('user.surat.index') }}" class="btn btn-light d-flex align-items-center gap-2"
           style="border-radius:9px;font-size:13px;font-weight:600;">
            <i class="bi bi-grid-fill"></i> Tampilan Card
        </a>
        <a href="{{ route('user.surat.create') }}" class="btn btn-primary d-flex align-items-center gap-2"
           style="background:#1e3a5f;border-color:#1e3a5f;border-radius:9px;font-size:13px;font-weight:600;">
            <i class="bi bi-plus-circle-fill"></i> Ajukan Surat
        </a>
    </div>
</div>

{{-- FILTER --}}
<div class="card card-custom mb-4 animate-in" style="animation-delay: 0.1s;">
    <div class="card-body py-3 px-4">
        <form method="GET" action="{{ route('user.surat.table') }}" data-turbo="false"
              class="d-flex gap-3 align-items-end flex-wrap">
            <div>
                <label class="form-label mb-1" style="font-size:11px;color:#6b7280;font-weight:600;">STATUS</label>
                <select name="status" class="form-select form-select-sm" style="font-size:13px;border-radius:7px;width:130px;">
                    <option value="">Semua</option>
                    <option value="proses"  {{ request('status')==='proses'  ? 'selected':'' }}>Proses</option>
                    <option value="revisi"  {{ request('status')==='revisi'  ? 'selected':'' }}>Revisi</option>
                    <option value="selesai" {{ request('status')==='selesai' ? 'selected':'' }}>Selesai</option>
                    <option value="ditolak" {{ request('status')==='ditolak' ? 'selected':'' }}>Ditolak</option>
                    <option value="draft"   {{ request('status')==='draft'   ? 'selected':'' }}>Draf</option>
                </select>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:11px;color:#6b7280;font-weight:600;">JENIS</label>
                <select name="jenis" class="form-select form-select-sm" style="font-size:13px;border-radius:7px;width:160px;">
                    <option value="">Semua Jenis</option>
                    @foreach(\App\Models\Surat::JENIS_LABEL as $val => $label)
                        <option value="{{ $val }}" {{ request('jenis')===$val ? 'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:11px;color:#6b7280;font-weight:600;">DARI TGL</label>
                <input type="date" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" class="form-control form-control-sm" style="font-size:13px;border-radius:7px;width:140px;background:#ffffff;color:#111827;border-color:#e5e7eb;">
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:11px;color:#6b7280;font-weight:600;">SAMPAI TGL</label>
                <input type="date" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}" class="form-control form-control-sm" style="font-size:13px;border-radius:7px;width:140px;background:#ffffff;color:#111827;border-color:#e5e7eb;">
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:11px;color:#6b7280;font-weight:600;">TAHUN</label>
                <select name="tahun" class="form-select form-select-sm" style="font-size:13px;border-radius:7px;width:100px;">
                    <option value="">Semua</option>
                    @php $startYear = 2024; $currentYear = date('Y'); @endphp
                    @for($y = $currentYear; $y >= $startYear; $y--)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:11px;color:#6b7280;font-weight:600;">BULAN</label>
                <select name="bulan" class="form-select form-select-sm" style="font-size:13px;border-radius:7px;width:120px;">
                    <option value="">Semua</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ request('bulan') == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label mb-1" style="font-size:11px;color:#6b7280;font-weight:600;">CARI JUDUL</label>
                <div class="input-group input-group-sm" style="width: 250px;">
                    <span class="input-group-text bg-white border-end-0" style="border-radius: 7px 0 0 7px; border-color: #e5e7eb;">
                        <i class="bi bi-search" style="color: #6b7280;"></i>
                    </span>
                    <input type="text" name="search" id="tableSearchInput" class="form-control border-start-0" 
                           placeholder="Ketik judul surat..." 
                           value="{{ request('search') }}"
                           autocomplete="off"
                           style="font-size:13px;border-radius:0 7px 7px 0;background:#ffffff;color:#111827;border-color:#e5e7eb;">
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary" style="background:#1e3a5f;border-color:#1e3a5f;border-radius:7px;font-size:12px;">
                    <i class="bi bi-search me-1"></i>Filter
                </button>
                <a href="{{ route('user.surat.table') }}" class="btn btn-sm btn-light" style="border-radius:7px;font-size:12px;">Reset</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('tableSearchInput');
        let searchTimer;

        if (searchInput) {
            // Restore focus and cursor position if searched
            if (searchInput.value.length > 0) {
                searchInput.focus();
                searchInput.setSelectionRange(searchInput.value.length, searchInput.value.length);
            }

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    this.closest('form').submit();
                }, 600); // Debounce 600ms
            });
        }
    });
</script>

<div class="card card-custom animate-in" style="animation-delay: 0.2s;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 13px;">
                <thead class="bg-light text-muted" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
                    <tr>
                        <th class="ps-4 py-3">No</th>
                        <th class="py-3">Informasi Surat</th>
                        <th class="py-3">Tujuan & Jenis</th>
                        <th class="py-3">Tgl Pengajuan</th>
                        <th class="py-3">Status & SLA</th>
                        <th class="py-3">No. Surat</th>
                        <th class="py-3">Progress Tahap</th>
                        <th class="pe-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surats as $index => $surat)
                        <tr>
                            <td class="ps-4 text-muted">
                                {{ $surats->firstItem() + $index }}
                            </td>
                            <td>
                                <div class="fw-bold" style="color: #1e3a5f; font-size: 14px;">{{ $surat->judul }}</div>
                                <div class="d-flex gap-1 mt-1">
                                    <span class="badge badge-{{ $surat->sifat }}" style="font-size: 9px; padding: 3px 6px;">{{ ucfirst($surat->sifat) }}</span>
                                    @if(in_array($surat->status, ['revisi', 'revisi_admin']))
                                        <span class="badge bg-warning text-dark" style="font-size: 9px; padding: 3px 6px;"><i class="bi bi-pencil-square"></i> Perlu Revisi</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 500; color: #374151;">{{ $surat->tujuan ?: '-' }}</div>
                                <div class="text-muted" style="font-size: 11px; margin-top: 2px;">
                                    <i class="bi bi-tag me-1"></i>{{ $surat->jenis_label }}
                                </div>
                            </td>
                            <td>
                                <div style="color: #374151;">{{ $surat->created_at->translatedFormat('d M Y') }}</div>
                                <small class="text-muted" style="font-size: 10px;">{{ $surat->created_at->format('H:i') }} WIB</small>
                            </td>
                            <td>
                                {{-- Status Badge --}}
                                <div class="mb-2">
                                    @if($surat->status === 'selesai')
                                        <div class="d-flex flex-column align-items-start">
                                            <span class="badge rounded-pill" style="background:#dcfce7; color:#15803d; font-size:10px; margin-bottom:2px;">✓ Selesai</span>
                                            @if(!is_null($surat->rating))
                                                <div style="font-size:10px;color:#d97706;font-weight:700;display:flex;align-items:center;gap:2px;">
                                                    <i class="bi bi-star-fill text-warning" style="font-size:10px;color:#f59e0b !important;"></i> {{ $surat->rating }}/5
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($surat->status === 'ditolak')
                                        <span class="badge rounded-pill" style="background:#fee2e2; color:#b91c1c; font-size:10px;">✗ Ditolak</span>
                                    @elseif(in_array($surat->status, ['revisi', 'revisi_admin']))
                                        <span class="badge rounded-pill" style="background:#fef3c7; color:#b45309; font-size:10px;">📝 Revisi</span>
                                    @elseif($surat->status === 'draft')
                                        <span class="badge rounded-pill" style="background:#f1f5f9; color:#64748b; font-size:10px;">📄 Draf</span>
                                    @else
                                        <span class="badge rounded-pill" style="background:#dbeafe; color:#1d4ed8; font-size:10px;">⏱ Proses</span>
                                    @endif
                                </div>

                                {{-- SLA --}}
                                @if($surat->status === 'selesai' || $surat->status === 'ditolak')
                                    <small class="text-muted" style="font-size: 10px;">Diproses: {{ $surat->updated_at->diffForHumans($surat->created_at, true) }}</small>
                                @elseif($surat->sla_status === 'terlambat')
                                    <span class="badge bg-danger" style="font-size: 9px;"><i class="bi bi-exclamation-circle me-1"></i>SLA Terlambat</span>
                                @else
                                    <div class="d-flex align-items-center gap-1" style="font-size: 11px; color: #2563eb;">
                                        <i class="bi bi-clock-history"></i>
                                        <span>{{ $surat->sisa_jam }}</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($surat->nomor_surat)
                                    <code class="fw-bold" style="color: #2563eb; font-size: 12px;">{{ $surat->nomor_surat }}</code>
                                @else
                                    <span class="text-muted" style="font-size: 11px;">Belum Terbit</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress" style="width: 50px; height: 6px; border-radius: 99px; background: #e5e7eb;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                             style="width: {{ $surat->proses_persen }}%; background: {{ $surat->status==='ditolak' ? '#ef4444' : '#1e3a5f' }};"></div>
                                    </div>
                                    <span class="fw-bold" style="font-size: 11px; color: #1e3a5f;">{{ $surat->proses_persen }}%</span>
                                </div>
                                <div style="font-size: 10px; color: #64748b; margin-top: 4px; font-weight: 500;">
                                    Tahap {{ $surat->tahap_sekarang }}: {{ $surat->nama_tahap }}
                                </div>
                            </td>
                            <td class="pe-4 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('user.surat.show', $surat) }}" 
                                       class="btn btn-sm btn-outline-primary d-flex align-items-center justify-content-center" 
                                       style="width: 32px; height: 32px; border-radius: 8px;" title="Lihat Detail">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    @if($surat->status === 'draft' || $surat->status === 'revisi')
                                        <a href="{{ route('user.surat.edit', $surat) }}" 
                                           class="btn btn-sm btn-outline-info d-flex align-items-center justify-content-center" 
                                           style="width: 32px; height: 32px; border-radius: 8px;" title="Edit Surat">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="animate-in">
                                    <i class="bi bi-inbox mb-3" style="font-size: 48px; display: block; opacity: 0.5;"></i>
                                    <h6 class="fw-bold">Belum ada data surat</h6>
                                    <p class="small mb-0">Silakan ajukan surat baru melalui tombol di atas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($surats->hasPages())
        <div class="card-footer bg-white border-0 py-3 px-4">
            {{ $surats->links() }}
        </div>
    @endif
</div>

<style>
    .table thead th {
        font-weight: 700;
        color: #64748b;
    }
    .table tbody tr {
        transition: all 0.2s ease;
    }
    .table tbody tr:hover {
        background-color: #f8fafc;
    }
</style>

@endsection
