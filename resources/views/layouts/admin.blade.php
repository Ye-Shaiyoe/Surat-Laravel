<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — Surat Metrologi</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ===== LAYOUT ===== */
        body { display: flex; min-height: 100vh; background: #f3f4f6; font-family: 'Figtree', sans-serif; }

        /* SIDEBAR */
        #sidebar {
            width: 240px; min-height: 100vh; background: #1e3a5f;
            display: flex; flex-direction: column; flex-shrink: 0;
            transition: width 0.2s;
        }
        .sidebar-logo {
            padding: 20px 16px 16px; border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar-logo span {
            font-size: 13px; font-weight: 600; color: #fff; letter-spacing: 0.01em;
        }
        .sidebar-logo small { display: block; font-size: 10px; color: rgba(255,255,255,0.45); margin-top: 2px; }
        .sidebar-menu { flex: 1; padding: 12px 0; }
        .menu-label {
            font-size: 10px; font-weight: 600; color: rgba(255,255,255,0.35);
            letter-spacing: 0.08em; text-transform: uppercase;
            padding: 10px 16px 4px;
        }
        .menu-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 16px; color: rgba(255,255,255,0.65);
            text-decoration: none; font-size: 13px; font-weight: 500;
            border-left: 3px solid transparent;
            transition: all 0.15s;
        }
        .menu-item:hover { background: rgba(255,255,255,0.07); color: #fff; }
        .menu-item.active { background: rgba(255,255,255,0.1); color: #fff; border-left-color: #60a5fa; }
        .menu-icon { width: 18px; text-align: center; font-size: 15px; }
        .sidebar-user {
            padding: 12px 16px; border-top: 1px solid rgba(255,255,255,0.08);
            font-size: 12px; color: rgba(255,255,255,0.55);
        }
        .sidebar-user strong { display: block; color: #fff; font-size: 13px; }

        /* MAIN AREA */
        #main { flex: 1; display: flex; flex-direction: column; min-width: 0; }

        /* TOPBAR */
        #topbar {
            background: #fff; border-bottom: 1px solid #e5e7eb;
            padding: 0 24px; height: 56px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 10;
        }
        .topbar-title { font-size: 15px; font-weight: 600; color: #111827; }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .topbar-badge {
            position: relative; font-size: 18px; cursor: pointer; color: #6b7280;
        }
        .notif-dot {
            position: absolute; top: 0; right: 0;
            width: 8px; height: 8px; background: #ef4444; border-radius: 50%;
            border: 2px solid #fff;
        }
        .topbar-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: #1e3a5f; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 600; cursor: pointer;
        }
        .dropdown { position: relative; }
        .dropdown-menu {
            display: none; position: absolute; right: 0; top: 40px;
            background: #fff; border: 1px solid #e5e7eb; border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            min-width: 160px; z-index: 99; overflow: hidden;
        }
        .dropdown:hover .dropdown-menu { display: block; }
        .dropdown-menu a {
            display: block; padding: 9px 14px; font-size: 13px;
            color: #374151; text-decoration: none;
        }
        .dropdown-menu a:hover { background: #f9fafb; }
        .dropdown-menu hr { border-color: #f3f4f6; margin: 0; }

        /* CONTENT */
        #content { padding: 24px; flex: 1; }

        /* CARDS */
        .card { background: #fff; border-radius: 10px; border: 1px solid #e5e7eb; padding: 20px; }
        .stat-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
        .stat-card {
            background: #fff; border: 1px solid #e5e7eb; border-radius: 10px;
            padding: 16px 20px;
        }
        .stat-label { font-size: 12px; color: #6b7280; margin-bottom: 4px; }
        .stat-value { font-size: 26px; font-weight: 700; color: #111827; line-height: 1; }
        .stat-sub { font-size: 11px; color: #9ca3af; margin-top: 4px; }
        .stat-card.blue .stat-value { color: #1d4ed8; }
        .stat-card.green .stat-value { color: #15803d; }
        .stat-card.amber .stat-value { color: #b45309; }
        .stat-card.red .stat-value { color: #b91c1c; }

        /* TABLES */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; }
        thead th {
            text-align: left; padding: 10px 12px;
            background: #f9fafb; color: #6b7280;
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.05em; border-bottom: 1px solid #e5e7eb;
        }
        tbody td { padding: 11px 12px; border-bottom: 1px solid #f3f4f6; color: #374151; }
        tbody tr:hover td { background: #f9fafb; }
        tbody tr:last-child td { border-bottom: none; }

        /* BADGES */
        .badge {
            display: inline-block; font-size: 11px; font-weight: 600;
            padding: 2px 8px; border-radius: 99px;
        }
        .badge-blue   { background: #dbeafe; color: #1d4ed8; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-amber  { background: #fef3c7; color: #b45309; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .badge-gray   { background: #f3f4f6; color: #6b7280; }
        .badge-purple { background: #ede9fe; color: #6d28d9; }

        /* BUTTONS */
        .btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 7px 14px; border-radius: 7px; font-size: 13px;
            font-weight: 500; cursor: pointer; border: 1px solid #e5e7eb;
            background: #fff; color: #374151; text-decoration: none;
            transition: all 0.15s;
        }
        .btn:hover { background: #f9fafb; }
        .btn-primary { background: #1e3a5f; color: #fff; border-color: #1e3a5f; }
        .btn-primary:hover { background: #16304f; }
        .btn-sm { padding: 5px 10px; font-size: 12px; }
        .btn-success { background: #15803d; color: #fff; border-color: #15803d; }
        .btn-danger { background: #b91c1c; color: #fff; border-color: #b91c1c; }

        /* SLA bar */
        .sla-bar { height: 4px; background: #e5e7eb; border-radius: 99px; overflow: hidden; width: 80px; }
        .sla-fill { height: 100%; border-radius: 99px; }

        /* Progress */
        .progress-bar { height: 6px; background: #e5e7eb; border-radius: 99px; overflow: hidden; }
        .progress-fill { height: 100%; background: #1e3a5f; border-radius: 99px; }

        /* Section header */
        .section-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 16px;
        }
        .section-header h2 { font-size: 15px; font-weight: 600; color: #111827; }
        .section-header small { font-size: 12px; color: #6b7280; }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        @media (max-width: 768px) {
            .dashboard-grid { grid-template-columns: 1fr; }
        }

        /* Responsive */
        @media (max-width: 768px) {
            #sidebar { width: 0; overflow: hidden; position: fixed; z-index: 50; }
            .stat-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>

{{-- ============ SIDEBAR ============ --}}
<aside id="sidebar">
    <div class="sidebar-logo">
        <span>⚖️ Surat Metrologi</span>
        <small>Balai Metrologi Legal</small>
    </div>

    <nav class="sidebar-menu">
        <div class="menu-label">Utama</div>
        <a href="{{ route('admin.dashboard') }}"
           class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="menu-icon">📊</span> Dashboard
        </a>
        <a href="{{ route('admin.surat.index') }}"
           class="menu-item {{ request()->routeIs('admin.surat.*') ? 'active' : '' }}">
            <span class="menu-icon">📬</span> Antrian Surat
            @if($antrianCount ?? 0)
                <span class="badge badge-red" style="margin-left:auto;font-size:10px;">{{ $antrianCount }}</span>
            @endif
        </a>

        <div class="menu-label">Laporan</div>
        <a href="{{ route('admin.laporan.index') }}"
           class="menu-item {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
            <span class="menu-icon">📋</span> Rekap Bulanan
        </a>

        <div class="menu-label">Pengaturan</div>
        <a href="{{ route('admin.template.index') }}"
           class="menu-item {{ request()->routeIs('admin.template.*') ? 'active' : '' }}">
            <span class="menu-icon">📄</span> Template Surat
        </a>
        <a href="{{ route('admin.users.index') }}"
           class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <span class="menu-icon">👥</span> Data Pegawai
        </a>
    </nav>

    <div class="sidebar-user">
        <strong>{{ Auth::user()->name }}</strong>
        Administrator
    </div>
</aside>

{{-- ============ MAIN ============ --}}
<div id="main">

    {{-- TOPBAR --}}
    <header id="topbar">
        <div class="topbar-title">{{ $title ?? 'Dashboard' }}</div>
        <div class="topbar-right">
            <span class="topbar-badge">
                🔔 <span class="notif-dot"></span>
            </span>
            <div class="dropdown">
                <div class="topbar-avatar">
                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                </div>
                <div class="dropdown-menu">
                    <a href="{{ route('profile.edit') }}">👤 Profil</a>
                    <hr>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        🚪 Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- FLASH MESSAGE --}}
    @if (session('success'))
        <div style="margin:16px 24px 0; background:#dcfce7; border:1px solid #86efac; border-radius:8px; padding:10px 16px; font-size:13px; color:#15803d;">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div style="margin:16px 24px 0; background:#fee2e2; border:1px solid #fca5a5; border-radius:8px; padding:10px 16px; font-size:13px; color:#b91c1c;">
            ❌ {{ session('error') }}
        </div>
    @endif

    {{-- CONTENT --}}
    <main id="content">
        @yield('content')
    </main>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none">
    @csrf
</form>

</body>
</html>