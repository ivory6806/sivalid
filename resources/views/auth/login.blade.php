<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIVALID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #059669;
            --primary-dark: #047857;
            --primary-glow: rgba(5,150,105,.35);
            --toggle-size: 52px;
        }

        /* ===== THEME VARIABLES ===== */
        [data-theme="dark"] {
            --bg-body: #0a0f1a;
            --card-bg: rgba(255,255,255,.03);
            --card-border: rgba(255,255,255,.08);
            --card-shadow-1: rgba(0,0,0,.4);
            --card-shadow-2: rgba(5,150,105,.06);
            --text-primary: #f1f5f9;
            --text-secondary: rgba(148,163,184,.7);
            --text-muted-icon: rgba(148,163,184,.4);
            --text-placeholder: rgba(148,163,184,.35);
            --input-bg: rgba(255,255,255,.04);
            --input-bg-focus: rgba(255,255,255,.06);
            --input-border: rgba(255,255,255,.08);
            --divider: rgba(255,255,255,.1);
            --label-color: rgba(203,213,225,.7);
            --error-color: #fca5a5;
            --error-bg: rgba(248,113,113,.08);
            --error-border: rgba(248,113,113,.15);
            --toggle-bg: rgba(255,255,255,.08);
            --toggle-icon: #94a3b8;
            --grid-line: rgba(255,255,255,.02);
            --orb-opacity: .5;
        }
        [data-theme="light"] {
            --bg-body: #f1f5f9;
            --card-bg: #ffffff;
            --card-border: #e2e8f0;
            --card-shadow-1: rgba(0,0,0,.08);
            --card-shadow-2: rgba(5,150,105,.04);
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --text-muted-icon: #94a3b8;
            --text-placeholder: #94a3b8;
            --input-bg: #f8fafc;
            --input-bg-focus: #ffffff;
            --input-border: #e2e8f0;
            --divider: #e2e8f0;
            --label-color: #475569;
            --error-color: #dc2626;
            --error-bg: #fef2f2;
            --error-border: #fecaca;
            --toggle-bg: #ffffff;
            --toggle-icon: #64748b;
            --grid-line: rgba(0,0,0,.03);
            --orb-opacity: .25;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: var(--bg-body);
            position: relative;
            overflow: hidden;
            transition: background .4s ease;
        }

        /* ===== ANIMATED BACKGROUND ===== */
        .bg-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: var(--orb-opacity);
            pointer-events: none;
            transition: opacity .4s;
        }
        .bg-orb-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(5,150,105,.4), transparent 70%);
            top: -15%; left: -10%;
            animation: float-1 12s ease-in-out infinite;
        }
        .bg-orb-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(13,148,136,.35), transparent 70%);
            bottom: -10%; right: -8%;
            animation: float-2 10s ease-in-out infinite;
        }
        .bg-orb-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(52,211,153,.2), transparent 70%);
            top: 50%; left: 60%;
            animation: float-3 14s ease-in-out infinite;
        }
        @keyframes float-1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(60px, 40px) scale(1.1); }
            66% { transform: translate(-30px, 80px) scale(.95); }
        }
        @keyframes float-2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(-50px, -30px) scale(1.05); }
            66% { transform: translate(40px, -60px) scale(.9); }
        }
        @keyframes float-3 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-80px, 50px) scale(1.15); }
        }

        /* Grid pattern overlay */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(var(--grid-line) 1px, transparent 1px),
                linear-gradient(90deg, var(--grid-line) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
            z-index: 0;
            transition: background-image .4s;
        }

        /* ===== THEME TOGGLE ===== */
        .theme-toggle {
            position: fixed;
            bottom: 28px;
            right: 28px;
            z-index: 100;
            width: var(--toggle-size);
            height: var(--toggle-size);
            border-radius: 16px;
            background: var(--toggle-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .3s cubic-bezier(.4,0,.2,1);
            box-shadow: 0 4px 20px rgba(0,0,0,.15);
        }
        .theme-toggle:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 30px rgba(0,0,0,.2);
        }
        .theme-toggle:active { transform: translateY(0) scale(.97); }
        .theme-toggle i {
            font-size: 1.3rem;
            color: var(--toggle-icon);
            transition: all .35s cubic-bezier(.4,0,.2,1);
            position: absolute;
        }
        .theme-toggle .icon-moon { opacity: 1; transform: rotate(0deg) scale(1); }
        .theme-toggle .icon-sun  { opacity: 0; transform: rotate(-90deg) scale(.5); }
        [data-theme="light"] .theme-toggle .icon-moon { opacity: 0; transform: rotate(90deg) scale(.5); }
        [data-theme="light"] .theme-toggle .icon-sun  { opacity: 1; transform: rotate(0deg) scale(1); }

        /* ===== CARD ===== */
        .login-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
            background: var(--card-bg);
            backdrop-filter: blur(40px) saturate(1.5);
            -webkit-backdrop-filter: blur(40px) saturate(1.5);
            border-radius: 24px;
            border: 1px solid var(--card-border);
            box-shadow:
                0 0 0 1px rgba(255,255,255,.04),
                0 30px 80px var(--card-shadow-1),
                0 0 120px var(--card-shadow-2);
            overflow: hidden;
            animation: card-in .8s cubic-bezier(.16,1,.3,1) both;
            transition: background .4s, border-color .4s, box-shadow .4s;
        }
        [data-theme="light"] .login-wrapper {
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
            box-shadow: 0 20px 60px rgba(0,0,0,.08), 0 0 80px rgba(5,150,105,.03);
        }
        @keyframes card-in {
            from { opacity: 0; transform: translateY(30px) scale(.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ===== HEADER ===== */
        .login-header {
            padding: 40px 36px 32px;
            text-align: center;
            position: relative;
        }
        .login-header::after {
            content: '';
            position: absolute;
            bottom: 0; left: 15%; width: 70%; height: 1px;
            background: linear-gradient(90deg, transparent, var(--divider), transparent);
            transition: background .4s;
        }

        .logo-container {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            position: relative;
        }
        .logo-ring {
            position: absolute; inset: 0;
            border-radius: 20px;
            border: 2px solid rgba(52,211,153,.2);
            animation: ring-pulse 3s ease-in-out infinite;
        }
        @keyframes ring-pulse {
            0%, 100% { transform: scale(1); opacity: .4; }
            50% { transform: scale(1.08); opacity: .15; }
        }
        .logo-circle {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(5,150,105,.25), rgba(13,148,136,.15));
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,.1);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            box-shadow: 0 8px 32px rgba(5,150,105,.2);
            transition: background .4s, border-color .4s, box-shadow .4s;
        }
        [data-theme="light"] .logo-circle {
            background: linear-gradient(135deg, #ecfdf5, #f0fdfa);
            border-color: #a7f3d0;
            box-shadow: 0 4px 16px rgba(5,150,105,.1);
        }
        .logo-circle img {
            width: 48px;
            height: 48px;
            object-fit: contain;
        }

        .login-header h1 {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--text-primary);
            letter-spacing: .08em;
            margin-bottom: 6px;
            transition: color .4s;
        }
        .login-header p {
            font-size: .82rem;
            color: var(--text-secondary);
            margin: 0;
            font-weight: 400;
            transition: color .4s;
        }

        /* ===== BODY ===== */
        .login-body { padding: 28px 36px 8px; }

        .form-group-custom { margin-bottom: 20px; }
        .form-label {
            font-size: .78rem;
            font-weight: 600;
            color: var(--label-color);
            margin-bottom: 8px;
            display: block;
            letter-spacing: .3px;
            transition: color .4s;
        }
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-wrapper .input-icon {
            position: absolute;
            left: 14px;
            font-size: 1rem;
            color: var(--text-muted-icon);
            pointer-events: none;
            transition: color .2s;
            z-index: 2;
        }
        .input-wrapper input.form-control {
            width: 100%;
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 12px;
            padding: 12px 14px 12px 42px;
            font-size: .9rem;
            color: var(--text-primary);
            transition: all .25s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .input-wrapper input.form-control::placeholder {
            color: var(--text-placeholder);
        }
        .input-wrapper input.form-control:focus {
            background: var(--input-bg-focus);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--primary-glow), 0 0 20px rgba(5,150,105,.08);
            outline: none;
        }
        .input-wrapper input.form-control.is-invalid {
            border-color: #f87171;
            box-shadow: 0 0 0 3px rgba(248,113,113,.15);
        }

        .pwd-toggle {
            position: absolute;
            right: 4px; top: 50%; transform: translateY(-50%);
            background: none; border: none;
            color: var(--text-muted-icon);
            padding: 8px 12px;
            cursor: pointer;
            font-size: 1.05rem;
            transition: color .2s;
            z-index: 2;
            border-radius: 0 12px 12px 0;
        }
        .pwd-toggle:hover { color: var(--text-secondary); }

        .input-wrapper:has(input.form-control:focus) .input-icon { color: #34d399; }

        .invalid-feedback {
            font-size: .75rem;
            color: var(--error-color);
            margin-top: 6px;
            padding-left: 2px;
            transition: color .4s;
        }

        /* ===== BUTTON ===== */
        .btn-login {
            width: 100%;
            padding: 13px;
            border-radius: 12px;
            background: linear-gradient(135deg, #059669, #0d9488);
            color: #fff;
            font-weight: 700;
            font-size: .95rem;
            border: none;
            cursor: pointer;
            transition: all .3s cubic-bezier(.4,0,.2,1);
            position: relative;
            overflow: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: .2px;
            margin-top: 4px;
        }
        .btn-login::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,.15) 50%, transparent 60%);
            transform: translateX(-100%);
            transition: transform .6s;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(5,150,105,.35), 0 0 60px rgba(5,150,105,.1);
            color: #fff;
        }
        .btn-login:hover::before { transform: translateX(100%); }
        .btn-login:active { transform: translateY(0); }

        /* ===== FOOTER ===== */
        .login-footer {
            text-align: center;
            padding: 16px 36px 28px;
            font-size: .82rem;
        }
        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all .2s;
            position: relative;
        }
        .login-footer a::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0;
            width: 0; height: 1.5px;
            background: var(--primary);
            transition: width .3s;
        }
        .login-footer a:hover::after { width: 100%; }

        /* Error alert */
        .alert-error-custom {
            background: var(--error-bg);
            border: 1px solid var(--error-border);
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: .84rem;
            color: var(--error-color);
            animation: shake .4s ease-in-out;
            transition: background .4s, border-color .4s, color .4s;
        }
        .alert-error-custom i { font-size: 1.1rem; color: #f87171; flex-shrink: 0; }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            75% { transform: translateX(6px); }
        }

        @media (max-width: 480px) {
            .login-wrapper { border-radius: 20px; }
            .login-header { padding: 32px 24px 24px; }
            .login-body { padding: 24px 24px 4px; }
            .login-footer { padding: 14px 24px 24px; }
            .logo-container { width: 68px; height: 68px; }
            .logo-circle { width: 68px; height: 68px; border-radius: 16px; }
            .logo-circle img { width: 40px; height: 40px; }
            .login-header h1 { font-size: 1.4rem; }
            .theme-toggle { bottom: 20px; right: 20px; width: 46px; height: 46px; border-radius: 14px; }
        }
    </style>
</head>
<body>

    <!-- Background Orbs -->
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>
    
    <div class="login-wrapper">
        <div class="login-header">
            <div class="logo-container">
                <div class="logo-ring"></div>
                <div class="logo-circle">
                    <img src="/images/logo.png" alt="Logo Politeknik LPP">
                </div>
            </div>
            <h1>SIVALID</h1>
            <p>Sistem Informasi Validasi</p>
        </div>

        <div class="login-body">
            @if(session('error'))
            <div class="alert-error-custom">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="alert-error-custom" style="background: rgba(248,113,113,.1); border-color: rgba(248,113,113,.2);">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ $errors->first('email') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group-custom">
                    <label class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i class="bi bi-envelope input-icon"></i>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                    </div>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group-custom">
                    <label class="form-label">Password</label>
                    <div class="input-wrapper">
                        <i class="bi bi-lock input-icon"></i>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Masukkan password" id="pwdInput">
                        <button class="pwd-toggle" type="button" onclick="togglePwd()">
                            <i class="bi bi-eye" id="pwdIcon"></i>
                        </button>
                    </div>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk
                </button>
            </form>
        </div>

        <div class="login-footer">
            <a href="{{ route('password.request') }}">Lupa password?</a>
        </div>
    </div>

    <script>
    function togglePwd() {
        const i = document.getElementById('pwdInput');
        const ic = document.getElementById('pwdIcon');
        if (i.type === 'password') { 
            i.type = 'text'; 
            ic.className = 'bi bi-eye-slash'; 
        } else { 
            i.type = 'password'; 
            ic.className = 'bi bi-eye'; 
        }
    }
    </script>
</body>
</html>