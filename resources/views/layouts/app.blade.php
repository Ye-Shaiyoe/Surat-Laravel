<!DOCTYPE html>
<<<<<<< HEAD
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
=======
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Layanan Ketatausahaan</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial,sans-serif;}
body{background:#f5f7ff;display:flex;min-height:100vh;}

.sidebar{width:230px;background:#1e40af;color:white;display:flex;flex-direction:column;position:fixed;top:0;left:0;bottom:0;z-index:50;}
.logo{padding:18px 16px;border-bottom:1px solid rgba(255,255,255,0.12);}
.logo-title{font-weight:700;font-size:0.9rem;line-height:1.3;}
.logo-sub{font-size:0.64rem;opacity:0.5;margin-top:2px;}
.nav{flex:1;padding:10px 8px;overflow-y:auto;}
.nav-label{font-size:0.6rem;opacity:0.4;text-transform:uppercase;letter-spacing:0.08em;padding:0 8px;margin:12px 0 4px;}
.nav a{display:flex;align-items:center;gap:9px;padding:9px 10px;border-radius:7px;color:rgba(255,255,255,0.7);font-size:0.82rem;text-decoration:none;margin-bottom:2px;}
.nav a:hover,.nav a.active{background:rgba(255,255,255,0.15);color:white;font-weight:600;}
.user{padding:12px;border-top:1px solid rgba(255,255,255,0.12);}
.user-name{font-size:0.82rem;font-weight:700;}
.user-role{font-size:0.67rem;opacity:0.55;margin-top:1px;}
.user-jabatan{font-size:0.65rem;opacity:0.4;margin-top:1px;}
.btn-logout{display:block;margin-top:8px;padding:7px;background:rgba(255,255,255,0.08);border-radius:7px;color:rgba(255,255,255,0.7);font-size:0.77rem;text-align:center;border:none;width:100%;cursor:pointer;}
.btn-logout:hover{background:rgba(239,68,68,0.4);color:white;}

.main{margin-left:230px;flex:1;padding:22px;}
.topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.topbar h1{font-size:1.05rem;font-weight:700;color:#0f172a;}
.topbar p{font-size:0.74rem;color:#64748b;margin-top:1px;}
.notif-wrap{position:relative;}
.notif-btn{background:white;border:1.5px solid #e2e8f8;border-radius:7px;padding:7px 11px;cursor:pointer;font-size:0.9rem;}
.notif-dot{position:absolute;top:4px;right:4px;width:7px;height:7px;background:#ef4444;border-radius:50%;display:none;}

.card{background:white;border-radius:9px;border:1px solid #e2e8f8;margin-bottom:16px;overflow:hidden;}
.card-head{padding:13px 16px;border-bottom:1px solid #e2e8f8;display:flex;align-items:center;justify-content:space-between;font-size:0.88rem;font-weight:700;color:#0f172a;}
.card-body{padding:18px;}

.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:18px;}
.stat{background:white;border-radius:9px;padding:14px 16px;border:1px solid #e2e8f8;display:flex;align-items:center;gap:11px;}
.stat-icon{font-size:1.4rem;}
.stat-num{font-size:1.3rem;font-weight:800;color:#0f172a;}
.stat-label{font-size:0.69rem;color:#64748b;margin-top:1px;}

table{width:100%;border-collapse:collapse;font-size:0.8rem;}
th{padding:9px 13px;text-align:left;background:#f8faff;color:#64748b;font-size:0.68rem;text-transform:uppercase;border-bottom:1px solid #e2e8f8;}
td{padding:10px 13px;border-bottom:1px solid #f0f4ff;color:#1e293b;vertical-align:middle;}
tr:last-child td{border-bottom:none;}
tr:hover td{background:#f8faff;}

.badge{padding:2px 9px;border-radius:20px;font-size:0.67rem;font-weight:700;}
.badge-menunggu{background:#fef3c7;color:#d97706;}
.badge-proses{background:#dbeafe;color:#1d4ed8;}
.badge-selesai{background:#d1fae5;color:#059669;}
.badge-ditolak{background:#fee2e2;color:#dc2626;}
.badge-segera{background:#fee2e2;color:#dc2626;}
.badge-biasa{background:#f1f5f9;color:#475569;}
.badge-rahasia{background:#fde68a;color:#92400e;}

.btn{padding:5px 12px;border-radius:6px;border:none;cursor:pointer;font-size:0.76rem;font-weight:600;display:inline-block;text-decoration:none;}
.btn-primary{background:#1e40af;color:white;}
.btn-primary:hover{background:#1e3a8a;}
.btn-success{background:#10b981;color:white;}
.btn-danger{background:#ef4444;color:white;}
.btn-outline{background:white;border:1.5px solid #1e40af;color:#1e40af;}
.btn-sm{padding:3px 9px;font-size:0.71rem;}

.form-group{margin-bottom:13px;}
.form-label{display:block;font-size:0.72rem;font-weight:700;color:#64748b;margin-bottom:4px;text-transform:uppercase;}
.form-label span{color:#ef4444;}
.form-input{width:100%;padding:9px 12px;border:1.5px solid #e2e8f8;border-radius:7px;font-size:0.84rem;outline:none;font-family:Arial;}
.form-input:focus{border-color:#1e40af;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.upload-box{border:2px dashed #e2e8f8;border-radius:7px;padding:16px;text-align:center;cursor:pointer;background:#f8faff;font-size:0.81rem;color:#64748b;}
.upload-box:hover{border-color:#1e40af;background:#eff4ff;}

.modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.35);z-index:100;align-items:center;justify-content:center;}
.modal-bg.show{display:flex;}
.modal{background:white;border-radius:10px;padding:24px;width:100%;max-width:480px;max-height:90vh;overflow-y:auto;}
.modal h3{margin-bottom:14px;font-size:0.95rem;}
.modal-footer{display:flex;gap:8px;justify-content:flex-end;margin-top:14px;}

.tabs{display:flex;gap:3px;background:white;padding:3px;border-radius:8px;border:1px solid #e2e8f8;width:fit-content;margin-bottom:16px;}
.tab{padding:7px 15px;border-radius:6px;font-size:0.79rem;font-weight:600;color:#64748b;text-decoration:none;}
.tab.active,.tab:hover{background:#1e40af;color:white;}

.tracking{display:flex;flex-direction:column;}
.track-item{display:flex;gap:14px;align-items:flex-start;position:relative;}
.track-item:not(:last-child)::before{content:'';position:absolute;left:13px;top:28px;width:2px;height:calc(100% - 4px);background:#e2e8f8;}
.track-dot{width:27px;height:27px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.72rem;font-weight:700;flex-shrink:0;position:relative;z-index:1;border:2px solid #e2e8f8;}
.track-dot.done{background:#10b981;color:white;border-color:#10b981;}
.track-dot.active{background:#1e40af;color:white;border-color:#1e40af;}
.track-dot.pending{background:white;color:#94a3b8;}
.track-content{padding-bottom:18px;flex:1;}
.track-title{font-size:0.82rem;font-weight:700;}
.track-title.done{color:#059669;}
.track-title.active{color:#1e40af;}
.track-title.pending{color:#94a3b8;}
.track-desc{font-size:0.73rem;color:#64748b;margin-top:2px;}
.track-time{font-size:0.68rem;color:#94a3b8;margin-top:2px;}

.notif-panel{display:none;position:fixed;top:56px;right:22px;width:290px;background:white;border-radius:10px;border:1px solid #e2e8f8;box-shadow:0 8px 24px rgba(0,0,0,0.09);z-index:200;overflow:hidden;}
.notif-panel.show{display:block;}
.notif-head{padding:11px 14px;border-bottom:1px solid #e2e8f8;font-size:0.85rem;font-weight:700;}
.notif-item{padding:10px 14px;border-bottom:1px solid #f0f4ff;font-size:0.79rem;line-height:1.5;}
.notif-item.unread{background:#eff6ff;}

.alert-success{background:#d1fae5;border:1px solid #10b981;border-radius:7px;padding:11px 14px;margin-bottom:14px;font-size:0.81rem;color:#059669;}
.alert-error{background:#fee2e2;border:1px solid #ef4444;border-radius:7px;padding:11px 14px;margin-bottom:14px;font-size:0.81rem;color:#dc2626;}
.empty{text-align:center;padding:28px;color:#94a3b8;font-size:0.8rem;}
.sla-ok{color:#059669;font-weight:700;font-size:0.74rem;}
.sla-late{color:#dc2626;font-weight:700;font-size:0.74rem;}
</style>
</head>
<body>

<div class="sidebar">
  <div class="logo">
    <div class="logo-title">⚖️ Layanan Ketatausahaan</div>
    <div class="logo-sub">Direktorat Metrologi</div>
  </div>
  <div class="nav">
    <div class="nav-label">Menu Utama</div>
    <a href="{{ route('dashboard') }}" {{ request()->routeIs('dashboard') ? 'class=active' : '' }}>🏠 Dashboard</a>

    <div class="nav-label">Persuratan</div>
    <a href="{{ route('persuratan.format') }}" {{ request()->routeIs('persuratan.format') ? 'class=active' : '' }}>📄 Format Surat</a>
    <a href="{{ route('persuratan.pengajuan') }}" {{ request()->routeIs('persuratan.pengajuan*') ? 'class=active' : '' }}>📋 Pengajuan Surat</a>
    <a href="{{ route('persuratan.laporan') }}" {{ request()->routeIs('persuratan.laporan') ? 'class=active' : '' }}>📊 Laporan</a>

    <div class="nav-label">Layanan Lain</div>
<a href="{{ route('layanan.ketatausahaan') }}" {{ request()->routeIs('layanan.ketatausahaan*') ? 'class=active' : '' }}>📝 Layanan Ketatausahaan</a>
<a href="{{ route('layanan.sarana') }}" {{ request()->routeIs('layanan.sarana*') ? 'class=active' : '' }}>🏗️ Sarana & Prasarana</a>
<a href="{{ route('layanan.persediaan') }}" {{ request()->routeIs('layanan.persediaan*') ? 'class=active' : '' }}>📦 Persediaan</a>
<a href="{{ route('layanan.dll') }}" {{ request()->routeIs('layanan.dll*') ? 'class=active' : '' }}>📋 Dll</a>
  </div>
  <div class="user">
    <div class="user-name">{{ auth()->user()->name }}</div>
    <div class="user-role">{{ strtoupper(auth()->user()->role ?? 'Pegawai') }}</div>
    <div class="user-jabatan">{{ auth()->user()->jabatan ?? '-' }}</div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="btn-logout">⬅ Keluar</button>
    </form>
  </div>
</div>

<div class="main">
  <div class="topbar">
    <div>
      <h1>@yield('title')</h1>
      <p>@yield('subtitle')</p>
    </div>
    <div class="notif-wrap">
      <button class="notif-btn" onclick="toggleNotif()">🔔</button>
      <span class="notif-dot" id="notif-dot"></span>
    </div>
  </div>

  @if(session('success'))
  <div class="alert-success">✅ {{ session('success') }}</div>
  @endif
  @if(session('error'))
  <div class="alert-error">❌ {{ session('error') }}</div>
  @endif

  @yield('content')
</div>

<div class="notif-panel" id="notif-panel">
  <div class="notif-head">🔔 Notifikasi</div>
  <div id="notif-list"><div class="empty">Tidak ada notifikasi</div></div>
</div>

<script>
function toggleNotif(){document.getElementById('notif-panel').classList.toggle('show');}
function bukaModal(id){document.getElementById(id).classList.add('show');}
function tutupModal(id){document.getElementById(id).classList.remove('show');}
document.addEventListener('click',e=>{
  const p=document.getElementById('notif-panel');
  if(!p.contains(e.target)&&!e.target.closest('.notif-btn'))p.classList.remove('show');
});
</script>
@yield('scripts')
</body>
</html>
>>>>>>> ae1b02b (Add full Laravel project fresh)
