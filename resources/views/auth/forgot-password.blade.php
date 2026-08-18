<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - {{ config('site.name', 'SIVALID') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset(config('site.favicon', 'images/logo.png')) }}">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #059669;
            --primary-dark: #047857;
            --primary-glow: rgba(5,150,105,.15);
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #f1f5f9;
            position: relative;
            overflow: hidden;
        }

        /* ===== ANIMATED BACKGROUND ===== */
        .bg-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: .35;
            pointer-events: none;
        }
        .bg-orb-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(5,150,105,.35), transparent 70%);
            top: -15%; left: -10%;
            animation: float-1 12s ease-in-out infinite;
        }
        .bg-orb-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(13,148,136,.3), transparent 70%);
            bottom: -10%; right: -8%;
            animation: float-2 10s ease-in-out infinite;
        }
        .bg-orb-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(52,211,153,.25), transparent 70%);
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

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: 
                linear-gradient(rgba(0,0,0,.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,.025) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
            z-index: 0;
        }

        /* ===== CARD ===== */
        .forgot-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
            background: #ffffff;
            border-radius: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 20px 60px rgba(0,0,0,.06), 0 0 100px rgba(5,150,105,.04);
            overflow: hidden;
            animation: card-in .8s cubic-bezier(.16,1,.3,1) both;
        }
        @keyframes card-in {
            from { opacity: 0; transform: translateY(30px) scale(.96); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ===== HEADER ===== */
        .forgot-header {
            padding: 36px 36px 28px;
            text-align: center;
            position: relative;
        }
        .forgot-header::after {
            content: '';
            position: absolute;
            bottom: 0; left: 15%; width: 70%; height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        }

        .icon-container {
            width: 72px;
            height: 72px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: linear-gradient(135deg, #eef2ff, #e0e7ff);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-shadow: 0 4px 16px rgba(67,56,202,.1);
        }
        .icon-container i {
            font-size: 1.8rem;
            color: #4338ca;
        }
        .icon-container::after {
            content: '';
            position: absolute; inset: -4px;
            border-radius: 50%;
            border: 2px solid rgba(67,56,202,.12);
            animation: ring-pulse 3s ease-in-out infinite;
        }
        @keyframes ring-pulse {
            0%, 100% { transform: scale(1); opacity: .5; }
            50% { transform: scale(1.08); opacity: .15; }
        }

        .forgot-header h1 {
            font-size: 1.35rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: .5rem;
        }
        .forgot-header p {
            font-size: .84rem;
            color: #64748b;
            line-height: 1.65;
            margin: 0;
        }

        /* ===== BODY ===== */
        .forgot-body { padding: 28px 36px 8px; }

        .form-group-custom { margin-bottom: 20px; }
        .form-label {
            font-size: .78rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
            display: block;
            letter-spacing: .3px;
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
            color: #94a3b8;
            pointer-events: none;
            transition: color .2s;
            z-index: 2;
        }
        .input-wrapper input.form-control {
            width: 100%;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 14px 12px 42px;
            font-size: .9rem;
            color: #0f172a;
            transition: all .25s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .input-wrapper input.form-control::placeholder { color: #94a3b8; }
        .input-wrapper input.form-control:focus {
            background: #ffffff;
            border-color: #4338ca;
            box-shadow: 0 0 0 3px rgba(67,56,202,.1);
            outline: none;
        }
        .input-wrapper input.form-control.is-invalid {
            border-color: #f87171;
            box-shadow: 0 0 0 3px rgba(248,113,113,.1);
        }
        .input-wrapper:has(input.form-control:focus) .input-icon { color: #4338ca; }

        .invalid-feedback {
            font-size: .75rem;
            color: #dc2626;
            margin-top: 6px;
            padding-left: 2px;
        }

        /* ===== BUTTON ===== */
        .btn-submit {
            width: 100%;
            padding: 13px;
            border-radius: 12px;
            background: linear-gradient(135deg, #4338ca, #6366f1);
            color: #fff;
            font-weight: 700;
            font-size: .92rem;
            border: none;
            cursor: pointer;
            transition: all .3s cubic-bezier(.4,0,.2,1);
            position: relative;
            overflow: hidden;
            font-family: 'Plus Jakarta Sans', sans-serif;
            letter-spacing: .2px;
            margin-top: 4px;
        }
        .btn-submit::before {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, transparent 40%, rgba(255,255,255,.2) 50%, transparent 60%);
            transform: translateX(-100%);
            transition: transform .6s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(67,56,202,.3);
            color: #fff;
        }
        .btn-submit:hover::before { transform: translateX(100%); }
        .btn-submit:active { transform: translateY(0); }

        /* ===== FOOTER ===== */
        .forgot-footer {
            text-align: center;
            padding: 16px 36px 28px;
            font-size: .82rem;
        }
        .forgot-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all .2s;
            position: relative;
        }
        .forgot-footer a::after {
            content: '';
            position: absolute;
            bottom: -2px; left: 0;
            width: 0; height: 1.5px;
            background: var(--primary);
            transition: width .3s;
        }
        .forgot-footer a:hover { color: var(--primary-dark); }
        .forgot-footer a:hover::after { width: 100%; }

        /* Success Alert */
        .alert-success-custom {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: .85rem;
            color: #065f46;
            animation: card-in .5s ease both;
        }
        .alert-success-custom i { font-size: 1.2rem; color: #059669; flex-shrink: 0; }

        @media (max-width: 480px) {
            .forgot-wrapper { border-radius: 20px; }
            .forgot-header { padding: 28px 24px 22px; }
            .forgot-body { padding: 24px 24px 4px; }
            .forgot-footer { padding: 14px 24px 24px; }
            .icon-container { width: 62px; height: 62px; }
            .icon-container i { font-size: 1.5rem; }
            .forgot-header h1 { font-size: 1.2rem; }
        }
    </style>
</head>
<body>

    <!-- Background Orbs -->
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
    <div class="bg-orb bg-orb-3"></div>

    <div class="forgot-wrapper">
        <div class="forgot-header">
            <div class="icon-container">
                <i class="bi bi-key-fill"></i>
            </div>
            <h1>Lupa Password?</h1>
            <p>Masukkan email Anda dan kami akan mengirimkan tautan untuk membuat password baru.</p>
        </div>

        <div class="forgot-body">
            <!-- Session Status -->
            @if(session('status'))
            <div class="alert-success-custom">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group-custom">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-wrapper">
                        <i class="bi bi-envelope input-icon"></i>
                        <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                    </div>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    <i class="bi bi-send me-2"></i>Kirim Link Reset Password
                </button>
            </form>
        </div>

        <div class="forgot-footer">
            <a href="{{ route('login') }}">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Login
            </a>
        </div>
    </div>

</body>
</html>