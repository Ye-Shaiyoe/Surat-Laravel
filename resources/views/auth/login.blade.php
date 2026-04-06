<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem Surat Metrologi</title>
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
            --gold:        #f5c842;
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
            --btn-text:    #0a2540;
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
        .bg-layer {
            position: fixed;
            inset: 0;
            z-index: 0;
            overflow: hidden;
        }

        /* Deep gradient base */
        .bg-gradient {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 120% 80% at 20% 0%, #183e8f 0%, transparent 60%),
                radial-gradient(ellipse 80% 60% at 80% 100%, #0c4a6e 0%, transparent 55%),
                linear-gradient(160deg, #0a2540 0%, #071a30 60%, #030e1c 100%);
        }

        /* Subtle grid */
        .bg-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* Floating orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.22;
            animation: drift 14s ease-in-out infinite alternate;
        }
        .orb-1 { width: 600px; height: 600px; background: #1d4ed8; top: -200px; left: -150px; animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; background: #0ea5e9; bottom: -100px; right: -100px; animation-delay: -6s; }
        .orb-3 { width: 280px; height: 280px; background: #7c3aed; top: 30%; right: 20%; animation-delay: -9s; opacity: 0.14; }

        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(40px, 25px) scale(1.08); }
        }

        /* Diagonal light streak */
        .bg-streak {
            position: absolute;
            width: 2px;
            height: 60%;
            background: linear-gradient(to bottom, transparent, rgba(99,179,237,0.18), transparent);
            top: 10%;
            left: 38%;
            transform: rotate(15deg);
            pointer-events: none;
        }

        /* ── Main scene ── */
        .scene {
            position: relative;
            z-index: 10;
            width: min(96vw, 980px);
            display: flex;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: var(--shadow-card), 0 0 0 1px var(--border);
            backdrop-filter: blur(2px);
        }

        /* ── Left Panel ── */
        .panel-left {
            flex: 0 0 38%;
            background: linear-gradient(160deg, rgba(26,86,219,0.35) 0%, rgba(10,37,64,0.6) 100%);
            border-right: 1px solid var(--border);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 32px;
            gap: 24px;
            position: relative;
            overflow: hidden;
        }

        /* Corner decorative ring */
        .panel-left::before {
            content: '';
            position: absolute;
            width: 320px; height: 320px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.06);
            bottom: -100px; right: -100px;
            pointer-events: none;
        }
        .panel-left::after {
            content: '';
            position: absolute;
            width: 200px; height: 200px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.08);
            top: -60px; left: -60px;
            pointer-events: none;
        }

        /* Logo */
        .logo-outer {
            width: 92px; height: 92px;
            border-radius: 50%;
            border: 1px solid var(--border-str);
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(12px);
            display: flex; align-items: center; justify-content: center;
            position: relative;
            box-shadow: 0 0 0 8px rgba(255,255,255,0.04), inset 0 1px 0 rgba(255,255,255,0.2);
        }
        .logo-inner {
            width: 58px; height: 58px;
            border-radius: 14px;
            background: rgba(255,255,255,0.08);
            display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .logo-inner img { width: 100%; height: 100%; object-fit: contain; }

        /* Pulse ring animation on logo */
        .logo-outer::before {
            content: '';
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 1px solid rgba(99,179,237,0.25);
            animation: pulse-ring 3s ease-out infinite;
        }
        @keyframes pulse-ring {
            0%   { transform: scale(1); opacity: 0.5; }
            70%  { transform: scale(1.12); opacity: 0; }
            100% { opacity: 0; }
        }

        .brand-title {
            font-family: 'Sora', sans-serif;
            color: white;
            font-size: 15px;
            font-weight: 700;
            text-align: center;
            line-height: 1.5;
            letter-spacing: -0.01em;
        }

        .divider-line {
            width: 40px; height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-str), transparent);
        }

        .brand-sub {
            color: var(--muted);
            font-size: 12px;
            text-align: center;
            line-height: 1.7;
        }

        /* Info badges */
        .info-badge {
            background: rgba(255,255,255,0.07);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px 18px;
            text-align: center;
            transition: background .2s;
        }
        .info-badge:hover { background: var(--surface-hov); }
        .badge-label {
            color: var(--faint);
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }
        .badge-val {
            color: white;
            font-size: 14px;
            font-weight: 700;
            font-family: 'Sora', sans-serif;
            margin-top: 2px;
        }

        /* Status pill */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(56,189,248,0.12);
            border: 1px solid rgba(56,189,248,0.25);
            border-radius: 999px;
            padding: 5px 12px;
            font-size: 11px;
            color: #7dd3fc;
        }
        .status-dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            background: #38bdf8;
            box-shadow: 0 0 6px #38bdf8;
            animation: blink 2s ease-in-out infinite;
        }
        @keyframes blink {
            0%,100% { opacity: 1; }
            50%      { opacity: 0.4; }
        }

        /* ── Right Panel ── */
        .panel-right {
            flex: 1;
            background: rgba(255,255,255,0.055);
            backdrop-filter: blur(36px);
            -webkit-backdrop-filter: blur(36px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 44px;
            position: relative;
            overflow: hidden;
        }

        /* Subtle top accent line */
        .panel-right::before {
            content: '';
            position: absolute;
            top: 0; left: 44px; right: 44px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        }

        .greeting-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(245,200,66,0.12);
            border: 1px solid rgba(245,200,66,0.22);
            border-radius: 999px;
            padding: 4px 12px;
            font-size: 11px;
            color: #fde68a;
            margin-bottom: 14px;
            width: fit-content;
        }

        .form-heading {
            font-family: 'Sora', sans-serif;
            color: white;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.03em;
            line-height: 1.2;
            margin-bottom: 4px;
        }
        .form-heading span {
            background: linear-gradient(90deg, #7dd3fc, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .form-sub {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 28px;
        }

        /* ── Alert ── */
        .alert-glass {
            background: rgba(253,100,116,0.12);
            border: 1px solid rgba(253,100,116,0.28);
            border-radius: 10px;
            color: #fca5a5;
            font-size: 12.5px;
            padding: 10px 14px;
            margin-bottom: 20px;
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }

        /* ── Fields ── */
        .field-group {
            margin-bottom: 18px;
        }
        .field-label {
            color: rgba(255,255,255,0.7);
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.09em;
            margin-bottom: 6px;
            display: block;
        }

        .input-wrap {
            position: relative;
        }
        .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--faint);
            font-size: 15px;
            pointer-events: none;
            transition: color .2s;
        }
        .field-input {
            width: 100%;
            padding: 11px 14px 11px 38px;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 11px;
            color: white;
            font-size: 13.5px;
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
        .field-input:focus + .input-icon,
        .input-wrap:focus-within .input-icon {
            color: #7dd3fc;
        }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--faint);
            cursor: pointer;
            padding: 2px;
            font-size: 15px;
            transition: color .2s;
        }
        .pw-toggle:hover { color: var(--muted); }

        .error-text {
            color: var(--error);
            font-size: 11px;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ── Remember & Forgot row ── */
        .extras-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        /* Custom checkbox */
        .custom-check {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }
        .custom-check input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 16px; height: 16px;
            border-radius: 5px;
            border: 1px solid var(--border-str);
            background: var(--input-bg);
            cursor: pointer;
            position: relative;
            transition: background .15s, border-color .15s;
            flex-shrink: 0;
        }
        .custom-check input[type="checkbox"]:checked {
            background: #1a56db;
            border-color: #4f86f7;
        }
        .custom-check input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            left: 4px; top: 2px;
            width: 5px; height: 8px;
            border: 2px solid white;
            border-top: none; border-left: none;
            transform: rotate(42deg);
        }
        .custom-check label {
            color: rgba(255,255,255,0.72);
            font-size: 12px;
            cursor: pointer;
        }

        .forgot-link {
            font-size: 12px;
            color: #7dd3fc;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 4px;
            opacity: 0.85;
            transition: opacity .15s;
        }
        .forgot-link:hover { opacity: 1; color: #bae6fd; }

        /* ── Submit button ── */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #1a56db 0%, #3b82f6 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            letter-spacing: 0.01em;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: transform .12s, box-shadow .18s;
            box-shadow: 0 4px 20px rgba(26,86,219,0.4), 0 1px 4px rgba(0,0,0,0.3);
        }
        .btn-submit::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.18) 0%, transparent 50%);
            pointer-events: none;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(26,86,219,0.5), 0 2px 8px rgba(0,0,0,0.3);
        }
        .btn-submit:active { transform: translateY(0); }

        /* Button icon arrow */
        .btn-submit .arrow {
            display: inline-block;
            transition: transform .2s;
            margin-left: 6px;
        }
        .btn-submit:hover .arrow { transform: translateX(4px); }

        /* ── Register link row ── */
        .register-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
        }
        .register-row span {
            color: var(--muted);
            font-size: 12.5px;
        }
        .register-link {
            color: #7dd3fc;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            transition: color .15s;
        }
        .register-link:hover { color: #bae6fd; }

        /* ── Divider ── */
        .form-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
        }
        .form-divider hr {
            flex: 1;
            border: none;
            border-top: 1px solid var(--border);
        }
        .form-divider span {
            color: var(--faint);
            font-size: 11px;
        }

        /* ── Footer ── */
        .footer-bar {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 34px;
            background: rgba(0,0,0,0.15);
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 2;
        }
        .footer-bar span {
            color: rgba(255,255,255,0.3);
            font-size: 10.5px;
        }

        /* ── Mobile ── */
        @media (max-width: 640px) {
            body { overflow-y: auto; align-items: flex-start; padding: 24px 0; }
            .scene {
                flex-direction: column;
                border-radius: 20px;
                width: 92vw;
            }
            .panel-left {
                flex: 0 0 auto;
                border-right: none;
                border-bottom: 1px solid var(--border);
                flex-direction: row;
                padding: 20px 24px;
                gap: 14px;
                justify-content: flex-start;
            }
            .brand-title { font-size: 12px; text-align: left; }
            .brand-sub, .divider-line, .info-badge, .status-pill { display: none; }
            .panel-right { padding: 28px 24px 50px; }
            .form-heading { font-size: 22px; }
        }
    </style>
</head>
<body>

    <!-- Background layers -->
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

            <div class="status-pill">
                <div class="status-dot"></div>
                Sistem aktif
            </div>
        </div>

        <!-- ══ Right: Login Form ══ -->
        <div class="panel-right">

            <div class="greeting-chip">
                <i class="bi bi-shield-check" style="font-size:11px;"></i>
                Portal resmi pemerintah
            </div>

            <div class="form-heading">
                Selamat <span>Datang</span>
            </div>
            <div class="form-sub">Masukkan kredensial Anda untuk mengakses sistem</div>

            @if ($errors->any())
            <div class="alert-glass">
                <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0; margin-top:1px;"></i>
                <div>
                    @foreach ($errors->all() as $err)
                        <div>{{ $err }}</div>
                    @endforeach
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

               <!-- Bagian Email cuy -->
                <div class="field-group">
                    <label class="field-label" for="email">Alamat Email</label>
                    <div class="input-wrap">
                        <input
                            class="field-input"
                            id="email" type="email" name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@instansi.go.id"
                            required autofocus autocomplete="username">
                        <i class="bi bi-envelope input-icon"></i>
                    </div>
                    @error('email')
                        <p class="error-text"><i class="bi bi-x-circle-fill" style="font-size:10px;"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Bagian Password -->
                <div class="field-group">
                    <label class="field-label" for="password">Kata Sandi</label>
                    <div class="input-wrap">
                        <input
                            class="field-input"
                            id="password" type="password" name="password"
                            placeholder="••••••••"
                            required autocomplete="current-password"
                            style="padding-right: 40px;">
                        <i class="bi bi-lock input-icon"></i>
                        <button type="button" class="pw-toggle" id="pwToggle" tabindex="-1" aria-label="Tampilkan kata sandi">
                            <i class="bi bi-eye" id="pwIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="error-text"><i class="bi bi-x-circle-fill" style="font-size:10px;"></i> {{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="extras-row">
                    <label class="custom-check">
                        <input type="checkbox" id="remember" name="remember">
                        <span>Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            <i class="bi bi-question-circle"></i>
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-submit">
                    Masuk ke Sistem
                    <span class="arrow">→</span>
                </button>

                <!-- Register -->
                <div class="register-row">
                    <span>Belum memiliki akun?</span>
                    <a href="{{ route('register') }}" class="register-link">Daftar sekarang</a>
                </div>

            </form>
        </div>

        <!-- Footer bar -->
        <div class="footer-bar">
            <span>&copy; {{ date('Y') }} Dinas Perdagangan dan Perindustrian &mdash; Hak cipta dilindungi undang-undang</span>
        </div>
    </div>

    <!-- Password toggle script -->
    <script>
        const toggle = document.getElementById('pwToggle');
        const pwInput = document.getElementById('password');
        const pwIcon = document.getElementById('pwIcon');
        toggle.addEventListener('click', () => {
            const shown = pwInput.type === 'text';
            pwInput.type = shown ? 'password' : 'text';
            pwIcon.className = shown ? 'bi bi-eye' : 'bi bi-eye-slash';
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
