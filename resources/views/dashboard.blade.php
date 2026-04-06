@extends('layouts.user')
@section('title', 'Dashboard')

@section('content')

{{-- GREETING --}}
<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <div>
        <h4 class="fw-bold mb-1" style="color:#1e3a5f;">
            👋 Halo, {{ Str::words(Auth::user()->name, 1, '') }}!
        </h4>
        <p class="text-muted mb-0" style="font-size:13px;">
            {{ now()->translatedFormat('l, d F Y') }} · Selamat datang di Sistem Surat Metrologi
        </p>
    </div>
    <a href="{{ route('user.surat.create') }}" class="btn btn-primary d-flex align-items-center gap-2"
       style="background:#1e3a5f; border-color:#1e3a5f; border-radius:9px; font-size:13px; font-weight:600;">
        <i class="bi bi-plus-circle-fill"></i> Ajukan Surat Baru
    </a>
</div>

{{-- STAT CARDS --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#1e3a5f,#2563eb); color:#fff;">
            <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-mailbox-flag" viewBox="0 0 16 16">
                <path d="M10.5 8.5V3.707l.854-.853A.5.5 0 0 0 11.5 2.5v-2A.5.5 0 0 0 11 0H9.5a.5.5 0 0 0-.5.5v8zM5 7c0 .334-.164.264-.415.157C4.42 7.087 4.218 7 4 7s-.42.086-.585.157C3.164 7.264 3 7.334 3 7a1 1 0 0 1 2 0"/>
                <path d="M4 3h4v1H6.646A4 4 0 0 1 8 7v6h7V7a3 3 0 0 0-3-3V3a4 4 0 0 1 4 4v6a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V7a4 4 0 0 1 4-4m0 1a3 3 0 0 0-3 3v6h6V7a3 3 0 0 0-3-3"/>
            </svg>
            </div>
            <div class="stat-value">{{ $totalSurat }}</div>
            <div class="stat-label">Total Surat Diajukan</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#15803d,#22c55e); color:#fff;">
            <div class="stat-icon">✅</div>
            <div class="stat-value">{{ $suratSelesai }}</div>
            <div class="stat-label">Surat Selesai</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#b45309,#f59e0b); color:#fff;">
            <div class="stat-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="42" fill="currentColor" class="bi bi-hourglass-split" viewBox="0 0 16 16" style="display:block;">
                <path d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z"/>
            </svg>
            </div>
            <div class="stat-value">{{ $suratProses }}</div>
            <div class="stat-label">Sedang Diproses</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,#b91c1c,#ef4444); color:#fff;">
            <div class="stat-icon">❌</div>
            <div class="stat-value">{{ $suratDitolak }}</div>
            <div class="stat-label">Surat Ditolak</div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- SURAT TERBARU + TRACKING --}}
    <div class="col-12 col-lg-7">
        <div class="card card-custom h-100">
            <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between px-4 pt-4 pb-3 border-bottom">
                    <div>
                        <h6 class="fw-bold mb-0" style="color:#1e3a5f;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-arrow-down-fill" viewBox="0 0 16 16">
                            <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zm.192 8.159 6.57-4.027L8 9.586l1.239-.757.367.225A4.49 4.49 0 0 0 8 12.5c0 .526.09 1.03.256 1.5H2a2 2 0 0 1-1.808-1.144M16 4.697v4.974A4.5 4.5 0 0 0 12.5 8a4.5 4.5 0 0 0-1.965.45l-.338-.207z"/>
                            <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.354-1.646a.5.5 0 0 1-.722-.016l-1.149-1.25a.5.5 0 1 1 .737-.676l.28.305V11a.5.5 0 0 1 1 0v1.793l.396-.397a.5.5 0 0 1 .708.708z"/>
                        </svg> Surat Terbaru</h6>
                        <small class="text-muted">Klik surat untuk lihat tracking lengkap</small>
                    </div>
                    <a href="{{ route('user.surat.index') }}" class="btn btn-sm btn-outline-primary"
                       style="font-size:12px; border-radius:7px;">Lihat Semua</a>
                </div>

                @if($suratTerbaru->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-envelope-open" style="font-size:36px; display:block; margin-bottom:10px;"></i>
                        Belum ada surat yang diajukan.<br>
                        <a href="{{ route('user.surat.create') }}" class="text-primary text-decoration-none fw-semibold">
                            Ajukan sekarang →
                        </a>
                    </div>
                @else
                    {{-- Surat list accordion --}}
                    <div class="accordion accordion-flush" id="suratAccordion">
                    @foreach($suratTerbaru as $i => $surat)
                        <div class="accordion-item border-0 border-bottom">
                            <div class="accordion-header">
                                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }} py-3 px-4"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#surat{{ $surat->id }}"
                                        style="font-size:13px; background:transparent; box-shadow:none;">
                                    <div class="d-flex align-items-start gap-3 w-100 me-2">
                                        {{-- Status dot --}}
                                        <div style="
                                            width:10px; height:10px; border-radius:50%; flex-shrink:0; margin-top:4px;
                                            background:{{ $surat->status === 'selesai' ? '#22c55e' : ($surat->status === 'ditolak' ? '#ef4444' : '#f59e0b') }}">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="fw-semibold text-truncate" style="color:#111827; max-width:280px;">
                                                {{ $surat->judul }}
                                            </div>
                                            <div class="d-flex gap-2 mt-1 flex-wrap">
                                                <span class="badge rounded-pill" style="font-size:10px; background:#ede9fe; color:#6d28d9;">
                                                    {{ $surat->jenis_label }}
                                                </span>
                                                <span class="badge rounded-pill badge-{{ $surat->sifat }}" style="font-size:10px;">
                                                    {{ ucfirst($surat->sifat) }}
                                                </span>
                                                <span class="text-muted" style="font-size:11px;">
                                                    Tahap {{ $surat->tahap_sekarang }}/10
                                                </span>
                                            </div>
                                        </div>
                                        {{-- SLA / Status badge --}}
                                        <div class="ms-auto flex-shrink-0">
                                            @if($surat->status === 'selesai')
                                                <span class="badge rounded-pill" style="background:#dcfce7;color:#15803d;font-size:10px;">✓ Selesai</span>
                                            @elseif($surat->status === 'ditolak')
                                                <span class="badge rounded-pill" style="background:#fee2e2;color:#b91c1c;font-size:10px;">✗ Ditolak</span>
                                            @elseif($surat->sla_status === 'terlambat')
                                                <span class="badge rounded-pill" style="background:#fee2e2;color:#b91c1c;font-size:10px;">⚠ SLA!</span>
                                            @else
                                                <span class="badge rounded-pill" style="background:#dbeafe;color:#1d4ed8;font-size:10px;">⏱ Proses</span>
                                            @endif
                                        </div>
                                    </div>
                                </button>
                            </div>

                            {{-- TRACKING PANEL --}}
                            <div id="surat{{ $surat->id }}"
                                 class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                                 data-bs-parent="#suratAccordion">
                                <div class="accordion-body px-4 pt-0 pb-3">

                                    {{-- Progress bar --}}
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="progress flex-1" style="height:6px; border-radius:99px; flex:1;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width:{{ $surat->proses_persen }}%; background:#1e3a5f; border-radius:99px;">
                                            </div>
                                        </div>
                                        <span style="font-size:11px; font-weight:600; color:#1e3a5f; white-space:nowrap;">
                                            {{ $surat->proses_persen }}%
                                        </span>
                                    </div>

                                    {{-- Tracking steps --}}
                                    <div class="tracking-steps">
                                    @foreach($surat->tahapans as $tahapan)
                                        <div class="step-item {{ $tahapan->status === 'selesai' ? 'done' : '' }}">
                                            <div style="position:relative;">
                                                <div class="step-circle {{ $tahapan->status }}">
                                                    @if($tahapan->status === 'selesai') <i class="bi bi-check-lg"></i>
                                                    @elseif($tahapan->status === 'proses') <i class="bi bi-arrow-right"></i>
                                                    @elseif($tahapan->status === 'ditolak') <i class="bi bi-x-lg"></i>
                                                    @else {{ $tahapan->tahap }}
                                                    @endif
                                                </div>
                                                @if(!$loop->last)
                                                    <div class="step-line"></div>
                                                @endif
                                            </div>
                                            <div class="step-content">
                                                <div class="step-title {{ $tahapan->status }}">
                                                    {{ $tahapan->nama_tahap }}
                                                </div>
                                                @if($tahapan->selesai_pada)
                                                    <div class="step-meta">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $tahapan->selesai_pada->format('d M Y, H:i') }}
                                                        @if($tahapan->diprosesByUser)
                                                            · {{ $tahapan->diprosesByUser->name }}
                                                        @endif
                                                    </div>
                                                @elseif($tahapan->status === 'proses')
                                                    <div class="step-meta" style="color:#1d4ed8;">
                                                        <i class="bi bi-hourglass-split me-1"></i> Sedang diproses...
                                                    </div>
                                                @endif
                                                @if($tahapan->catatan)
                                                    <div class="step-note">
                                                        <i class="bi bi-chat-left-text me-1"></i>{{ $tahapan->catatan }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    </div>

                                    {{-- Info nomor surat jika sudah ada --}}
                                    @if($surat->nomor_surat)
                                        <div class="alert alert-success py-2 px-3 mt-2 mb-0" style="font-size:12px; border-radius:8px;">
                                            <i class="bi bi-hash me-1"></i>
                                            <strong>Nomor Surat:</strong> {{ $surat->nomor_surat }}
                                            · {{ $surat->tanggal_surat?->format('d M Y') }}
                                        </div>
                                    @endif

                                    {{-- Surat ditolak: info --}}
                                    @if($surat->status === 'ditolak')
                                        <div class="alert alert-danger py-2 px-3 mt-2 mb-0" style="font-size:12px; border-radius:8px;">
                                            <i class="bi bi-x-circle me-1"></i>
                                            <strong>Surat ditolak.</strong> Silakan ajukan ulang dengan perbaikan.
                                        </div>
                                    @endif

                                    <div class="text-end mt-2">
                                        <a href="{{ route('user.surat.show', $surat) }}"
                                           class="btn btn-sm" style="font-size:11px; color:#1e3a5f; border:1px solid #e5e7eb; border-radius:7px;">
                                            Detail lengkap →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- PANEL KANAN --}}
    <div class="col-12 col-lg-5">

        {{-- NOTIFIKASI TERBARU --}}
        <div class="card card-custom mb-3">
            <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between px-4 pt-4 pb-3 border-bottom">
                    <h6 class="fw-bold mb-0" style="color:#1e3a5f;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
                        </svg> Notifikasi Terbaru
                    </h6>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge rounded-pill bg-danger" style="font-size:10px;">
                            {{ auth()->user()->unreadNotifications->count() }} baru
                        </span>
                    @endif
                </div>
                <div style="max-height:240px; overflow-y:auto;">
                    @forelse(auth()->user()->notifications->take(6) as $notif)
                        <a href="{{ route('user.notif.read', $notif->id) }}"
                           class="d-flex align-items-start gap-3 px-4 py-3 text-decoration-none border-bottom
                                  {{ $notif->read_at ? '' : 'bg-light' }}"
                           style="transition:background 0.1s;">
                            <div class="notif-icon {{ $notif->data['type'] ?? 'info' }}" style="margin-top:2px;">
                                @switch($notif->data['type'] ?? 'info')
                                    @case('success') <i class="bi bi-check-circle-fill"></i> @break
                                    @case('warning') <i class="bi bi-exclamation-triangle-fill"></i> @break
                                    @case('danger')  <i class="bi bi-x-circle-fill"></i> @break
                                    @default         <i class="bi bi-info-circle-fill"></i>
                                @endswitch
                            </div>
                            <div style="flex:1; min-width:0;">
                                <div style="font-size:12px; font-weight:{{ $notif->read_at ? '400' : '600' }}; color:#111827;">
                                    {{ $notif->data['title'] ?? 'Notifikasi' }}
                                </div>
                                <div style="font-size:11px; color:#6b7280;">
                                    {{ Str::limit($notif->data['message'] ?? '', 50) }}
                                </div>
                                <div style="font-size:10px; color:#9ca3af; margin-top:2px;">
                                    {{ $notif->created_at->diffForHumans() }}
                                </div>
                            </div>
                            @if(!$notif->read_at)
                                <div style="width:7px;height:7px;border-radius:50%;background:#3b82f6;flex-shrink:0;margin-top:5px;"></div>
                            @endif
                        </a>
                    @empty
                        <div class="text-center py-4 text-muted" style="font-size:13px;">
                            <i class="bi bi-bell-slash" style="font-size:24px; display:block; margin-bottom:6px;"></i>
                            Belum ada notifikasi
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- INFO SLA AKTIF --}}
        @if($suratProses > 0)
        <div class="card card-custom mb-3">
            <div class="card-body px-4 py-3">
                <h6 class="fw-bold mb-3" style="color:#1e3a5f; font-size:13px;">⏱ Status SLA Surat Aktif</h6>
                @foreach($suratAktif as $surat)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span style="font-size:12px; font-weight:500; color:#374151;">
                                {{ Str::limit($surat->judul, 28) }}
                            </span>
                            @if($surat->sla_status === 'terlambat')
                                <span style="font-size:10px; font-weight:600; color:#b91c1c;">⚠ Terlambat</span>
                            @else
                                <span style="font-size:10px; color:#6b7280;">{{ $surat->sisa_jam }}</span>
                            @endif
                        </div>
                        <div class="sla-bar">
                            @php
                                $pct = $surat->deadline_sla
                                    ? min(100, now()->diffInMinutes($surat->created_at) /
                                        max(1, $surat->deadline_sla->diffInMinutes($surat->created_at)) * 100)
                                    : 50;
                                $color = $pct >= 90 ? '#ef4444' : ($pct >= 60 ? '#f59e0b' : '#22c55e');
                            @endphp
                            <div class="sla-fill" style="width:{{ $pct }}%; background:{{ $color }};"></div>
                        </div>
                        <div style="font-size:10px; color:#9ca3af; margin-top:2px;">
                            Tahap {{ $surat->tahap_sekarang }}/10 · {{ $surat->nama_tahap }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- DOWNLOAD TEMPLATE --}}
        <div class="card card-custom">
            <div class="card-body px-4 py-3">
                <h6 class="fw-bold mb-3" style="color:#1e3a5f; font-size:13px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-check" viewBox="0 0 16 16">
                    <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2zm3.708 6.208L1 11.105V5.383zM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2z"/>
                    <path d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686"/>
                </svg> Template Surat
                </h6>
                @forelse($templates as $tpl)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-file-earmark-word" style="color:#2563eb; font-size:18px;"></i>
                        <span style="font-size:12px; flex:1; color:#374151;">{{ $tpl['nama'] }}</span>
                        <a href="{{ $tpl['url'] }}" target="_blank"
                           class="btn btn-sm" style="font-size:11px; padding:3px 10px; border:1px solid #e5e7eb; border-radius:6px; color:#1e3a5f;">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                @empty
                    <div class="text-muted" style="font-size:12px;">Belum ada template tersedia.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection