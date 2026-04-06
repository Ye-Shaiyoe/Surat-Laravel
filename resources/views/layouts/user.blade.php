<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard' }} — Surat Metrologi</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ===== NAVBAR ===== */
        .navbar-main {
            background: #1e3a5f;
            border-bottom: 3px solid #2563eb;
            padding: 0 1.5rem;
            height: 60px;
        }
        .navbar-brand-text {
            font-size: 15px;
            font-weight: 700;
            color: #fff !important;
            letter-spacing: 0.01em;
        }
        .navbar-brand-text small {
            display: block;
            font-size: 10px;
            font-weight: 400;
            color: rgba(255,255,255,0.5);
            letter-spacing: 0;
        }
        .nav-link-item {
            color: rgba(255,255,255,0.7) !important;
            font-size: 13px;
            font-weight: 500;
            padding: 6px 14px !important;
            border-radius: 6px;
            transition: all 0.15s;
        }
        .nav-link-item:hover,
        .nav-link-item.active {
            color: #fff !important;
            background: rgba(255,255,255,0.1);
        }
        .nav-link-item i { margin-right: 5px; }

        /* ===== NOTIF BELL ===== */
        .notif-btn {
            position: relative;
            background: rgba(255,255,255,0.1);
            border: none;
            border-radius: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 17px;
            cursor: pointer;
            transition: background 0.15s;
        }
        .notif-btn:hover { background: rgba(255,255,255,0.2); }
        .notif-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #ef4444;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            width: 17px;
            height: 17px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #1e3a5f;
        }

        /* ===== NOTIF DROPDOWN ===== */
        .notif-dropdown {
            width: 340px;
            max-height: 420px;
            overflow-y: auto;
            border: none;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            border-radius: 12px;
            padding: 0;
        }
        .notif-header {
            padding: 12px 16px;
            border-bottom: 1px solid #f1f3f5;
            font-size: 13px;
            font-weight: 600;
            color: #1e3a5f;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .notif-item {
            padding: 12px 16px;
            border-bottom: 1px solid #f8f9fa;
            display: flex;
            gap: 10px;
            align-items: flex-start;
            text-decoration: none;
            transition: background 0.1s;
        }
        .notif-item:hover { background: #f8f9fa; }
        .notif-item.unread { background: #eff6ff; }
        .notif-item.unread:hover { background: #dbeafe; }
        .notif-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }
        .notif-icon.success { background: #dcfce7; color: #15803d; }
        .notif-icon.warning { background: #fef3c7; color: #b45309; }
        .notif-icon.danger  { background: #fee2e2; color: #b91c1c; }
        .notif-icon.info    { background: #dbeafe; color: #1d4ed8; }
        .notif-title { font-size: 12px; font-weight: 600; color: #111827; line-height: 1.3; }
        .notif-sub   { font-size: 11px; color: #6b7280; margin-top: 2px; }
        .notif-time  { font-size: 10px; color: #9ca3af; margin-top: 3px; }
        .notif-empty { padding: 32px 16px; text-align: center; color: #9ca3af; font-size: 13px; }

        /* ===== AVATAR --===== */
        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(255,255,255,0.15);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            padding: 24px;
            min-height: calc(100vh - 60px);
        }

        /* ===== CARDS ===== */
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .stat-card {
            border: none;
            border-radius: 12px;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        .stat-card .stat-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }
        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 700;
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: 12px;
            opacity: 0.75;
            margin-top: 4px;
        }

        /* ===== TRACKING STEPS ===== */
        .tracking-steps { position: relative; }
        .step-item {
            display: flex;
            gap: 14px;
            position: relative;
        }
        .step-item:not(:last-child) .step-line {
            position: absolute;
            left: 15px;
            top: 32px;
            width: 2px;
            height: calc(100% - 8px);
            background: #e5e7eb;
        }
        .step-item:not(:last-child).done .step-line { background: #86efac; }
        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
            z-index: 1;
        }
        .step-circle.done    { background: #dcfce7; color: #15803d; }
        .step-circle.active  { background: #dbeafe; color: #1d4ed8; border: 2px solid #3b82f6; }
        .step-circle.waiting { background: #f3f4f6; color: #9ca3af; }
        .step-circle.rejected{ background: #fee2e2; color: #b91c1c; }
        .step-content { padding-bottom: 20px; flex: 1; }
        .step-title {
            font-size: 13px;
            font-weight: 600;
            color: #111827;
        }
        .step-title.active  { color: #1d4ed8; }
        .step-title.waiting { color: #9ca3af; }
        .step-meta { font-size: 11px; color: #6b7280; margin-top: 2px; }
        .step-note {
            font-size: 12px;
            background: #f9fafb;
            border-left: 3px solid #e5e7eb;
            padding: 6px 10px;
            border-radius: 0 6px 6px 0;
            color: #374151;
            margin-top: 6px;
        }

        /* ===== BADGE SIFAT ===== */
        .badge-segera  { background: #fee2e2; color: #b91c1c; }
        .badge-rahasia { background: #fef3c7; color: #b45309; }
        .badge-biasa   { background: #f3f4f6; color: #6b7280; }

        /* ===== SLA BAR ===== */
        .sla-bar {
            height: 5px;
            background: #e5e7eb;
            border-radius: 99px;
            overflow: hidden;
        }
        .sla-fill {
            height: 100%;
            border-radius: 99px;
            transition: width 0.3s;
        }

        /* ===== FLASH ===== */
        .flash-container {
            position: fixed;
            top: 70px;
            right: 20px;
            z-index: 9999;
            width: 320px;
        }

        /* ===== UPLOAD AREA ===== */
        .upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            color: #94a3b8;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .upload-area:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            color: #2563eb;
        }
        .upload-area input[type=file] {
            display: none;
        }

        /* Scrollbar notif */
        .notif-dropdown::-webkit-scrollbar { width: 4px; }
        .notif-dropdown::-webkit-scrollbar-track { background: transparent; }
        .notif-dropdown::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 99px; }
    </style>
</head>
<body>

{{-- ===== NAVBAR ===== --}}
<nav class="navbar navbar-main d-flex align-items-center justify-content-between">
    {{-- Brand --}}
    <a class="navbar-brand-text text-decoration-none" href="{{ route('dashboard') }}">
        ⚖️ Surat Metrologi
        <small>Balai Metrologi Legal</small>
    </a>

    {{-- Nav Links --}}
    <div class="d-flex align-items-center gap-1 d-none d-md-flex">
        <a href="{{ route('dashboard') }}"
           class="nav-link-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house"></i> Dashboard
        </a>
        <a href="{{ route('user.surat.create') }}"
           class="nav-link-item {{ request()->routeIs('user.surat.create') ? 'active' : '' }}">
            <i class="bi bi-plus-circle"></i> Ajukan Surat
        </a>
        <a href="{{ route('user.surat.index') }}"
           class="nav-link-item {{ request()->routeIs('user.surat.*') ? 'active' : '' }}">
            <i class="bi bi-envelope"></i> Surat Saya
        </a>
        <a href="{{ route('user.template.index') }}"
           class="nav-link-item {{ request()->routeIs('user.template.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-word"></i> Template
        </a>
    </div>

    {{-- Right: notif + avatar --}}
    <div class="d-flex align-items-center gap-2">

        {{-- Notifikasi --}}
        <div class="dropdown">
            <button class="notif-btn" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                    aria-expanded="false" id="notif-toggle">
                <i class="bi bi-bell"></i>
                @php $unreadNotif = auth()->user()->unreadNotifications->count(); @endphp
                @if($unreadNotif > 0)
                    <span class="notif-badge">{{ $unreadNotif > 9 ? '9+' : $unreadNotif }}</span>
                @endif
            </button>
            <div class="dropdown-menu notif-dropdown" aria-labelledby="notif-toggle">
                <div class="notif-header">
                    <span><i class="bi bi-bell me-1"></i> Notifikasi</span>
                    @if($unreadNotif > 0)
                        <a href="{{ route('user.notif.readAll') }}"
                           class="text-decoration-none" style="font-size:11px; color:#2563eb;"
                           onclick="event.preventDefault(); document.getElementById('readall-form').submit();">
                            Tandai semua dibaca
                        </a>
                    @endif
                </div>

                @forelse(auth()->user()->notifications->take(10) as $notif)
                    <a href="{{ route('user.notif.read', $notif->id) }}"
                       class="notif-item {{ $notif->read_at ? '' : 'unread' }}">
                        <div class="notif-icon {{ $notif->data['type'] ?? 'info' }}">
                            @switch($notif->data['type'] ?? 'info')
                                @case('success') <i class="bi bi-check-circle-fill"></i> @break
                                @case('warning') <i class="bi bi-exclamation-triangle-fill"></i> @break
                                @case('danger')  <i class="bi bi-x-circle-fill"></i> @break
                                @default         <i class="bi bi-info-circle-fill"></i>
                            @endswitch
                        </div>
                        <div style="flex:1; min-width:0;">
                            <div class="notif-title">{{ $notif->data['title'] ?? 'Notifikasi' }}</div>
                            <div class="notif-sub">{{ Str::limit($notif->data['message'] ?? '', 55) }}</div>
                            <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                        </div>
                        @if(!$notif->read_at)
                            <div style="width:7px;height:7px;border-radius:50%;background:#3b82f6;flex-shrink:0;margin-top:4px;"></div>
                        @endif
                    </a>
                @empty
                    <div class="notif-empty">
                        <i class="bi bi-bell-slash" style="font-size:24px;display:block;margin-bottom:8px;"></i>
                        Belum ada notifikasi
                    </div>
                @endforelse
            </div>
        </div>

        {{-- User dropdown --}}
        <div class="dropdown">
            <div class="user-avatar" data-bs-toggle="dropdown">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <ul class="dropdown-menu dropdown-menu-end" style="border-radius:10px; border:none; box-shadow:0 8px 24px rgba(0,0,0,0.1); font-size:13px; min-width:180px;">
                <li><div class="px-3 py-2 border-bottom">
                    <div style="font-weight:600; color:#111827; font-size:13px;">{{ Auth::user()->name }}</div>
                    <div style="font-size:11px; color:#6b7280;">{{ Auth::user()->email }}</div>
                </div></li>
                <li><a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person me-2"></i> Profil Saya
                </a></li>
                <li><a class="dropdown-item py-2" href="{{ route('user.surat.index') }}">
                    <i class="bi bi-envelope me-2"></i> Surat Saya
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item py-2 text-danger" href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Flash toast --}}
<div class="flash-container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius:10px; font-size:13px; border:none;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius:10px; font-size:13px; border:none;">
            <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

{{-- Main --}}
<main class="main-content">
    @yield('content')
</main>

{{-- Hidden forms --}}
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
<form id="readall-form" action="{{ route('user.notif.readAll') }}" method="POST" class="d-none">@csrf</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Auto-dismiss flash setelah 4 detik
    setTimeout(() => {
        document.querySelectorAll('.flash-container .alert').forEach(el => {
            new bootstrap.Alert(el).close();
        });
    }, 4000);
</script>
@stack('scripts')
</body>
</html>