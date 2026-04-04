<<<<<<< HEAD
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Surat Metrologi</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0c4a8f 0%, #1a6fbc 40%, #2d9de8 70%, #e8f4ff 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 32px 16px;
            position: relative; overflow: hidden; overflow-y: auto;
            font-family: 'Segoe UI', sans-serif;
        }
        .bg-circle1 { position: absolute; width: 400px; height: 400px; border-radius: 50%; background: rgba(255,255,255,0.07); top: -100px; left: -100px; }
        .bg-circle2 { position: absolute; width: 250px; height: 250px; border-radius: 50%; background: rgba(255,255,255,0.05); bottom: -60px; right: -60px; }
        .bg-circle3 { position: absolute; width: 180px; height: 180px; border-radius: 50%; background: rgba(255,255,255,0.06); bottom: 100px; left: 60px; }
        .header-logo { display: flex; flex-direction: column; align-items: center; margin-bottom: 20px; z-index: 1; }
        .logo-circle { width: 72px; height: 72px; border-radius: 50%; background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.5); display: flex; align-items: center; justify-content: center; margin-bottom: 12px; backdrop-filter: blur(10px); }
        .logo-inner { width: 42px; height: 42px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .header-title { color: white; font-size: 16px; font-weight: 600; text-align: center; }
        .header-sub { color: rgba(255,255,255,0.8); font-size: 13px; text-align: center; margin-top: 2px; }
        .glass-card { background: rgba(255,255,255,0.18); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.35); border-radius: 20px; padding: 36px 32px; width: 100%; max-width: 420px; z-index: 1; }
        .card-title { color: white; font-size: 22px; font-weight: 700; text-align: center; margin-bottom: 4px; }
        .card-sub { color: rgba(255,255,255,0.75); font-size: 13px; text-align: center; margin-bottom: 24px; }
        .divider { height: 1px; background: rgba(255,255,255,0.2); margin-bottom: 24px; }
        .field-label { color: rgba(255,255,255,0.9); font-size: 13px; font-weight: 500; display: block; margin-bottom: 6px; }
        .field-wrap { margin-bottom: 18px; }
        .field-input { width: 100%; padding: 12px 16px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); border-radius: 10px; color: white; font-size: 14px; outline: none; font-family: inherit; }
        .field-input::placeholder { color: rgba(255,255,255,0.5); }
        .field-input:focus { border-color: rgba(255,255,255,0.7); background: rgba(255,255,255,0.22); }
        .remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
        .remember-row input { accent-color: white; width: 15px; height: 15px; }
        .remember-row label { color: rgba(255,255,255,0.85); font-size: 13px; cursor: pointer; }
        .forgot { display: block; text-align: right; color: rgba(255,255,255,0.8); font-size: 12px; margin-bottom: 20px; text-decoration: none; }
        .forgot:hover { color: white; }
        .register { display: block; text-align: center; color: rgba(0, 0, 0, 0.8); font-size: 12px; margin-bottom: 20px; text-decoration: none; }
        .btn-login { width: 100%; padding: 13px; background: white; color: #0c4a8f; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; font-family: inherit; }
        .btn-login:hover { background: #e8f4ff; }
        .footer-text { color: rgba(255,255,255,0.6); font-size: 11px; text-align: center; margin-top: 20px; z-index: 1; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="bg-circle1"></div>
    <div class="bg-circle2"></div>
    <div class="bg-circle3"></div>

    <div class="header-logo">
        <div class="logo-circle">
        <div class="logo-inner">
            <img src="{{ asset('images/Logo.png') }}" alt="Logo" style="width:42px;height:42px;object-fit:contain;">
            </div>
            </div>
        </div>
        <div class="header-title">Dinas Perdagangan dan Perindustrian</div>
        <div class="header-sub">Sistem Informasi Surat Direktro Metrologi</div>
    </div>
<br>
    <div class="glass-card">
        <div class="card-title">Selamat Datang</div>
        <div class="card-sub">Silakan masuk untuk melanjutkan</div>
        <div class="divider"></div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="field-wrap">
                <label class="field-label" for="email">Email</label>
                <input class="field-input" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@instansi.go.id" required autofocus>
                @error('email')<p style="color:#fca5a5;font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
            </div>
            <div class="field-wrap">
                <label class="field-label" for="password">Kata Sandi</label>
                <input class="field-input" id="password" type="password" name="password" placeholder="••••••••" required>
                @error('password')<p style="color:#fca5a5;font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
            </div>
            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot">Lupa kata sandi?</a>
                
            @endif

            <button type="submit" class="btn-login">Masuk</button>
        </form>
        <br>
        <a href="{{ route('register') }}" class="register">belum ada akun?</a>
    </div>

    <div class="footer-text">
        &copy; {{ date('Y') }} Dinas Perdagangan dan Perindustrian<br>
        Hak cipta dilindungi undang-undang
    </div>
</body>
</html>
=======
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
>>>>>>> ae1b02b (Add full Laravel project fresh)
