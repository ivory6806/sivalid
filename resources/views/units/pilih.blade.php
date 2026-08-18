<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pilih Unit - {{ config('site.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f8fafc; min-height: 100vh; }
    </style>
</head>
<body>

<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px">
    <div style="width:100%;max-width:480px">
        <div style="text-align:center;margin-bottom:24px">
            <div style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#059669 0%,#0d9488 100%);display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
                <i class="bi bi-diagram-3-fill" style="font-size:1.4rem;color:#fff"></i>
            </div>
            <div style="font-size:1.15rem;font-weight:800;color:#1e293b">Pilih Unit</div>
            <div style="font-size:.84rem;color:#64748b;margin-top:4px">Halo {{ auth()->user()->name }}, Anda menjabat di lebih dari satu unit. Pilih salah satu untuk melanjutkan.</div>
        </div>

        @if(session('error'))
        <div style="padding:10px 14px;border-radius:10px;background:#fef2f2;color:#ef4444;font-size:.82rem;margin-bottom:16px">
            {{ session('error') }}
        </div>
        @endif

        <div style="display:flex;flex-direction:column;gap:10px">
            @foreach($memberships as $m)
            <form method="POST" action="{{ route('units.select.choose', $m) }}">
                @csrf
                <button type="submit" style="width:100%;text-align:left;display:flex;align-items:center;gap:14px;padding:16px 18px;border-radius:14px;border:1.5px solid #e2e8f0;background:#fff;cursor:pointer;transition:all .15s"
                    onmouseover="this.style.borderColor='#059669';this.style.boxShadow='0 4px 14px rgba(5,150,105,.1)'"
                    onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                    <div style="width:44px;height:44px;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;
                        {{ $m->role === 'kepala' ? 'background:#ecfdf5;color:#059669' : 'background:#eff6ff;color:#3b82f6' }}">
                        <i class="bi bi-{{ $m->role === 'kepala' ? 'person-badge' : 'person' }}" style="font-size:1.1rem"></i>
                    </div>
                    <div style="flex:1">
                        <div style="font-size:.92rem;font-weight:700;color:#1e293b">{{ $m->unit->name }}</div>
                        <div style="font-size:.78rem;color:#64748b;margin-top:1px">{{ $m->jabatan ?: 'Sebagai ' . ($m->role === 'kepala' ? 'Kepala' : 'Staff') }}</div>
                    </div>
                    <i class="bi bi-chevron-right" style="color:#94a3b8"></i>
                </button>
            </form>
            @endforeach
        </div>

        <div style="text-align:center;margin-top:20px">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:none;border:none;font-size:.8rem;color:#94a3b8;cursor:pointer;text-decoration:underline">
                    Keluar dari akun ini
                </button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
