<<<<<<< HEAD
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Sistem Surat Metrologi</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --blue-deep:   #0b3d7a;
            --blue-mid:    #1560bd;
            --blue-light:  #3b9ded;
            --white-glass: rgba(255,255,255,0.18);
            --white-border: rgba(255,255,255,0.32);
            --text-white:  #ffffff;
            --text-muted:  rgba(255,255,255,0.72);
            --input-bg:    rgba(255,255,255,0.12);
            --input-focus: rgba(255,255,255,0.24);
            --error:       #fca5a5;
            --btn-text:    #0b3d7a;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--blue-deep) 0%, var(--blue-mid) 45%, var(--blue-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow: hidden;
            position: relative;
        }

        /* ── Animated background blobs ── */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            opacity: 0.35;
            animation: float 8s ease-in-out infinite alternate;
        }
        .blob-1 { width: 520px; height: 520px; background: #1a7fff; top: -160px; left: -160px; animation-delay: 0s; }
        .blob-2 { width: 360px; height: 360px; background: #0b3d7a; bottom: -100px; right: -80px; animation-delay: -3s; }
        .blob-3 { width: 220px; height: 220px; background: #5ec4ff; bottom: 80px; left: 120px; animation-delay: -5s; }

        @keyframes float {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, 20px) scale(1.06); }
        }

        /* ── Grid noise overlay ── */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── 16:9 Outer wrapper ── */
        .scene {
            position: relative;
            z-index: 1;
            width: min(96vw, 960px);
            aspect-ratio: 16 / 9;
            display: flex;
            align-items: stretch;
            border-radius: 24px;
            overflow: hidden;
            box-shadow:
                0 0 0 1px rgba(255,255,255,0.15),
                0 40px 80px rgba(0,0,0,0.35),
                0 8px 24px rgba(0,0,0,0.25);
        }

        /* ── Left panel — branding ── */
        .panel-left {
            flex: 0 0 42%;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-right: 1px solid var(--white-border);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 32px;
            gap: 20px;
            position: relative;
            overflow: hidden;
        }

        .panel-left::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            bottom: -80px; right: -80px;
            pointer-events: none;
        }

        .logo-ring {
            width: 84px; height: 84px;
            border-radius: 50%;
            border: 1.5px solid rgba(255,255,255,0.4);
            background: rgba(255,255,255,0.14);
            backdrop-filter: blur(8px);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 24px rgba(0,0,0,0.2);
        }
        .logo-box {
            width: 52px; height: 52px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            display: flex; align-items: center; justify-content: center;
        }
        .logo-box img { width: 100%; height: 100%; object-fit: contain; }

        .brand-name {
            color: var(--text-white);
            font-size: 15px;
            font-weight: 700;
            text-align: center;
            line-height: 1.4;
            letter-spacing: -0.01em;
        }
        .brand-sub {
            color: var(--text-muted);
            font-size: 12px;
            text-align: center;
            line-height: 1.5;
        }

        .divider-h {
            width: 48px; height: 1px;
            background: rgba(255,255,255,0.25);
        }

        .badge {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.22);
            border-radius: 8px;
            padding: 10px 16px;
            text-align: center;
        }
        .badge-label {
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }
        .badge-value {
            color: white;
            font-size: 13px;
            font-weight: 700;
            margin-top: 2px;
        }

        /* ── Right panel — form ── */
        .panel-right {
            flex: 1;
            background: rgba(255,255,255,0.13);
            backdrop-filter: blur(32px);
            -webkit-backdrop-filter: blur(32px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 36px 40px;
            overflow-y: auto;
        }

        .form-title {
            color: white;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 3px;
        }
        .form-sub {
            color: var(--text-muted);
            font-size: 12.5px;
            margin-bottom: 22px;
        }

        /* ── Two-column grid for fields ── */
        .fields-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 16px;
        }
        .field-full { grid-column: 1 / -1; }

        .field-wrap { display: flex; flex-direction: column; gap: 5px; }

        .field-label {
            color: rgba(255,255,255,0.85);
            font-size: 11.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
        }

        .field-input {
            width: 100%;
            padding: 10px 14px;
            background: var(--input-bg);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 9px;
            color: white;
            font-size: 13.5px;
            font-family: inherit;
            outline: none;
            transition: border-color .2s, background .2s;
        }
        .field-input::placeholder { color: rgba(255,255,255,0.38); }
        .field-input:focus {
            border-color: rgba(255,255,255,0.65);
            background: var(--input-focus);
        }

        /* Admin code field — amber tint */
        .field-input.admin-input {
            border-color: rgba(251,191,36,0.35);
            background: rgba(251,191,36,0.08);
        }
        .field-input.admin-input:focus {
            border-color: rgba(251,191,36,0.7);
            background: rgba(251,191,36,0.14);
        }
        .field-input.admin-input::placeholder { color: rgba(251,191,36,0.45); }

        .field-hint {
            color: rgba(251,191,36,0.75);
            font-size: 11px;
            margin-top: 2px;
        }

        .error-msg {
            color: var(--error);
            font-size: 11px;
            margin-top: 2px;
        }

        /* ── Actions ── */
        .actions {
            margin-top: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .btn-register {
            flex: 1;
            padding: 12px;
            background: white;
            color: var(--btn-text);
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            font-family: inherit;
            letter-spacing: -0.01em;
            transition: background .18s, transform .12s;
        }
        .btn-register:hover { background: #dbeeff; transform: translateY(-1px); }
        .btn-register:active { transform: translateY(0); }

        .login-link {
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            white-space: nowrap;
            transition: color .15s;
        }
        .login-link:hover { color: white; }

        /* ── Footer strip ── */
        .footer-strip {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 32px;
            background: rgba(0,0,0,0.12);
            border-top: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            pointer-events: none;
        }
        .footer-strip span {
            color: rgba(255,255,255,0.45);
            font-size: 10.5px;
        }

        /* ── Responsive fallback (mobile) ── */
        @media (max-width: 640px) {
            body { overflow-y: auto; align-items: flex-start; padding: 20px 0; }
            .scene { aspect-ratio: unset; flex-direction: column; border-radius: 16px; width: 92vw; }
            .panel-left { flex: 0 0 auto; border-right: none; border-bottom: 1px solid var(--white-border); padding: 28px 24px; flex-direction: row; gap: 14px; justify-content: flex-start; }
            .brand-name { font-size: 13px; text-align: left; }
            .brand-sub, .divider-h, .badge { display: none; }
            .panel-right { padding: 24px 20px; }
            .fields-grid { grid-template-columns: 1fr; }
            .field-full { grid-column: 1; }
            .actions { flex-direction: column; }
            .login-link { font-size: 13px; }
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="scene">
        <!-- ── Left branding panel ── -->
        <div class="panel-left">
            <div class="logo-ring">
                <div class="logo-box">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo">
                </div>
            </div>

            <div class="brand-name">Dinas Perdagangan<br>dan Perindustrian</div>
            <div class="divider-h"></div>
            <div class="brand-sub">Sistem Informasi<br>Surat Metrologi</div>

            <div class="divider-h"></div>

            <div class="badge">
                <div class="badge-label">Tahun</div>
                <div class="badge-value">{{ date('Y') }}</div>
            </div>
        </div>

        <!-- ── Right form panel ── -->
        <div class="panel-right">
            <div class="form-title">Buat Akun</div>
            <div class="form-sub">Lengkapi data berikut untuk mendaftar</div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="fields-grid">
                    <!-- Nama -->
                    <div class="field-wrap field-full">
                        <label class="field-label" for="name">Nama Lengkap</label>
                        <input class="field-input" id="name" type="text" name="name"
                            value="{{ old('name') }}" placeholder="Nama lengkap Anda"
                            required autofocus autocomplete="name">
                        @error('name')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>

                    <!-- Email -->
                    <div class="field-wrap field-full">
                        <label class="field-label" for="email">Email</label>
                        <input class="field-input" id="email" type="email" name="email"
                            value="{{ old('email') }}" placeholder="nama@instansi.go.id"
                            required autocomplete="username">
                        @error('email')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>

                    <!-- Password -->
                    <div class="field-wrap">
                        <label class="field-label" for="password">Kata Sandi</label>
                        <input class="field-input" id="password" type="password" name="password"
                            placeholder="••••••••" required autocomplete="new-password">
                        @error('password')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="field-wrap">
                        <label class="field-label" for="password_confirmation">Konfirmasi Sandi</label>
                        <input class="field-input" id="password_confirmation" type="password"
                            name="password_confirmation" placeholder="••••••••"
                            required autocomplete="new-password">
                        @error('password_confirmation')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>

                    <!-- Kode Admin -->
                    <div class="field-wrap field-full">
                        <label class="field-label" for="admin_code">Kode Admin <span style="opacity:.55;font-weight:400;text-transform:none;letter-spacing:0;">(Opsional)</span></label>
                        <input class="field-input admin-input" id="admin_code" type="password"
                            name="admin_code" placeholder="Kosongkan jika bukan admin"
                            autocomplete="off">
                        <p class="field-hint">⚑ Isi hanya jika Anda mendaftar sebagai administrator sistem</p>
                        @error('admin_code')<p class="error-msg">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="actions">
                    <button type="submit" class="btn-register">Daftar Sekarang →</button>
                    <a href="{{ route('login') }}" class="login-link">Sudah punya akun?<br>Masuk di sini</a>
                </div>
            </form>
        </div>

        <!-- Footer strip -->
        <div class="footer-strip">
            <span>&copy; {{ date('Y') }} Dinas Perdagangan dan Perindustrian &mdash; Hak cipta dilindungi undang-undang</span>
        </div>
    </div>
</body>
</html>
=======
<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
>>>>>>> ae1b02b (Add full Laravel project fresh)
