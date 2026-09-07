<div class="section-header d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1" style="font-size:18px; font-weight:700; color:var(--text-primary);">
            <i class="bi bi-file-earmark-text text-primary me-1"></i>
            @if(isset($title) && $title === 'Semua Surat')
                Semua Data Surat
            @elseif(isset($title) && $title === 'Surat Masuk')
                Surat Masuk
            @elseif(isset($title) && $title === 'Surat Sedang Diproses')
                Surat Sedang Diproses
            @elseif(isset($title) && $title === 'Surat Selesai')
                Surat Selesai
            @elseif(isset($title) && $title === 'Surat Perlu Revisi')
                Surat Perlu Revisi
            @else
                {{ $title ?? 'Data Surat' }}
            @endif
        </h2>
        <small style="color:var(--text-secondary); font-size:12px;">
            Menampilkan {{ $surats->firstItem() ?? 0 }} - {{ $surats->lastItem() ?? 0 }} dari total <strong>{{ $surats->total() }}</strong> data surat
        </small>
    </div>
    @if(request()->anyFilled(['search', 'jenis', 'status', 'sifat', 'tahap', 'user_id', 'sla_status', 'bulan', 'tahun', 'tanggal_mulai', 'tanggal_selesai', 'start_date', 'end_date']))
        <span class="badge" style="background:rgba(59, 130, 246, 0.08); color:#2563eb; border:1px solid rgba(59, 130, 246, 0.2); font-size:11px; padding:5px 12px; border-radius:20px; font-weight:600;">
            <i class="bi bi-funnel-fill me-1"></i> Filter Aktif
        </span>
    @endif
</div>

@if($surats->isEmpty())
    <div style="text-align:center; padding:50px 20px; color:var(--text-secondary); font-size:13px;">
        <div style="margin-bottom:12px;">
            <i class="bi bi-inbox text-slate-400" style="font-size:42px; opacity:0.6;"></i>
        </div>
        <div style="font-weight:600; font-size:15px; color:var(--text-primary); margin-bottom:4px;">Tidak ada data surat yang sesuai</div>
        <p style="font-size:12px; margin-bottom:16px; color:var(--text-secondary);">Silakan ubah kata kunci pencarian atau atur ulang filter yang dipilih.</p>
        <button type="button" onclick="resetAllFilters()" class="btn btn-sm btn-outline-primary" style="border-radius:8px; font-size:12px; font-weight:600;">
            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
        </button>
    </div>
@else
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">#</th>
                    <th style="width: 250px;">Informasi Surat</th>
                    <th style="width: 150px;">Pengusul</th>
                    <th style="width: 140px;">Detail Klasifikasi</th>
                    <th style="width: 150px;">Tujuan</th>
                    <th style="width: 160px;">Proses Tracking</th>
                    <th style="width: 110px;">Status</th>
                    <th style="width: 130px;">SLA</th>
                    <th style="width: 90px; text-align:center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($surats as $surat)
                <tr>
                    <td style="color:var(--text-secondary); font-size:12px;">
                        {{ ($surats->currentPage() - 1) * $surats->perPage() + $loop->iteration }}
                    </td>
                    <td>
                        <div style="font-weight:700; color:var(--text-primary); line-height: 1.4; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; display: flex; align-items: center; gap: 6px;" title="{{ $surat->judul }}">
                            {{ $surat->judul }}
                            @if($surat->pendingDeleteRequest)
                                <span class="badge" style="background:#fee2e2; color:#ef4444; border:1px solid #fca5a5; font-size:9px; padding: 2px 6px; font-weight:700;">
                                    <i class="bi bi-trash-fill me-1"></i> Permintaan Hapus
                                </span>
                            @endif
                        </div>
                        <div style="font-size:11px; color:#1e3a5f; margin-top:4px; font-weight: 600;">
                            <i class="bi bi-hash"></i> {{ $surat->nomor_surat ?? 'Belum ada nomor' }}
                        </div>
                        <div style="font-size:11px; color:var(--text-secondary); margin-top:2px; display: flex; align-items: center; gap: 4px;">
                            <i class="bi bi-calendar-event"></i> {{ $surat->created_at?->format('d/m/Y') ?? '—' }} 
                            <span style="opacity: 0.5;">|</span>
                            <i class="bi bi-clock"></i> {{ $surat->created_at?->format('H:i') ?? '—' }} WIB
                        </div>
                    </td>
                    <td>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div style="width:28px; height:28px; border-radius:50%; background:linear-gradient(135deg, #3b82f6, #1d4ed8); color:white; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; flex-shrink:0;">
                                {{ strtoupper(substr($surat->user?->name ?? 'P', 0, 1)) }}
                            </div>
                            <div style="font-size:12.5px; font-weight: 600; color: var(--text-primary); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $surat->user?->name }}">
                                {{ $surat->user?->name ?? '—' }}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="mb-1">
                            <span class="badge badge-purple" style="font-size: 10px;">{{ $surat->jenis_label }}</span>
                        </div>
                        @if($surat->sifat === 'segera')
                            <span class="badge badge-red" style="font-size: 10px;"><i class="bi bi-lightning-charge-fill me-1"></i>Segera</span>
                        @elseif($surat->sifat === 'rahasia')
                            <span class="badge badge-amber" style="font-size: 10px;"><i class="bi bi-shield-lock-fill me-1"></i>Rahasia</span>
                        @else
                            <span class="badge badge-gray" style="font-size: 10px;">Biasa</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size: 12px; color: var(--text-primary); line-height: 1.3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $surat->tujuan }}">
                            {{ $surat->tujuan ?? '—' }}
                        </div>
                    </td>
                    <td>
                        <div style="font-size:12px; font-weight:700; color:#3b82f6; display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                            <span>Tahap {{ $surat->tahap_sekarang }}/10</span>
                            <span style="font-size: 10px;">{{ $surat->proses_persen }}%</span>
                        </div>
                        <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 10px; overflow: hidden;">
                            <div style="width: {{ $surat->proses_persen }}%; height: 100%; background: linear-gradient(90deg, #3b82f6, #2563eb); border-radius: 10px;"></div>
                        </div>
                        <div style="font-size:10px; color:var(--text-secondary); margin-top: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $surat->nama_tahap }}">
                            {{ $surat->nama_tahap }}
                        </div>
                    </td>
                    <td>
                        @if($surat->status === 'selesai')
                            <span class="badge badge-green"><i class="bi bi-check-circle-fill me-1"></i>Selesai</span>
                            @if(!is_null($surat->rating))
                                <div style="font-size:10px; color:#d97706; margin-top:4px; font-weight:700; display:flex; align-items:center; justify-content:center; gap:2px; background:rgba(251,191,36,0.1); padding:2px 4px; border-radius:4px; border:1px solid rgba(251,191,36,0.2);">
                                    <i class="bi bi-star-fill text-warning" style="color:#f59e0b !important;"></i> {{ $surat->rating }}/5
                                </div>
                            @endif
                        @elseif($surat->status === 'ditolak')
                            <span class="badge badge-red"><i class="bi bi-x-circle-fill me-1"></i>Ditolak</span>
                        @elseif($surat->status === 'revisi')
                            <div class="badge badge-amber" style="text-align: left; padding: 4px 8px;">
                                <div><i class="bi bi-pencil-square me-1"></i>Revisi</div>
                                <div style="font-size: 8px; opacity: 0.8; margin-top: 1px;">{{ $surat->revisi_uploaded_at?->format('d/m H:i') ?? '-' }}</div>
                            </div>
                        @elseif($surat->status === 'revisi_admin')
                            <span class="badge" style="background:#fef3c7;color:#92400e;border:1.5px solid #fbbf24;font-size:10px;"><i class="bi bi-tools me-1"></i>Admin Revisi</span>
                        @elseif($surat->status === 'draft')
                            <span class="badge badge-gray"><i class="bi bi-file-earmark me-1"></i>Draf</span>
                        @else
                            <span class="badge badge-blue"><i class="bi bi-hourglass-split me-1"></i>Proses</span>
                        @endif
                    </td>
                    <td>
                        @if($surat->status === 'selesai')
                            <div class="text-success" style="font-size: 11px; font-weight: 600;">
                                <i class="bi bi-check-circle-fill me-1"></i> Selesai
                            </div>
                        @elseif($surat->sla_status === 'terlambat')
                            <div class="text-danger" style="font-size: 11px; font-weight: 700;">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $surat->sisa_jam }}
                            </div>
                        @else
                            <div class="text-primary" style="font-size: 11px; font-weight: 600;">
                                <i class="bi bi-clock-history me-1"></i> {{ $surat->sisa_jam }}
                            </div>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <a href="{{ route('admin.surat.show', $surat) }}"
                           class="btn btn-sm btn-primary" style="padding: 5px 12px; border-radius: 7px; font-weight: 600; font-size: 11px; white-space:nowrap;">
                            Detail <i class="bi bi-arrow-right"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mt-3 pt-2 border-top" id="paginationWrapper">
        <div style="font-size:12px; color:var(--text-secondary);">
            Halaman {{ $surats->currentPage() }} dari {{ $surats->lastPage() }}
        </div>
        <div class="ajax-pagination">
            {{ $surats->links() }}
        </div>
    </div>
@endif
