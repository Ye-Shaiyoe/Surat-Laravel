<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Direktorat Metrologi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f2044 0%, #1a3a6e 30%, #0d5c8a 60%, #0a4f7a 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .orb1 {
            position: fixed;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(56,189,248,0.25) 0%, transparent 70%);
            top: -150px; left: -150px;
            border-radius: 50%;
            pointer-events: none;
        }
        .orb2 {
            position: fixed;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%);
            bottom: 0; right: -100px;
            border-radius: 50%;
            pointer-events: none;
        }
        .orb3 {
            position: fixed;
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(14,165,233,0.18) 0%, transparent 70%);
            bottom: 150px; left: 80px;
            border-radius: 50%;
            pointer-events: none;
        }

        .particle {
            position: fixed;
            border-radius: 50%;
            background: rgba(255,255,255,0.12);
            animation: float 8s ease-in-out infinite;
            pointer-events: none;
        }
        .p1 { width:6px;height:6px; top:15%;left:20%; animation-delay:0s; }
        .p2 { width:4px;height:4px; top:60%;left:75%; animation-delay:2s; }
        .p3 { width:8px;height:8px; top:35%;left:85%; animation-delay:4s; }
        .p4 { width:3px;height:3px; top:80%;left:30%; animation-delay:1s; }
        .p5 { width:5px;height:5px; top:50%;left:10%; animation-delay:3s; }

        @keyframes float {
            0%,100%{transform:translateY(0);}
            50%{transform:translateY(-14px);}
        }

        .logo-pill {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 50px;
            padding: 10px 20px 10px 10px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.2), inset 0 1px 0 rgba(255,255,255,0.15);
        }

        .logo-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #38bdf8, #0284c7);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 12px rgba(56,189,248,0.4);
            flex-shrink: 0;
        }

        .glass-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 24px;
            padding: 40px 36px 36px;
            box-shadow:
                0 8px 48px rgba(0,0,0,0.3),
                0 2px 0 rgba(255,255,255,0.12) inset,
                0 -1px 0 rgba(0,0,0,0.1) inset;
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        .icon-wrap {
            width: 80px; height: 80px;
            background: rgba(56,189,248,0.18);
            border: 1px solid rgba(56,189,248,0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 0 32px rgba(56,189,248,0.2);
        }

        .divider-glow {
            width: 40px; height: 2px;
            background: linear-gradient(90deg, transparent, rgba(56,189,248,0.6), transparent);
            margin: 0 auto 28px;
            border-radius: 2px;
        }

        .btn-primary {
            display: block;
            width: 100%;
            padding: 13px 20px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            letter-spacing: 0.01em;
            background: linear-gradient(135deg, #38bdf8 0%, #0284c7 100%);
            color: #fff;
            box-shadow: 0 4px 20px rgba(56,189,248,0.4), 0 1px 0 rgba(255,255,255,0.2) inset;
            margin-bottom: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(56,189,248,0.55), 0 1px 0 rgba(255,255,255,0.2) inset;
        }

        .btn-secondary {
            display: block;
            width: 100%;
            padding: 13px 20px;
            border-radius: 14px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            letter-spacing: 0.01em;
            background: rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.9);
            border: 1px solid rgba(255,255,255,0.2);
            box-shadow: 0 2px 12px rgba(0,0,0,0.15), 0 1px 0 rgba(255,255,255,0.1) inset;
            transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-secondary:hover {
            transform: translateY(-2px);
            background: rgba(255,255,255,0.16);
            box-shadow: 0 6px 20px rgba(0,0,0,0.2), 0 1px 0 rgba(255,255,255,0.15) inset;
        }
    </style>
</head>
<body>
    <!-- Orbs & Particles -->
    <div class="orb1"></div>
    <div class="orb2"></div>
    <div class="orb3"></div>
    <div class="particle p1"></div>
    <div class="particle p2"></div>
    <div class="particle p3"></div>
    <div class="particle p4"></div>
    <div class="particle p5"></div>

    <div class="relative z-10 flex flex-col min-h-screen">
        <!-- Header -->
        <div class="flex justify-end items-center py-6 px-8">
            <div class="logo-pill">
                <div class="logo-icon">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div>
                    <h2 style="font-size:13px; font-weight:600; color:#fff;">Direktorat Metrologi</h2>
                    <p style="font-size:10px; color:rgba(255,255,255,0.6); margin-top:1px;">Kementerian Perdagangan</p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex items-center justify-center px-4 pb-10">
            <div class="glass-card">
                <!-- Icon -->
                <div class="icon-wrap">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="">
                    </a>
                </div>

                <h1 style="font-size:26px; font-weight:700; color:#fff; letter-spacing:-0.02em; margin-bottom:6px;">
                    Selamat Datang
                </h1>
                <p style="font-size:13px; color:rgba(255,255,255,0.55); margin-bottom:24px; line-height:1.5;">
                    Sistem Informasi Direktorat Metrologi
                </p>

                <div class="divider-glow"></div>

                <!-- Buttons -->
                <a href="{{ route('login') }}" class="btn-primary">Login</a>
                <a href="{{ route('register') }}" class="btn-secondary">Register</a>

                <p style="font-size:10px; color:rgba(255,255,255,0.3); margin-top:28px; letter-spacing:0.02em;">
                    &copy; {{ date('Y') }} Direktorat Metrologi. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</body>
</html>