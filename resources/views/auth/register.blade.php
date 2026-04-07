<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Sistem Surat Metrologi</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:        #0a2540;
            --navy-mid:    #103262;
            --blue:        #1a56db;
            --blue-light:  #4f86f7;
            --accent:      #38bdf8;
            --surface:     rgba(255,255,255,0.06);
            --surface-hov: rgba(255,255,255,0.11);
            --border:      rgba(255,255,255,0.13);
            --border-str:  rgba(255,255,255,0.28);
            --text:        #ffffff;
            --muted:       rgba(255,255,255,0.58);
            --faint:       rgba(255,255,255,0.30);
            --input-bg:    rgba(255,255,255,0.07);
            --input-focus: rgba(255,255,255,0.13);
            --error:       #fda4af;
            --shadow-card: 0 24px 64px rgba(0,0,0,0.45), 0 4px 16px rgba(0,0,0,0.28);
        }

        body {
            min-height: 100vh;
            font-family: 'DM Sans', sans-serif;
            background: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* ── Background layers ── */
        .bg-layer { position: fixed; inset: 0; z-index: 0; overflow: hidden; }

        .bg-gradient {
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 120% 80% at 20% 0%, #183e8f 0%, transparent 60%),
                radial-gradient(ellipse 80% 60% at 80% 100%, #0c4a6e 0%, transparent 55%),
                linear-gradient(160deg, #0a2540 0%, #071a30 60%, #030e1c 100%);
        }
        .bg-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px); opacity: 0.22;
            animation: drift 14s ease-in-out infinite alternate;
        }
        .orb-1 { width: 600px; height: 600px; background: #1d4ed8; top: -200px; left: -150px; animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; background: #0ea5e9; bottom: -100px; right: -100px; animation-delay: -6s; }
        .orb-3 { width: 280px; height: 280px; background: #7c3aed; top: 30%; right: 20%; animation-delay: -9s; opacity: 0.14; }
        @keyframes drift {
            from { transform: translate(0,0) scale(1); }
            to   { transform: translate(40px,25px) scale(1.08); }
        }
        .bg-streak {
            position: absolute; width: 2px; height: 60%;
            background: linear-gradient(to bottom, transparent, rgba(99,179,237,0.18), transparent);
            top: 10%; left: 38%; transform: rotate(15deg); pointer-events: none;
        }

        /* ── Main scene ── */
        .scene {
            position: relative; z-index: 10;
            width: min(96vw, 1020px);
            display: flex;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: var(--shadow-card), 0 0 0 1px var(--border);
            backdrop-filter: blur(2px);
        }

        /* ── Left Panel ── */
        .panel-left {
            flex: 0 0 34%;
            background: linear-gradient(160deg, rgba(26,86,219,0.35) 0%, rgba(10,37,64,0.6) 100%);
            border-right: 1px solid var(--border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 28px;
            gap: 22px;
            position: relative;
            overflow: hidden;
        }
        .panel-left::before {
            content: ''; position: absolute;
            width: 320px; height: 320px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.06);
            bottom: -100px; right: -100px; pointer-events: none;
        }
        .panel-left::after {
            content: ''; position: absolute;
            width: 200px; height: 200px; border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.08);
            top: -60px; left: -60px; pointer-events: none;
        }

        .logo-outer {
            width: 92px; height: 92px; border-radius: 50%;
            border: 1px solid var(--border-str);
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(12px);
            display: flex; align-items: center; justify-content: center;
            position: relative;
            box-shadow: 0 0 0 8px rgba(255,255,255,0.04), inset 0 1px 0 rgba(255,255,255,0.2);
        }
        .logo-outer::before {
            content: ''; position: absolute; inset: -8px; border-radius: 50%;
            border: 1px solid rgba(99,179,237,0.25);
            animation: pulse-ring 3s ease-out infinite;
        }
        @keyframes pulse-ring {
            0%   { transform: scale(1); opacity: 0.5; }
            70%  { transform: scale(1.12); opacity: 0; }
            100% { opacity: 0; }
        }
        .logo-inner {
            width: 58px; height: 58px; border-radius: 14px;
            background: rgba(255,255,255,0.08);
            display: flex; align-items: center; justify-content: center; overflow: hidden;
        }
        .logo-inner img { width: 100%; height: 100%; object-fit: contain; }

        .brand-title {
            font-family: 'Sora', sans-serif;
            color: white; font-size: 14px; font-weight: 700;
            text-align: center; line-height: 1.5; letter-spacing: -0.01em;
        }
        .divider-line {
            width: 40px; height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-str), transparent);
        }
        .brand-sub {
            color: var(--muted); font-size: 12px;
            text-align: center; line-height: 1.7;
        }
        .info-badge {
            background: rgba(255,255,255,0.07);
            border: 1px solid var(--border); border-radius: 12px;
            padding: 10px 18px; text-align: center;
            transition: background .2s;
        }
        .info-badge:hover { background: var(--surface-hov); }
        .badge-label {
            color: var(--faint); font-size: 10px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.1em;
        }
        .badge-val {
            color: white; font-size: 14px; font-weight: 700;
            font-family: 'Sora', sans-serif; margin-top: 2px;
        }

        /* Steps indicator */
        .steps-box {
            background: rgba(255,255,255,0.06);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 16px;
            width: 100%;
        }
        .steps-label {
            color: var(--faint); font-size: 10px;
            font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.1em; margin-bottom: 10px;
        }
        .step-item {
            display: flex; align-items: center; gap: 10px;
            padding: 4px 0;
        }
        .step-dot {
            width: 20px; height: 20px; border-radius: 50%;
            background: rgba(255,255,255,0.08);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 700; color: var(--faint);
            flex-shrink: 0;
        }
        .step-dot.active {
            background: rgba(26,86,219,0.4);
            border-color: #4f86f7;
            color: #7dd3fc;
        }
        .step-text { color: var(--muted); font-size: 11.5px; }
        .step-text.active { color: #bae6fd; font-weight: 500; }

        /* ── Right Panel ── */
        .panel-right {
            flex: 1;
            background: rgba(255,255,255,0.055);
            backdrop-filter: blur(36px);
            -webkit-backdrop-filter: blur(36px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 44px;
            position: relative;
            overflow: hidden;
        }
        .panel-right::before {
            content: ''; position: absolute;
            top: 0; left: 44px; right: 44px; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        }

        .greeting-chip {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(56,189,248,0.1);
            border: 1px solid rgba(56,189,248,0.2);
            border-radius: 999px; padding: 4px 12px;
            font-size: 11px; color: #7dd3fc;
            margin-bottom: 12px; width: fit-content;
        }
        .form-heading {
            font-family: 'Sora', sans-serif;
            color: white; font-size: 24px; font-weight: 800;
            letter-spacing: -0.03em; line-height: 1.2; margin-bottom: 3px;
        }
        .form-heading span {
            background: linear-gradient(90deg, #7dd3fc, #818cf8);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .form-sub {
            color: var(--muted); font-size: 12.5px; margin-bottom: 22px;
        }

        /* ── Field grid ── */
        .fields-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px 16px;
        }
        .field-full { grid-column: 1 / -1; }

        .field-group { display: flex; flex-direction: column; }
        .field-label {
            color: rgba(255,255,255,0.7); font-size: 10.5px;
            font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.09em; margin-bottom: 5px; display: block;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            color: var(--faint); font-size: 14px;
            pointer-events: none; transition: color .2s;
        }
        .input-wrap:focus-within .input-icon { color: #7dd3fc; }

        .field-input {
            width: 100%;
            padding: 10px 14px 10px 36px;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: white; font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color .2s, background .2s, box-shadow .2s;
        }
        .field-input::placeholder { color: var(--faint); }
        .field-input:focus {
            border-color: rgba(99,179,237,0.55);
            background: var(--input-focus);
            box-shadow: 0 0 0 3px rgba(56,189,248,0.1);
        }

        /* Admin field amber tint */
        .field-input.admin-input {
            border-color: rgba(251,191,36,0.28);
            background: rgba(251,191,36,0.06);
        }
        .field-input.admin-input:focus {
            border-color: rgba(251,191,36,0.65);
            background: rgba(251,191,36,0.1);
            box-shadow: 0 0 0 3px rgba(251,191,36,0.08);
        }
        .field-input.admin-input::placeholder { color: rgba(251,191,36,0.38); }

        .input-wrap:focus-within .admin-icon { color: #fbbf24; }

        .field-hint {
            color: rgba(251,191,36,0.65); font-size: 10.5px;
            margin-top: 4px; display: flex; align-items: center; gap: 4px;
        }
        .error-text {
            color: var(--error); font-size: 10.5px; margin-top: 4px;
            display: flex; align-items: center; gap: 4px;
        }

        /* Password toggle */
        .pw-toggle {
            position: absolute; right: 10px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: var(--faint); cursor: pointer;
            padding: 2px; font-size: 14px; transition: color .2s;
        }
        .pw-toggle:hover { color: var(--muted); }

        /* Strength bar */
        .strength-bar {
            display: flex; gap: 4px; margin-top: 6px;
        }
        .strength-seg {
            height: 3px; flex: 1; border-radius: 999px;
            background: var(--border); transition: background .3s;
        }
        .strength-seg.weak   { background: #f87171; }
        .strength-seg.medium { background: #fbbf24; }
        .strength-seg.strong { background: #34d399; }

        /* ── Submit + link ── */
        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #1a56db 0%, #3b82f6 100%);
            color: white; border: none; border-radius: 12px;
            font-size: 14px; font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer; position: relative; overflow: hidden;
            transition: transform .12s, box-shadow .18s;
            box-shadow: 0 4px 20px rgba(26,86,219,0.4), 0 1px 4px rgba(0,0,0,0.3);
            margin-top: 20px;
        }
        .btn-submit::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, transparent 50%);
            pointer-events: none;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(26,86,219,0.5), 0 2px 8px rgba(0,0,0,0.3); }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit .arrow { display: inline-block; transition: transform .2s; margin-left: 6px; }
        .btn-submit:hover .arrow { transform: translateX(4px); }

        .login-row {
            display: flex; align-items: center; justify-content: center;
            gap: 6px; margin-top: 16px;
        }
        .login-row span { color: var(--muted); font-size: 12.5px; }
        .login-link {
            color: #7dd3fc; font-size: 12.5px; font-weight: 600;
            text-decoration: none; transition: color .15s;
        }
        .login-link:hover { color: #bae6fd; }

        /* ── Footer ── */
        .footer-bar {
            position: absolute; bottom: 0; left: 0; right: 0;
            height: 34px; background: rgba(0,0,0,0.15);
            border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            pointer-events: none; z-index: 2;
        }
        .footer-bar span { color: rgba(255,255,255,0.3); font-size: 10.5px; }

        /* ── Mobile ── */
        @media (max-width: 640px) {
            body { overflow-y: auto; align-items: flex-start; padding: 24px 0; }
            .scene { flex-direction: column; border-radius: 20px; width: 92vw; }
            .panel-left {
                flex: 0 0 auto; border-right: none;
                border-bottom: 1px solid var(--border);
                flex-direction: row; padding: 20px 24px;
                gap: 14px; justify-content: flex-start;
            }
            .brand-title { font-size: 12px; text-align: left; }
            .brand-sub, .divider-line, .info-badge, .steps-box { display: none; }
            .panel-right { padding: 28px 24px 56px; }
            .form-heading { font-size: 20px; }
            .fields-grid { grid-template-columns: 1fr; }
            .field-full { grid-column: 1; }
        }
    </style>
</head>
<body>

    <div class="bg-layer">
        <div class="bg-gradient"></div>
        <div class="bg-grid"></div>
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="bg-streak"></div>
    </div>

    <div class="scene">

        <!-- ══ Left: Branding ══ -->
        <div class="panel-left">

            <div class="logo-outer">
                <div class="logo-inner">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Dinas">
                </div>
            </div>

            <div class="brand-title">Dinas Perdagangan<br>dan Perindustrian</div>
            <div class="divider-line"></div>
            <div class="brand-sub">Sistem Informasi<br>Manajemen Surat<br>Metrologi Legal</div>
            <div class="divider-line"></div>

            <div class="info-badge">
                <div class="badge-label">Tahun Anggaran</div>
                <div class="badge-val">{{ date('Y') }}</div>
            </div>

            <!-- Steps guide -->
            <div class="steps-box">
                <div class="steps-label">Langkah Pendaftaran</div>
                <div class="step-item">
                    <div class="step-dot active">1</div>
                    <div class="step-text active">Isi data akun</div>
                </div>
                <div class="step-item">
                    <div class="step-dot">2</div>
                    <div class="step-text">Verifikasi email</div>
                </div>
                <div class="step-item">
                    <div class="step-dot">3</div>
                    <div class="step-text">Akses sistem</div>
                </div>
            </div>
        </div>

        <!-- ══ Right: Register Form ══ -->
        <div class="panel-right">

            <div class="greeting-chip">
                <i class="bi bi-person-plus" style="font-size:11px;"></i>
                Pendaftaran akun baru
            </div>

            <div class="form-heading">
                Buat <span>Akun</span>
            </div>
            <div class="form-sub">Lengkapi data berikut untuk mendaftar sebagai pengguna sistem</div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="fields-grid">

                    <!-- Nama Lengkap -->
                    <div class="field-group field-full">
                        <label class="field-label" for="name">Nama Lengkap</label>
                        <div class="input-wrap">
                            <input class="field-input" id="name" type="text" name="name"
                                value="{{ old('name') }}" placeholder="Nama lengkap Anda"
                                required autofocus autocomplete="name">
                            <i class="bi bi-person input-icon"></i>
                        </div>
                        @error('name')
                            <p class="error-text"><i class="bi bi-x-circle-fill" style="font-size:10px;"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="field-group field-full">
                        <label class="field-label" for="email">Alamat Email</label>
                        <div class="input-wrap">
                            <input class="field-input" id="email" type="email" name="email"
                                value="{{ old('email') }}" placeholder="example@gmail.com"
                                required autocomplete="username">
                            <i class="bi bi-envelope input-icon"></i>
                        </div>
                        @error('email')
                            <p class="error-text"><i class="bi bi-x-circle-fill" style="font-size:10px;"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="field-group">
                        <label class="field-label" for="password">Kata Sandi</label>
                        <div class="input-wrap">
                            <input class="field-input" id="password" type="password" name="password"
                                placeholder="Min. 8 karakter"
                                required autocomplete="new-password"
                                style="padding-right: 36px;"
                                oninput="checkStrength(this.value)">
                            <i class="bi bi-lock input-icon"></i>
                            <button type="button" class="pw-toggle" onclick="togglePw('password','pwIcon1')" tabindex="-1">
                                <i class="bi bi-eye" id="pwIcon1"></i>
                            </button>
                        </div>
                        <div class="strength-bar">
                            <div class="strength-seg" id="s1"></div>
                            <div class="strength-seg" id="s2"></div>
                            <div class="strength-seg" id="s3"></div>
                            <div class="strength-seg" id="s4"></div>
                        </div>
                        @error('password')
                            <p class="error-text"><i class="bi bi-x-circle-fill" style="font-size:10px;"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="field-group">
                        <label class="field-label" for="password_confirmation">Konfirmasi Sandi</label>
                        <div class="input-wrap">
                            <input class="field-input" id="password_confirmation" type="password"
                                name="password_confirmation" placeholder="Ulangi kata sandi"
                                required autocomplete="new-password"
                                style="padding-right: 36px;">
                            <i class="bi bi-lock-fill input-icon"></i>
                            <button type="button" class="pw-toggle" onclick="togglePw('password_confirmation','pwIcon2')" tabindex="-1">
                                <i class="bi bi-eye" id="pwIcon2"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="error-text"><i class="bi bi-x-circle-fill" style="font-size:10px;"></i> {{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Admin -->
                    <div class="field-group field-full">
                        <label class="field-label" for="admin_code">
                            Kode Admin
                            <span style="opacity:.5; font-weight:400; text-transform:none; letter-spacing:0; font-size:10px;">&nbsp;(opsional)</span>
                        </label>
                        <div class="input-wrap">
                            <input class="field-input admin-input" id="admin_code" type="password"
                                name="admin_code" placeholder="Kosongkan jika bukan admin"
                                autocomplete="off">
                            <i class="bi bi-shield-lock input-icon admin-icon" style="color: rgba(251,191,36,0.4);"></i>
                        </div>
                        <p class="field-hint">
                            <i class="bi bi-info-circle" style="font-size:10px;"></i>
                            Isi hanya jika mendaftar sebagai administrator sistem
                        </p>
                        @error('admin_code')
                            <p class="error-text"><i class="bi bi-x-circle-fill" style="font-size:10px;"></i> {{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <button type="submit" class="btn-submit">
                    Daftar Sekarang
                    <span class="arrow">→</span>
                </button>

                <div class="login-row">
                    <span>Sudah memiliki akun?</span>
                    <a href="{{ route('login') }}" class="login-link">Masuk di sini</a>
                </div>

            </form>
        </div>

        <div class="footer-bar">
            <span>&copy; {{ date('Y') }} Dinas Perdagangan dan Perindustrian &mdash; Hak cipta dilindungi undang-undang</span>
        </div>
    </div>

    <script>
        function togglePw(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon  = document.getElementById(iconId);
            const shown = input.type === 'text';
            input.type  = shown ? 'password' : 'text';
            icon.className = shown ? 'bi bi-eye' : 'bi bi-eye-slash';
        }

        function checkStrength(val) {
            const segs = [document.getElementById('s1'), document.getElementById('s2'),
                          document.getElementById('s3'), document.getElementById('s4')];
            segs.forEach(s => s.className = 'strength-seg');

            if (!val) return;

            let score = 0;
            if (val.length >= 8)  score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const cls = score <= 1 ? 'weak' : score <= 2 ? 'weak' : score === 3 ? 'medium' : 'strong';
            const colors = score <= 1 ? ['weak'] : score === 2 ? ['weak','weak'] : score === 3 ? ['medium','medium','medium'] : ['strong','strong','strong','strong'];
            colors.forEach((c, i) => segs[i].classList.add(c));
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>