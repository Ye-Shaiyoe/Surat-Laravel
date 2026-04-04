<<<<<<< HEAD
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - Sistem Surat Metrologi</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #0c4a8f 0%, #1a6fbc 40%, #2d9de8 70%, #e8f4ff 100%);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 32px 16px;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
            font-family: 'Segoe UI', sans-serif;
        }
        .bg-circle1 { position: absolute; width: 400px; height: 400px; border-radius: 50%; background: rgba(255,255,255,0.07); top: -100px; left: -100px; }
        .bg-circle2 { position: absolute; width: 250px; height: 250px; border-radius: 50%; background: rgba(255,255,255,0.05); bottom: -60px; right: -60px; }
        .bg-circle3 { position: absolute; width: 180px; height: 200px; border-radius: 50%; background: rgba(255,255,255,0.06); bottom: 100px; left: 60px; }
        .header-logo { display: flex; flex-direction: column; align-items: center; margin-bottom: 20px; z-index: 1; }
        .logo-circle { width: 72px; height: 72px; border-radius: 50%; background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.5); display: flex; align-items: center; justify-content: center; margin-bottom: 12px; backdrop-filter: blur(10px); }
        .logo-inner { width: 42px; height: 42px; background: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; }
        .header-title { color: white; font-size: 16px; font-weight: 600; text-align: center; }
        .header-sub { color: rgba(255,255,255,0.8); font-size: 13px; text-align: center; margin-top: 2px; }
        .glass-card { background: rgba(255,255,255,0.18); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.35); border-radius: 20px; padding: 36px 32px; width: 100%; max-width: 420px; z-index: 1; }
        .card-title { color: white; font-size: 22px; font-weight: 700; text-align: center; margin-bottom: 4px; }
        .card-sub { color: rgba(255,255,255,0.75); font-size: 13px; text-align: center; margin-bottom: 24px; line-height: 1.6; }
        .divider { height: 1px; background: rgba(255,255,255,0.2); margin-bottom: 24px; }
        .success-msg { color: #bbf7d0; font-size: 12px; margin-top: 10px; line-height: 1.5; text-align: center; }
        .btn-register { width: 100%; padding: 13px; background: white; color: #0c4a8f; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; font-family: inherit; margin-top: 4px; }
        .btn-register:hover { background: #e8f4ff; }
        .btn-secondary {
            width: 100%;
            padding: 12px;
            background: rgba(255,255,255,0.12);
            color: white;
            border: 1px solid rgba(255,255,255,0.35);
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            margin-top: 12px;
        }
        .btn-secondary:hover { background: rgba(255,255,255,0.18); }
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
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width:42px;height:42px;object-fit:contain;">
            </div>
        </div>
        <div class="header-title">Dinas Perdagangan dan Perindustrian</div>
        <div class="header-sub">Sistem Informasi Surat Metrologi</div>
    </div>

    <div class="glass-card">
        <div class="card-title">Verifikasi Email</div>
        <div class="card-sub">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        <div class="divider"></div>

        @if (session('status') == 'verification-link-sent')
            <div class="success-msg">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn-register">{{ __('Resend Verification Email') }}</button>
=======
<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
>>>>>>> ae1b02b (Add full Laravel project fresh)
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
<<<<<<< HEAD
            <button type="submit" class="btn-secondary">{{ __('Log Out') }}</button>
        </form>
    </div>

    <div class="footer-text">
        &copy; {{ date('Y') }} Dinas Perdagangan dan Perindustrian<br>
        Hak cipta dilindungi undang-undang
    </div>
</body>
</html>
=======

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
>>>>>>> ae1b02b (Add full Laravel project fresh)
