<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MABIPRO Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
            body {
                font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
                background: linear-gradient(135deg, #0f2340 0%, #1e3a5f 50%, #0f4c75 100%);
                min-height: 100vh;
                display: flex; align-items: center; justify-content: center;
                padding: 24px;
            }

            .login-card {
                background: #ffffff;
                border-radius: 16px;
                box-shadow: 0 25px 60px rgba(0,0,0,0.3);
                width: 100%;
                max-width: 420px;
                overflow: hidden;
            }
            .login-header {
                background: linear-gradient(135deg, #1e3a5f 0%, #0f2340 100%);
                padding: 32px 32px 28px;
                text-align: center;
            }
            .login-logo {
                font-size: 28px;
                font-weight: 700;
                color: #ffffff;
                letter-spacing: 0.05em;
            }
            .login-logo-sub {
                font-size: 12px;
                color: rgba(255,255,255,0.5);
                text-transform: uppercase;
                letter-spacing: 0.1em;
                margin-top: 4px;
            }
            .login-body { padding: 32px; }
            .login-title {
                font-size: 20px; font-weight: 700; color: #1e293b;
                margin-bottom: 4px;
            }
            .login-subtitle { font-size: 13px; color: #64748b; margin-bottom: 24px; }

            .form-group { margin-bottom: 18px; }
            .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
            .form-control {
                width: 100%; padding: 11px 14px; border-radius: 8px;
                border: 1px solid #d1d5db; font-size: 14px; color: #111827;
                outline: none; transition: border-color 0.15s, box-shadow 0.15s;
                font-family: inherit;
            }
            .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.12); }
            .form-control.is-invalid { border-color: #ef4444; }
            .form-error { font-size: 12px; color: #dc2626; margin-top: 5px; }

            .form-check { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
            .form-check input { width: 15px; height: 15px; cursor: pointer; accent-color: #1e3a5f; }
            .form-check label { font-size: 13px; color: #475569; cursor: pointer; }

            .alert-error {
                background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px;
                padding: 11px 14px; font-size: 13px; color: #dc2626; margin-bottom: 18px;
                display: flex; align-items: center; gap: 8px;
            }
            .alert-success {
                background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;
                padding: 11px 14px; font-size: 13px; color: #166534; margin-bottom: 18px;
            }
            .alert-error svg { width: 16px; height: 16px; flex-shrink: 0; }

            .btn-login {
                width: 100%; padding: 12px; border-radius: 8px;
                background: linear-gradient(135deg, #1e3a5f, #0f4c75);
                color: #fff; font-size: 15px; font-weight: 600;
                border: none; cursor: pointer; font-family: inherit;
                transition: opacity 0.15s, transform 0.1s;
            }
            .btn-login:hover { opacity: 0.92; transform: translateY(-1px); }
            .btn-login:active { transform: translateY(0); }

            .login-footer {
                padding: 16px 32px;
                background: #f8fafc;
                border-top: 1px solid #f1f5f9;
                text-align: center;
                font-size: 12px;
                color: #94a3b8;
            }
        </style>
    @endif
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <div class="login-logo">MABIPRO</div>
            <div class="login-logo-sub">Manajemen Properti</div>
        </div>

        <div class="login-body">
            <h1 class="login-title">Masuk ke Sistem</h1>
            <p class="login-subtitle">Silakan masukkan kredensial Anda.</p>

            {{-- Alert error login --}}
            @if ($errors->has('email'))
                <div class="alert-error" role="alert">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $errors->first('email') }}
                </div>
            @endif

            {{-- Alert success (misal setelah logout) --}}
            @if (session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="contoh@email.com"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-control"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    >
                </div>

                <div class="form-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="btn-login" id="btn-login">
                    Masuk
                </button>
            </form>
        </div>

        <div class="login-footer">
            &copy; {{ date('Y') }} MABIPRO. Hak cipta dilindungi.
        </div>
    </div>
</body>
</html>
