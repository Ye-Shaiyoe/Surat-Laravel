<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>BPSUML Sistem Administrasi Digital</title>
    <meta name="description" content="Sistem Monitoring dan Pengelolaan Administrasi Balai Pengelolaan Standar Ukuran Metrologi Legal transparan, akuntabel, dan efisien.">
    <link rel="icon" href="{{ asset('images/metrologi.png') }}" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}?v=21">
</head>
<body>

    {{-- BACKGROUND WATERMARK --}}
    <div class="watermark-bg" aria-hidden="true">
        <img src="{{ asset('images/Logo SMK ALFALAH.png') }}" alt="Watermark SMK Al Falah">
    </div>

    {{-- MOBILE OVERLAY --}}
    <div class="nav-overlay" id="nav-overlay"></div>

    {{-- TOP BAR --}}
    <div class="topbar">
        <div class="container topbar-inner">
            <div class="topbar-left">
                <span class="topbar-live">
                    <span class="pulse"></span>
                    Sistem Aktif
                </span>
                <span class="topbar-divider hide-xs"></span>
                <span class="topbar-inst hide-xs">
                    <i class="bi bi-building"></i>
                    <span class="hide-sm">Kementerian Perdagangan RI · </span>Direktorat Metrologi
                </span>
            </div>
            <div class="topbar-right">
                <a href="https://metrologi.kemendag.go.id/" target="_blank" rel="noopener" aria-label="Portal Metrologi">
                    <i class="bi bi-globe2"></i><span class="hide-xs">Portal Metrologi</span>
                </a>
                <a href="{{ route('panduan') }}" target="_blank" aria-label="Panduan">
                    <i class="bi bi-book"></i><span class="hide-xs">Panduan</span>
                </a>
                <a href="https://ye-shaiyoe.github.io/Docs-adminstrasi-bpsuml/" target="_blank" rel="noopener" aria-label="Dokumentasi">
                    <i class="bi bi-file-earmark-code"></i><span class="hide-xs">Dokumentasi</span>
                </a>
                <a href="mailto:tubpsuml@gmail.com" class="hide-md" aria-label="Email">
                    <i class="bi bi-envelope"></i><span>tubpsuml@gmail.com</span>
                </a>
            </div>
        </div>
    </div>

    {{-- NAVBAR --}}
    <header class="navbar" id="navbar">
        <div class="container navbar-inner">
            <a href="{{ url('/?home=1') }}" class="brand">
                <img src="{{ asset('images/BP_SUML2.png') }}" alt="BPSUML" class="brand-logo">
                <div class="brand-text">
                    <strong>BPSUML</strong>
                    <span>Sistem Administrasi Digital</span>
                </div>
            </a>

            <nav class="nav-links" id="nav-links" aria-label="Navigasi utama">
                <div class="nav-links-head">
                    <span>Menu Navigasi</span>
                    <button type="button" class="nav-close" id="nav-close" aria-label="Tutup menu">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                <a href="#beranda" class="nav-item">Beranda</a>
                <a href="#tentang" class="nav-item">Tentang</a>
                <a href="#layanan" class="nav-item">Layanan</a>
                <a href="#alur" class="nav-item">Alur Kerja</a>
                <a href="#kinerja" class="nav-item">Kinerja</a>
                <a href="#keamanan" class="nav-item">Keamanan</a>
                <a href="#faq" class="nav-item">FAQ</a>
                <a href="#kontak" class="nav-item">Kontak</a>

                {{-- Portal & Guide Quick Links inside Mobile Hamburger Drawer --}}
                <div class="nav-links-extra">
                    <span class="nav-extra-title">Layanan & Portal</span>
                    <a href="https://metrologi.kemendag.go.id/" target="_blank" rel="noopener" class="nav-extra-item">
                        <i class="bi bi-globe2"></i>
                        <span>Portal Metrologi</span>
                        <i class="bi bi-box-arrow-up-right ms-auto"></i>
                    </a>
                    <a href="{{ route('panduan') }}" target="_blank" class="nav-extra-item">
                        <i class="bi bi-book"></i>
                        <span>Panduan Sistem</span>
                        <i class="bi bi-box-arrow-up-right ms-auto"></i>
                    </a>
                    <a href="https://ye-shaiyoe.github.io/Docs-adminstrasi-bpsuml/" target="_blank" rel="noopener" class="nav-extra-item">
                        <i class="bi bi-file-earmark-code"></i>
                        <span>Dokumentasi Docs</span>
                        <i class="bi bi-box-arrow-up-right ms-auto"></i>
                    </a>
                </div>

                <div class="nav-links-cta">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-block">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-ghost btn-block">Masuk</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-block">Daftar Akun</a>
                    @endauth
                </div>
            </nav>

            <div class="nav-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm hide-mobile-btn">
                        <span class="nav-user-dot"></span>
                        Dashboard
                        <i class="bi bi-arrow-right"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-sm hide-mobile-btn">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm hide-mobile-btn">Daftar</a>
                @endauth
                <button type="button" class="menu-toggle" id="menu-toggle" aria-label="Buka menu" aria-expanded="false">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    {{-- HERO --}}
    <section class="hero" id="beranda">
        <div class="hero-bg">
            <div class="hero-orb hero-orb-1"></div>
            <div class="hero-orb hero-orb-2"></div>
            <div class="hero-grid-lines"></div>
        </div>

        <div class="container hero-grid">
            <div class="hero-content reveal">
                <div class="hero-eyebrow">
                    <span class="hero-badge">
                        <span class="hero-badge-dot"></span>
                        Unit Pelaksana Teknis
                    </span>
                    <span class="hero-year">Direktorat Metrologi · {{ date('Y') }}</span>
                </div>

                <h1>
                    Sistem Administrasi Digital<br>
                    <em>BPSUML</em>
                </h1>

                <p class="hero-lead">
                    Platform resmi untuk pengajuan, approval berjenjang, monitoring SLA,
                    dan arsip surat di lingkungan Balai Pengelolaan Standar Ukuran Metrologi Legal.
                </p>

                <div class="hero-cta">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-gold btn-lg">
                            Buka Dashboard
                            <i class="bi bi-box-arrow-in-right"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-gold btn-lg">
                            Masuk ke Sistem
                            <i class="bi bi-box-arrow-in-right"></i>
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline btn-lg">Daftar Sebagai Pegawai</a>
                    @endauth
                    <a href="#alur" class="btn btn-text btn-lg">
                        Lihat alur kerja
                        <i class="bi bi-arrow-down"></i>
                    </a>
                </div>

                <div class="hero-trust-row">
                    <div class="hero-trust-item">
                        <i class="bi bi-shield-check"></i>
                        <div>
                            <strong>Role-based Access</strong>
                            <span>Hak akses berjenjang</span>
                        </div>
                    </div>
                    <div class="hero-trust-item">
                        <i class="bi bi-hourglass-split"></i>
                        <div>
                            <strong>SLA 1 Hari Kerja</strong>
                            <span>Monitoring real-time</span>
                        </div>
                    </div>
                    <div class="hero-trust-item">
                        <i class="bi bi-qr-code"></i>
                        <div>
                            <strong>Verifikasi QR</strong>
                            <span>Autentikasi dokumen</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-panel reveal reveal-delay-1">
                <div class="hero-panel-card">
                    <div class="hero-panel-header">
                        <div class="hero-panel-logo">
                            <img src="{{ asset('images/metrologi.png') }}" alt="Metrologi">
                        </div>
                        <div>
                            <strong>Ringkasan Operasional</strong>
                            <span>Data live dari sistem · cache 5 menit</span>
                        </div>
                        <span class="status-chip">
                            <span class="pulse"></span> Live
                        </span>
                    </div>

                    <div class="hero-panel-stats">
                        <div class="hps-item">
                            <div class="hps-icon"><i class="bi bi-inbox"></i></div>
                            <b data-count="{{ $totalSuratMasuk }}">0</b>
                            <span>Surat Masuk</span>
                        </div>
                        <div class="hps-item">
                            <div class="hps-icon"><i class="bi bi-check2-circle"></i></div>
                            <b data-count="{{ $totalDokumenTerarsip }}">0</b>
                            <span>Surat Selesai</span>
                        </div>
                        <div class="hps-item">
                            <div class="hps-icon"><i class="bi bi-people"></i></div>
                            <b data-count="{{ $totalPengguna }}">0</b>
                            <span>Pengguna</span>
                        </div>
                        <div class="hps-item">
                            <div class="hps-icon"><i class="bi bi-star-fill"></i></div>
                            <b data-count="{{ number_format($averageRating, 1) }}" data-decimal="1">0.0</b>
                            <span>Rating / 5</span>
                        </div>
                    </div>

                    <div class="hero-panel-footer">
                        <div class="hpf-growth {{ $growth >= 0 ? 'up' : 'down' }}">
                            <i class="bi bi-{{ $growth >= 0 ? 'graph-up-arrow' : 'graph-down-arrow' }}"></i>
                            {{ $growth >= 0 ? '+' : '' }}{{ $growth }}% vs bulan lalu
                        </div>
                        <a href="#kinerja" class="hpf-link">
                            Detail kinerja <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="hero-scroll-hint">
            <span>Scroll</span>
            <div class="scroll-line"></div>
        </div>
    </section>

    {{-- TRUST / INSTITUTION BAR --}}
    <section class="trust-bar">
        <div class="container trust-bar-inner">
            <span class="trust-label">Di bawah naungan</span>
            <div class="trust-logos">
                <div class="trust-item">
                    <img src="{{ asset('images/kemendag.png') }}" alt="Kemendag" onerror="this.style.display='none'">
                    <span>Kementerian Perdagangan RI</span>
                </div>
                <div class="trust-item">
                    <img src="{{ asset('images/Logo-direktorat.jpg') }}" alt="Direktorat Metrologi" onerror="this.style.display='none'">
                    <span>Direktorat Metrologi</span>
                </div>
                <div class="trust-item">
                    <img src="{{ asset('images/metrologi.png') }}" alt="BPSUML">
                    <span>BPSUML Bandung</span>
                </div>
                <div class="trust-item trust-item-text">
                    <i class="bi bi-patch-check-fill"></i>
                    <span>SPBE · Digital Governance</span>
                </div>
            </div>
        </div>
    </section>

    {{-- STATS STRIP --}}
    <section class="stats-strip" id="statistik">
        <div class="container">
            <div class="stats-strip-grid">
                <div class="stat-item reveal">
                    <div class="stat-icon"><i class="bi bi-envelope-arrow-down"></i></div>
                    <div>
                        <div class="stat-num" data-count="{{ $totalSuratMasuk }}">0</div>
                        <div class="stat-label">Total Surat Masuk</div>
                    </div>
                    <span class="stat-tag">Kumulatif</span>
                </div>
                <div class="stat-item reveal reveal-delay-1">
                    <div class="stat-icon green"><i class="bi bi-archive"></i></div>
                    <div>
                        <div class="stat-num" data-count="{{ $totalDokumenTerarsip }}">0</div>
                        <div class="stat-label">Dokumen Terarsip</div>
                    </div>
                    <span class="stat-tag success">Selesai</span>
                </div>
                <div class="stat-item reveal reveal-delay-2">
                    <div class="stat-icon blue"><i class="bi bi-person-badge"></i></div>
                    <div>
                        <div class="stat-num" data-count="{{ $totalPengguna }}">0</div>
                        <div class="stat-label">Pengguna Terdaftar</div>
                    </div>
                    <span class="stat-tag">Pegawai</span>
                </div>
                <div class="stat-item reveal reveal-delay-3">
                    <div class="stat-icon gold"><i class="bi bi-star"></i></div>
                    <div>
                        <div class="stat-num">
                            <span data-count="{{ number_format($averageRating, 1) }}" data-decimal="1">0.0</span><small>/5</small>
                        </div>
                        <div class="stat-label">Kepuasan Layanan</div>
                    </div>
                    <span class="stat-tag warn">Rating</span>
                </div>
            </div>
        </div>
    </section>

    {{-- TENTANG --}}
    <section class="section" id="tentang">
        <div class="container about-grid">
            <div class="about-media reveal">
                <div class="about-collage">
                    <figure class="about-photo about-photo-main">
                        <img src="{{ asset('images/about/kantor_suml.jpg') }}" alt="Lobby BPSUML"
                             loading="lazy"
                             onerror="this.src='{{ asset('images/about/building.png') }}'">
                        <figcaption class="about-photo-badge">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>Jl. Pasteur No. 27, Bandung</span>
                        </figcaption>
                    </figure>

                    <figure class="about-photo about-photo-top">
                        <img src="{{ asset('images/about/Modern.jpg') }}" alt="Ruang kerja BPSUML"
                             loading="lazy"
                             onerror="this.src='{{ asset('images/about/office.png') }}'">
                        <figcaption class="about-photo-label">Ruang Kerja</figcaption>
                    </figure>

                    <figure class="about-photo about-photo-bottom">
                        <img src="{{ asset('images/kolaborasi.jpg') }}" alt="Tim BPSUML"
                             loading="lazy"
                             onerror="this.src='{{ asset('images/kolaborasi.jpg') }}'">
                        <figcaption class="about-photo-label">Tim &amp; Kolaborasi</figcaption>
                    </figure>
                </div>

                <div class="about-stat-strip">
                    <div class="about-stat-item">
                        <strong data-count="{{ $totalSuratMasuk }}">0</strong>
                        <span>Surat masuk</span>
                    </div>
                    <div class="about-stat-item">
                        <strong data-count="{{ $totalSuratKeluar }}">0</strong>
                        <span>Surat keluar</span>
                    </div>
                    <div class="about-stat-item">
                        <strong data-count="{{ $totalDokumenTerarsip }}">0</strong>
                        <span>Terarsip</span>
                    </div>
                    <div class="about-stat-item">
                        <strong data-count="{{ $totalPengguna }}">0</strong>
                        <span>Pengguna</span>
                    </div>
                </div>
            </div>

            <div class="about-content reveal reveal-delay-1">
                <span class="section-label">Tentang Instansi</span>
                <h2>Balai Pengelolaan Standar Ukuran Metrologi Legal</h2>
                <p class="about-lead">
                    Unit pelaksana teknis Direktorat Metrologi, Kementerian Perdagangan RI —
                    menyelenggarakan pengelolaan, kalibrasi, dan tera ulang alat ukur.
                </p>
                <p>
                    Sistem administrasi digital ini mendukung tata kelola persuratan yang
                    <strong>transparan, akuntabel, dan efisien</strong> sesuai prinsip birokrasi modern.
                </p>

                <div class="value-grid">
                    <div class="value-card">
                        <i class="bi bi-eye"></i>
                        <strong>Transparan</strong>
                        <span>Status surat dapat dilacak setiap tahap</span>
                    </div>
                    <div class="value-card">
                        <i class="bi bi-journal-check"></i>
                        <strong>Akuntabel</strong>
                        <span>Log aktivitas &amp; jejak approval lengkap</span>
                    </div>
                    <div class="value-card">
                        <i class="bi bi-lightning"></i>
                        <strong>Efisien</strong>
                        <span>Paperless, cepat, terpusat di satu sistem</span>
                    </div>
                    <div class="value-card">
                        <i class="bi bi-lock"></i>
                        <strong>Aman</strong>
                        <span>Akses terkontrol sesuai peran pejabat</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- LAYANAN --}}
    <section class="section section-alt" id="layanan">
        <div class="container">
            <div class="section-head reveal">
                <span class="section-label">Layanan Sistem</span>
                <h2>Fitur Utama yang Siap Dipakai</h2>
                <p>Dirancang untuk alur kerja pemerintahan jelas, terukur, dan mudah diaudit.</p>
            </div>

            <div class="cards-grid">
                @php
                    $features = [
                        ['icon' => 'bi-envelope-paper', 'title' => 'Pengajuan Surat', 'desc' => 'Ajukan surat masuk, keluar, dan nota dinas dengan template resmi yang sudah disiapkan.', 'tag' => 'Inti'],
                        ['icon' => 'bi-diagram-3', 'title' => 'Alur Approval', 'desc' => 'Verifikasi berjenjang dari arsiparis hingga pejabat berwenang, sesuai hierarki organisasi.', 'tag' => 'Workflow'],
                        ['icon' => 'bi-speedometer2', 'title' => 'Monitoring SLA', 'desc' => 'Hitung mundur waktu layanan agar setiap dokumen diproses dalam batas waktu yang ditetapkan.', 'tag' => 'SLA'],
                        ['icon' => 'bi-qr-code-scan', 'title' => 'Verifikasi QR', 'desc' => 'Autentikasi keaslian dokumen final melalui QR Code yang terintegrasi pada surat.', 'tag' => 'Keamanan'],
                        ['icon' => 'bi-bar-chart-line', 'title' => 'Statistik & Laporan', 'desc' => 'Visualisasi data surat untuk mendukung pelaporan dan keputusan berbasis data.', 'tag' => 'Analitik'],
                        ['icon' => 'bi-bell', 'title' => 'Notifikasi Real-time', 'desc' => 'Pemberitahuan status approval, revisi, dan tenggat SLA langsung ke pengguna terkait.', 'tag' => 'Realtime'],
                        ['icon' => 'bi-folder2-open', 'title' => 'Arsip Digital', 'desc' => 'Penyimpanan terpusat dokumen selesai dengan akses terkontrol dan riwayat lengkap.', 'tag' => 'Arsip'],
                        ['icon' => 'bi-shield-lock', 'title' => 'Kontrol Akses', 'desc' => 'Hak akses berbasis peran (role-based) agar setiap pejabat hanya melihat yang relevan.', 'tag' => 'RBAC'],
                        ['icon' => 'bi-file-earmark-text', 'title' => 'Template Resmi', 'desc' => 'Gunakan template surat standar instansi untuk mempercepat penyusunan dokumen.', 'tag' => 'Template'],
                    ];
                @endphp
                @foreach($features as $i => $f)
                    <article class="card reveal reveal-delay-{{ $i % 3 }}">
                        <div class="card-top">
                            <div class="card-icon"><i class="bi {{ $f['icon'] }}"></i></div>
                            <span class="card-tag">{{ $f['tag'] }}</span>
                        </div>
                        <h3>{{ $f['title'] }}</h3>
                        <p>{{ $f['desc'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ALUR KERJA --}}
    <section class="section" id="alur">
        <div class="container">
            <div class="section-head reveal">
                <span class="section-label">Prosedur</span>
                <h2>Alur Kerja Administrasi Digital</h2>
                <p>Dari pengajuan hingga arsip setiap tahap tercatat dan dapat dilacak.</p>
            </div>

            <div class="workflow reveal">
                <div class="workflow-track" aria-hidden="true"></div>
                @php
                    $steps = [
                        ['num' => '01', 'title' => 'Pengajuan', 'desc' => 'Pegawai mengajukan usulan surat beserta draf, lampiran, dan data pendukung.', 'role' => 'Pengaju', 'icon' => 'bi-send'],
                        ['num' => '02', 'title' => 'Verifikasi', 'desc' => 'Arsiparis & pejabat memeriksa kelengkapan format serta substansi dokumen.', 'role' => 'Arsiparis / Kasubbag', 'icon' => 'bi-search'],
                        ['num' => '03', 'title' => 'Persetujuan', 'desc' => 'Pejabat berwenang memberikan approval sesuai hierarki dan kewenangan.', 'role' => 'Pejabat', 'icon' => 'bi-pen'],
                        ['num' => '04', 'title' => 'Arsip Digital', 'desc' => 'Dokumen final tersimpan aman, dapat diunduh, dan diverifikasi via QR.', 'role' => 'Sistem', 'icon' => 'bi-archive'],
                    ];
                @endphp
                @foreach($steps as $s)
                    <div class="workflow-step">
                        <div class="workflow-icon">
                            <i class="bi {{ $s['icon'] }}"></i>
                        </div>
                        <span class="workflow-num">{{ $s['num'] }}</span>
                        <h3>{{ $s['title'] }}</h3>
                        <p>{{ $s['desc'] }}</p>
                        <span class="workflow-role">{{ $s['role'] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="workflow-note reveal">
                <i class="bi bi-info-circle-fill"></i>
                <p>
                    Alur lengkap dapat mencapai <strong>hingga 10 tahap approval</strong> tergantung jenis surat
                    dan hierarki pejabat. Setiap aksi tercatat di log aktivitas sistem.
                </p>
            </div>
        </div>
    </section>

    {{-- KINERJA / SLA --}}
    <section class="section section-alt" id="kinerja">
        <div class="container">
            <div class="section-head reveal">
                <span class="section-label">Kinerja Sistem</span>
                <h2>Data yang Membangun Kepercayaan</h2>
                <p>Ringkasan distribusi jenis surat dan kepatuhan SLA bulan berjalan.</p>
            </div>

            <div class="kinerja-grid">
                {{-- Distribusi jenis --}}
                <div class="kinerja-card reveal">
                    <div class="kinerja-card-head">
                        <div>
                            <h3>Distribusi Jenis Surat</h3>
                            <p>Proporsi dokumen per kategori</p>
                        </div>
                        <span class="badge-soft">{{ count($doughnutLabels) }} jenis</span>
                    </div>
                    <div class="dist-list">
                        @php
                            $colors = ['#1a56db', '#0d9488', '#c9a227', '#7c3aed', '#e11d48', '#0891b2', '#ea580c', '#4f46e5'];
                            $maxPct = max(array_column($doughnutData, 'pct') ?: [1]);
                        @endphp
                        @foreach($doughnutLabels as $i => $label)
                            @php
                                $pct = $doughnutData[$i]['pct'] ?? 0;
                                $count = $doughnutData[$i]['count'] ?? 0;
                                $color = $colors[$i % count($colors)];
                                $barW = $maxPct > 0 ? max(4, ($pct / max($maxPct, 1)) * 100) : 4;
                            @endphp
                            <div class="dist-row">
                                <div class="dist-meta">
                                    <span class="dist-dot" style="background:{{ $color }}"></span>
                                    <span class="dist-name">{{ $label }}</span>
                                    <span class="dist-count">{{ number_format($count) }}</span>
                                    <span class="dist-pct">{{ $pct }}%</span>
                                </div>
                                <div class="dist-bar">
                                    <div class="dist-bar-fill" style="width:{{ $barW }}%;background:{{ $color }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- SLA per jenis --}}
                <div class="kinerja-card reveal reveal-delay-1">
                    <div class="kinerja-card-head">
                        <div>
                            <h3>Kepatuhan SLA per Jenis</h3>
                            <p>% tepat waktu · bulan ini</p>
                        </div>
                        <span class="badge-soft success">SLA</span>
                    </div>
                    <div class="sla-list">
                        @foreach($slaPerJenis as $sla)
                            <div class="sla-row">
                                <div class="sla-meta">
                                    <span class="sla-name">{{ $sla['name'] }}</span>
                                    <span class="sla-pct" style="color:{{ $sla['color'] }}">{{ $sla['pct'] }}%</span>
                                </div>
                                <div class="sla-bar">
                                    <div class="sla-bar-fill" style="width:{{ $sla['pct'] }}%;background:{{ $sla['color'] }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Mini KPI row --}}
            <div class="kpi-row">
                <div class="kpi-card reveal">
                    <i class="bi bi-arrow-{{ $growth >= 0 ? 'up' : 'down' }}-right"></i>
                    <div>
                        <strong>{{ $growth >= 0 ? '+' : '' }}{{ $growth }}%</strong>
                        <span>Pertumbuhan surat bulan ini</span>
                    </div>
                </div>
                <div class="kpi-card reveal reveal-delay-1">
                    <i class="bi bi-send-check"></i>
                    <div>
                        <strong data-count="{{ $totalSuratKeluar }}">0</strong>
                        <span>Surat keluar terproses</span>
                    </div>
                </div>
                <div class="kpi-card reveal reveal-delay-2">
                    <i class="bi bi-clock-history"></i>
                    <div>
                        <strong>30 jam</strong>
                        <span>Target SLA jam kerja</span>
                    </div>
                </div>
                <div class="kpi-card reveal reveal-delay-3">
                    <i class="bi bi-layers"></i>
                    <div>
                        <strong>10 tahap</strong>
                        <span>Maksimum alur approval</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- KEAMANAN --}}
    <section class="section" id="keamanan">
        <div class="container">
            <div class="security-layout">
                <div class="security-content reveal">
                    <span class="section-label">Keamanan &amp; Tata Kelola</span>
                    <h2>Dibangun untuk lingkungan pemerintahan</h2>
                    <p>
                        Setiap akses dan aksi di sistem dirancang agar dapat diaudit.
                        Hak pengguna dibatasi sesuai peran, sehingga data surat tetap terkendali.
                    </p>
                    <ul class="check-list">
                        <li><i class="bi bi-check-circle-fill"></i> Autentikasi pengguna &amp; sesi aman</li>
                        <li><i class="bi bi-check-circle-fill"></i> Role-based access control (RBAC)</li>
                        <li><i class="bi bi-check-circle-fill"></i> Log aktivitas untuk jejak audit</li>
                        <li><i class="bi bi-check-circle-fill"></i> Verifikasi dokumen via QR Code</li>
                        <li><i class="bi bi-check-circle-fill"></i> Monitoring tenggat SLA real-time</li>
                    </ul>
                </div>

                <div class="security-grid reveal reveal-delay-1">
                    <div class="sec-card">
                        <div class="sec-icon"><i class="bi bi-shield-lock"></i></div>
                        <strong>Kontrol Akses</strong>
                        <span>Admin, pejabat, dan pegawai melihat data sesuai kewenangan.</span>
                    </div>
                    <div class="sec-card">
                        <div class="sec-icon"><i class="bi bi-fingerprint"></i></div>
                        <strong>Jejak Digital</strong>
                        <span>Setiap approval dan revisi tercatat dengan waktu &amp; aktor.</span>
                    </div>
                    <div class="sec-card">
                        <div class="sec-icon"><i class="bi bi-qr-code"></i></div>
                        <strong>QR Verification</strong>
                        <span>Pihak eksternal dapat memverifikasi keaslian dokumen final.</span>
                    </div>
                    <div class="sec-card">
                        <div class="sec-icon"><i class="bi bi-hdd-stack"></i></div>
                        <strong>Arsip Terpusat</strong>
                        <span>Dokumen tersimpan rapi, mudah dicari, dan siap dilapor.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- PORTAL TERKAIT --}}
    <section class="section section-alt" id="portal">
        <div class="container">
            <div class="section-head reveal">
                <span class="section-label">Ekosistem</span>
                <h2>Portal &amp; Layanan Terkait</h2>
                <p>Akses cepat ke layanan pendukung di lingkungan Metrologi &amp; Kemendag.</p>
            </div>

            <div class="portal-grid">
                @php
                    $portals = [
                        ['name' => 'SIMET', 'desc' => 'Sistem Informasi Metrologi', 'url' => 'https://metrologi.kemendag.go.id/', 'icon' => 'bi-globe2'],
                        ['name' => 'KEMENDAG', 'desc' => 'Kementerian Perdagangan', 'url' => 'https://www.kemendag.go.id/', 'icon' => 'bi-building'],
                        ['name' => 'SISWASPK', 'desc' => 'Informasi Aplikasi SPBE', 'url' => 'https://simpktn.kemendag.go.id/index.php/siswaspk', 'icon' => 'bi-pc-display'],
                        ['name' => 'PPID', 'desc' => 'Informasi & Dokumentasi', 'url' => 'https://metrologi.kemendag.go.id/pelaporan_ttu/web/home', 'icon' => 'bi-folder2-open'],
                        ['name' => 'Aspirasi', 'desc' => 'Layanan Pengaduan Online', 'url' => route('login'), 'icon' => 'bi-chat-left-text'],
                        ['name' => 'BPSUML', 'desc' => 'Profil & Master SUML', 'url' => 'https://metrologi.kemendag.go.id/master_suml/', 'icon' => 'bi-info-circle'],
                    ];
                @endphp
                @foreach($portals as $i => $p)
                    <a href="{{ $p['url'] }}" class="portal-card reveal reveal-delay-{{ $i % 3 }}"
                       @if(str_starts_with($p['url'], 'http')) target="_blank" rel="noopener" @endif>
                        <div class="portal-icon"><i class="bi {{ $p['icon'] }}"></i></div>
                        <div class="portal-info">
                            <strong>{{ $p['name'] }}</strong>
                            <span>{{ $p['desc'] }}</span>
                        </div>
                        <i class="bi bi-arrow-up-right portal-arrow"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="section" id="faq">
        <div class="container faq-layout">
            <div class="faq-intro reveal">
                <span class="section-label">FAQ</span>
                <h2>Pertanyaan yang sering diajukan</h2>
                <p>Informasi singkat untuk membantu pengguna baru memahami sistem.</p>
                <a href="{{ route('panduan') }}" target="_blank" class="btn btn-primary btn-sm">
                    Buka Panduan Lengkap
                    <i class="bi bi-box-arrow-up-right"></i>
                </a>
                <a href="https://ye-shaiyoe.github.io/Docs-adminstrasi-bpsuml/" target="_blank" rel="noopener" class="btn btn-ghost btn-sm">
                    Dokumentasi Docs
                    <i class="bi bi-box-arrow-up-right"></i>
                </a>
            </div>

            <div class="faq-list reveal reveal-delay-1">
                @php
                    $faqs = [
                        ['q' => 'Siapa yang dapat menggunakan sistem ini?', 'a' => 'Sistem ditujukan untuk pegawai dan pejabat di lingkungan BPSUML yang memiliki akun terdaftar. Pendaftaran dapat dilakukan melalui halaman Daftar, lalu diverifikasi sesuai kebijakan internal.'],
                        ['q' => 'Bagaimana cara mengajukan surat?', 'a' => 'Setelah login, buka menu pengajuan surat, pilih jenis/template, lengkapi data dan unggah draf. Surat akan masuk ke alur verifikasi sesuai hierarki.'],
                        ['q' => 'Apa yang dimaksud SLA 1 Hari Kerja?', 'a' => 'Setiap surat memiliki target waktu penyelesaian. Sistem menampilkan countdown SLA agar pejabat dan pengaju dapat memantau ketepatan waktu proses.'],
                        ['q' => 'Bagaimana verifikasi keaslian dokumen?', 'a' => 'Dokumen final dilengkapi QR Code. Pindai atau buka tautan verifikasi untuk memastikan status dan keaslian surat di sistem.'],
                        ['q' => 'Apakah saya bisa melacak status surat?', 'a' => 'Ya. Setiap surat menampilkan tahap approval saat ini, riwayat aksi, dan notifikasi ketika status berubah atau memerlukan tindakan.'],
                    ];
                @endphp
                @foreach($faqs as $i => $faq)
                    <details class="faq-item" @if($i === 0) open @endif>
                        <summary>
                            <span>{{ $faq['q'] }}</span>
                            <i class="bi bi-chevron-down"></i>
                        </summary>
                        <div class="faq-body">{{ $faq['a'] }}</div>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA BAND --}}
    <section class="cta-band">
        <div class="cta-band-bg"></div>
        <div class="container cta-band-inner">
            <div class="cta-band-text">
                <span class="cta-eyebrow">Mulai sekarang</span>
                <h2>Siap menggunakan sistem arsip digital BPSUML?</h2>
                <p>Masuk dengan akun pegawai untuk mengelola, memantau, dan mengarsipkan Administrasi secara terpusat.</p>
                <div class="cta-trust-badges">
                    <span class="cta-badge-item"><i class="bi bi-shield-check"></i> Encrypted &amp; Aman</span>
                    <span class="cta-badge-item"><i class="bi bi-stopwatch"></i> SLA 1 Hari Kerja</span>
                    <span class="cta-badge-item"><i class="bi bi-qr-code"></i> Autentikasi QR</span>
                </div>
            </div>
            <div class="cta-band-right">
                <div class="cta-badge-card">
                    <div class="cbc-item">
                        <div class="cbc-icon"><i class="bi bi-shield-lock-fill"></i></div>
                        <div>
                            <strong>Standar SPBE</strong>
                            <span>Digital Governance</span>
                        </div>
                    </div>
                    <div class="cbc-divider"></div>
                    <div class="cbc-item">
                        <div class="cbc-icon gold"><i class="bi bi-lightning-charge-fill"></i></div>
                        <div>
                            <strong>Persetujuan Cepat</strong>
                            <span>Monitoring Real-time</span>
                        </div>
                    </div>
                </div>
                <div class="cta-band-actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-gold btn-lg">
                            <i class="bi bi-speedometer2"></i> Ke Dashboard
                        </a>
                        <a href="{{ route('panduan') }}" target="_blank" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-book"></i> Panduan Sistem
                        </a>
                        <a href="https://ye-shaiyoe.github.io/Docs-adminstrasi-bpsuml/" target="_blank" rel="noopener" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-file-earmark-code"></i> Dokumentasi Docs
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-gold btn-lg">
                            <i class="bi bi-box-arrow-in-right"></i> Masuk Sistem
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Daftar Akun</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="footer" id="kontak">
        <div class="container footer-grid">
            <div class="footer-brand-col">
                <div class="footer-brand-row">
                    <div>
                        <strong>BPSUML</strong>
                        <span>Balai Pengelolaan Standar Ukuran Metrologi Legal</span>
                    </div>
                </div>
                <p>
                    Unit pelaksana teknis Direktorat Metrologi, Kementerian Perdagangan
                    Republik Indonesia.
                </p>
                <address>
                    <i class="bi bi-geo-alt"></i>
                    Jl. Pasteur No. 27, Pasteur<br>
                    Kec. Sukajadi, Kota Bandung<br>
                    Jawa Barat 40161
                </address>
            </div>

            <div>
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="#beranda">Beranda</a></li>
                    <li><a href="#tentang">Tentang</a></li>
                    <li><a href="#layanan">Layanan</a></li>
                    <li><a href="#alur">Alur Kerja</a></li>
                    <li><a href="#kinerja">Kinerja</a></li>
                    <li><a href="#faq">FAQ</a></li>
                </ul>
            </div>

            <div>
                <h4>Akses Sistem</h4>
                <ul>
                    <li><a href="{{ route('login') }}">Masuk</a></li>
                    <li><a href="{{ route('register') }}">Daftar</a></li>
                    <li><a href="{{ route('panduan') }}" target="_blank">Panduan Penggunaan</a></li>
                    <li><a href="https://ye-shaiyoe.github.io/Docs-adminstrasi-bpsuml/" target="_blank" rel="noopener">Dokumentasi System</a></li>
                    @auth
                        <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h4>Kontak &amp; Layanan</h4>
                <ul>
                    <li>
                        <a href="mailto:tubpsuml@gmail.com">
                            <i class="bi bi-envelope"></i> tubpsuml@gmail.com
                        </a>
                    </li>
                    <li>
                        <a href="https://metrologi.kemendag.go.id/" target="_blank" rel="noopener">
                            <i class="bi bi-globe"></i> metrologi.kemendag.go.id
                        </a>
                    </li>
                    <li>
                        <div class="footer-hours">
                            <span><i class="bi bi-clock-history"></i> Senin–Kamis: 07.30–16.00 WIB</span>
                            <span><i class="bi bi-clock-history"></i> Jumat: 07.30–11.30 WIB</span>
                            <span><i class="bi bi-clock-history"></i> Libur: Sabtu &amp; Minggu</span>
                        </div>
                    </li>
                </ul>
                <div class="footer-socials">
                    <a href="https://www.instagram.com/direktorat_metrologi/" target="_blank" rel="noopener" aria-label="Instagram" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://x.com/DitMetrologi" target="_blank" rel="noopener" aria-label="X" title="X / Twitter">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="mailto:tubpsuml@gmail.com" aria-label="Email" title="Email">
                        <i class="bi bi-envelope-fill"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container footer-bottom-inner">
                <span>&copy; {{ date('Y') }} BPSUML Direktorat Metrologi, Kementerian Perdagangan RI</span>
                <span class="footer-tagline">Mengukur dengan Adil, Melayani dengan Tepat</span>
            </div>
        </div>
    </footer>

    <button type="button" class="back-top" id="back-top" aria-label="Kembali ke atas">
        <i class="bi bi-chevron-up"></i>
    </button>

    <script>
        (function () {
            const navbar = document.getElementById('navbar');
            const menuToggle = document.getElementById('menu-toggle');
            const navClose = document.getElementById('nav-close');
            const navLinks = document.getElementById('nav-links');
            const navOverlay = document.getElementById('nav-overlay');
            const backTop = document.getElementById('back-top');

            const setMenu = (open) => {
                navLinks?.classList.toggle('open', open);
                navOverlay?.classList.toggle('show', open);
                menuToggle?.classList.toggle('active', open);
                menuToggle?.setAttribute('aria-expanded', open ? 'true' : 'false');
                document.body.classList.toggle('menu-open', open);
            };

            menuToggle?.addEventListener('click', () => setMenu(!navLinks.classList.contains('open')));
            navClose?.addEventListener('click', () => setMenu(false));
            navOverlay?.addEventListener('click', () => setMenu(false));
            navLinks?.querySelectorAll('a').forEach(a => a.addEventListener('click', () => setMenu(false)));

            const onScroll = () => {
                navbar?.classList.toggle('scrolled', window.scrollY > 8);
                backTop?.classList.toggle('show', window.scrollY > 420);
            };
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();

            backTop?.addEventListener('click', () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });

            // Count-up
            const animateCount = (el) => {
                const target = parseFloat(el.getAttribute('data-count') || '0');
                const isDecimal = el.hasAttribute('data-decimal');
                const duration = 1500;
                const start = performance.now();
                const tick = (now) => {
                    const p = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - p, 3);
                    const val = target * eased;
                    el.textContent = isDecimal ? val.toFixed(1) : Math.round(val).toLocaleString('id-ID');
                    if (p < 1) requestAnimationFrame(tick);
                };
                requestAnimationFrame(tick);
            };

            // Reveal + counters
            const counters = document.querySelectorAll('[data-count]');
            const reveals = document.querySelectorAll('.reveal');

            if ('IntersectionObserver' in window) {
                const ioCount = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animateCount(entry.target);
                            ioCount.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.35 });
                counters.forEach(el => ioCount.observe(el));

                const ioReveal = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('in');
                            ioReveal.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
                reveals.forEach(el => ioReveal.observe(el));
            } else {
                counters.forEach(animateCount);
                reveals.forEach(el => el.classList.add('in'));
            }

            // Animate SLA/dist bars when visible
            document.querySelectorAll('.dist-bar-fill, .sla-bar-fill').forEach(bar => {
                const w = bar.style.width;
                bar.style.width = '0%';
                bar.dataset.target = w;
            });

            if ('IntersectionObserver' in window) {
                const ioBars = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.querySelectorAll('.dist-bar-fill, .sla-bar-fill').forEach(bar => {
                                bar.style.width = bar.dataset.target || '0%';
                            });
                            ioBars.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.2 });
                document.querySelectorAll('.kinerja-card').forEach(c => ioBars.observe(c));
            }

            // Parallax + tilt only on non-touch / desktop (saves battery on HP)
            const canMotion = window.matchMedia('(hover: hover) and (pointer: fine)').matches
                && !window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (canMotion) {
                let parallaxTicking = false;
                const orb1 = document.querySelector('.hero-orb-1');
                const orb2 = document.querySelector('.hero-orb-2');

                const applyParallax = () => {
                    const scrolled = window.scrollY;
                    if (scrolled < 1400) {
                        if (orb1) orb1.style.transform = `translate3d(0, ${scrolled * 0.18}px, 0)`;
                        if (orb2) orb2.style.transform = `translate3d(0, ${scrolled * -0.12}px, 0)`;
                    }
                    parallaxTicking = false;
                };

                window.addEventListener('scroll', () => {
                    if (!parallaxTicking) {
                        requestAnimationFrame(applyParallax);
                        parallaxTicking = true;
                    }
                }, { passive: true });

                const heroPanel = document.querySelector('.hero-panel');
                const heroCard = document.querySelector('.hero-panel-card');
                if (heroPanel && heroCard) {
                    heroPanel.addEventListener('mousemove', (e) => {
                        const rect = heroPanel.getBoundingClientRect();
                        const x = e.clientX - rect.left - rect.width / 2;
                        const y = e.clientY - rect.top - rect.height / 2;
                        const tiltX = (y / (rect.height / 2)) * -5;
                        const tiltY = (x / (rect.width / 2)) * 5;
                        heroCard.style.transform = `perspective(1000px) rotateX(${tiltX}deg) rotateY(${tiltY}deg) translateZ(6px)`;
                    });
                    heroPanel.addEventListener('mouseleave', () => {
                        heroCard.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) translateZ(0px)';
                    });
                }
            }

            // Active nav highlight
            const sections = document.querySelectorAll('section[id]');
            const navItems = document.querySelectorAll('.nav-item');
            const highlightNav = () => {
                let current = '';
                sections.forEach(sec => {
                    if (window.scrollY >= sec.offsetTop - 120) current = sec.id;
                });
                navItems.forEach(a => {
                    a.classList.toggle('active', a.getAttribute('href') === '#' + current);
                });
            };
            window.addEventListener('scroll', highlightNav, { passive: true });
        })();
    </script>
</body>
</html>
