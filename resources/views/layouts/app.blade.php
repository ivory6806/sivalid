<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('site.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset(config('site.favicon')) }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #059669;
            --primary-dark: #047857;
            --primary-light: #ecfdf5;
            --sidebar-w: 272px;
            --topbar-h: 64px;
            --sidebar-bg: #0f172a;
            --sidebar-hover: #1e293b;
            --sidebar-active: rgba(5,150,105,.15);
            --sidebar-text: #94a3b8;
            --sidebar-text-active: #34d399;
            --topbar-bg: #ffffff;
            --body-bg: #f1f5f9;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--body-bg); min-height: 100vh; color: var(--text-dark); }

        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed; left: 0; top: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            z-index: 1040;
            display: flex; flex-direction: column;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            border-right: 1px solid rgba(255,255,255,.05);
        }
        .sidebar-brand {
            height: var(--topbar-h);
            display: flex; align-items: center; gap: 12px;
            padding: 0 20px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            text-decoration: none;
        }
        .sidebar-brand .brand-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), #0d9488);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 1.1rem; flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(5,150,105,.3);
        }
        .sidebar-brand .brand-text {
            font-weight: 800; font-size: 1.15rem; color: #f1f5f9;
            letter-spacing: -.3px;
        }
        .sidebar-brand .brand-sub {
            font-size: .62rem; color: var(--sidebar-text);
            font-weight: 500; letter-spacing: .3px; text-transform: uppercase;
            margin-top: -1px;
        }
        .sidebar-brand img { height: 38px; border-radius: 10px; flex-shrink: 0; }

        .sidebar-nav { flex: 1; padding: 20px 12px; overflow-y: auto; }
        .sidebar-label {
            font-size: .65rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: 2px; color: rgba(148,163,184,.5);
            padding: 12px 14px 8px; margin-top: 4px;
        }
        .nav-item { margin-bottom: 2px; }
        .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px; border-radius: 10px;
            color: var(--sidebar-text); font-size: .88rem; font-weight: 500;
            text-decoration: none; transition: all .2s;
            position: relative;
        }
        .nav-link:hover { background: var(--sidebar-hover); color: #e2e8f0; }
        .nav-link.active {
            background: var(--sidebar-active);
            color: var(--sidebar-text-active);
            font-weight: 600;
        }
        .nav-link.active::before {
            content: '';
            position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 24px; border-radius: 0 4px 4px 0;
            background: var(--primary);
        }
        .nav-link i { font-size: 1.15rem; width: 24px; text-align: center; flex-shrink: 0; }
        .nav-link .nav-badge {
            margin-left: auto; background: #ef4444; color: #fff;
            font-size: .65rem; font-weight: 700; padding: 2px 8px;
            border-radius: 10px; line-height: 1.4;
        }

        /* Sidebar Footer */
        .sidebar-footer {
            padding: 12px;
            border-top: 1px solid rgba(255,255,255,.06);
            margin: 0 12px 12px;
        }
        .sidebar-footer .user-link {
            display: flex; align-items: center; gap: 10px;
            padding: 12px; border-radius: 12px;
            background: rgba(255,255,255,.03);
            text-decoration: none; transition: all .2s;
        }
        .sidebar-footer .user-link:hover { background: rgba(255,255,255,.08); }
        .sidebar-footer .user-avatar {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), #0d9488);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 800; font-size: .85rem;
            flex-shrink: 0; letter-spacing: -.5px;
        }
        .sidebar-footer .user-info { flex: 1; min-width: 0; }
        .sidebar-footer .user-name {
            font-size: .82rem; font-weight: 600; color: #e2e8f0;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sidebar-footer .user-role { font-size: .68rem; color: var(--sidebar-text); font-weight: 500; }
        .sidebar-footer .user-go { color: rgba(255,255,255,.2); font-size: .9rem; transition: all .2s; flex-shrink: 0; }
        .sidebar-footer .user-link:hover .user-go { color: rgba(255,255,255,.5); transform: translateX(2px); }

        /* ========== MAIN ========== */
        .main-content { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

        /* Topbar */
        .topbar {
            height: var(--topbar-h);
            background: var(--topbar-bg);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 28px;
            position: sticky; top: 0; z-index: 1030;
        }
        .topbar-left { display: flex; align-items: center; gap: 16px; }
        .topbar-title { font-size: 1.05rem; font-weight: 700; color: var(--text-dark); }
        .topbar-right { display: flex; align-items: center; gap: 12px; }
        .topbar-time { font-size: .82rem; color: var(--text-muted); font-weight: 500; }
        .btn-logout {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 10px;
            border: 1px solid var(--border); background: var(--card-bg);
            color: var(--text-muted); font-size: .82rem; font-weight: 600;
            cursor: pointer; transition: all .2s; text-decoration: none;
        }
        .btn-logout:hover { border-color: #fca5a5; color: #ef4444; background: #fef2f2; }

        /* Content */
        .content-area { padding: 28px; flex: 1; }

        /* Flash Messages */
        .flash-success {
            background: var(--primary-light); color: #065f46;
            border: 1px solid #a7f3d0; border-radius: 12px;
            padding: 14px 18px; margin-bottom: 24px;
            display: flex; align-items: center; gap: 10px;
            font-size: .88rem; font-weight: 500;
        }
        .flash-success i { font-size: 1.2rem; color: var(--primary); }
        .flash-error {
            background: #fef2f2; color: #991b1b;
            border: 1px solid #fecaca; border-radius: 12px;
            padding: 14px 18px; margin-bottom: 24px;
            display: flex; align-items: center; gap: 10px;
            font-size: .88rem; font-weight: 500;
        }
        .flash-error i { font-size: 1.2rem; color: #ef4444; }

        /* ========== COMPONENTS ========== */
        .stat-card {
            background: var(--card-bg); border-radius: 14px;
            padding: 22px; border: 1px solid var(--border);
            transition: all .25s cubic-bezier(.4,0,.2,1);
            position: relative; overflow: hidden;
        }
        .stat-card::after {
            content: ''; position: absolute; top: -30px; right: -30px;
            width: 80px; height: 80px; border-radius: 50%;
            opacity: .06; transition: opacity .3s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(0,0,0,.06); }
        .stat-card:hover::after { opacity: .1; }
        .stat-card .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
        .stat-card .stat-val { font-size: 1.7rem; font-weight: 900; color: var(--text-dark); letter-spacing: -.5px; }
        .stat-card .stat-lbl { font-size: .78rem; color: var(--text-muted); margin-top: 2px; font-weight: 500; }
        .stat-card.green .stat-icon { background: #ecfdf5; color: #059669; }
        .stat-card.green::after { background: #059669; }
        .stat-card.amber .stat-icon { background: #fffbeb; color: #d97706; }
        .stat-card.amber::after { background: #d97706; }
        .stat-card.red .stat-icon { background: #fef2f2; color: #ef4444; }
        .stat-card.red::after { background: #ef4444; }
        .stat-card.blue .stat-icon { background: #eff6ff; color: #3b82f6; }
        .stat-card.blue::after { background: #3b82f6; }
        .stat-card.purple .stat-icon { background: #f5f3ff; color: #7c3aed; }
        .stat-card.purple::after { background: #7c3aed; }

        .card-custom { background: var(--card-bg); border-radius: 14px; border: 1px solid var(--border); overflow: hidden; }
        .card-header-custom {
            padding: 18px 22px; border-bottom: 1px solid var(--border);
            font-weight: 700; font-size: .92rem; color: var(--text-dark);
            display: flex; align-items: center; justify-content: space-between;
        }
        .table-custom { margin: 0; }
        .table-custom th {
            font-size: .72rem; font-weight: 700; text-transform: uppercase;
            letter-spacing: .5px; color: var(--text-muted);
            border-bottom-width: 1px; padding: 12px 18px;
            background: #f8fafc;
        }
        .table-custom td { padding: 14px 18px; vertical-align: middle; font-size: .88rem; }
        .table-custom tbody tr { transition: background .15s; }
        .table-custom tbody tr:hover { background: #f8fafc; }

        .badge-status { padding: 5px 12px; border-radius: 8px; font-size: .75rem; font-weight: 600; letter-spacing: .2px; }

        .btn-primary-custom {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 20px; border-radius: 10px;
            background: var(--primary); color: #fff; border: none;
            font-size: .88rem; font-weight: 600; cursor: pointer;
            transition: all .2s; text-decoration: none;
        }
        .btn-primary-custom:hover {
            background: var(--primary-dark); color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(5,150,105,.25);
        }
        .btn-outline-custom {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 14px; border-radius: 8px;
            background: transparent; color: var(--text-muted);
            border: 1px solid var(--border); font-size: .82rem;
            font-weight: 500; cursor: pointer; transition: all .2s;
            text-decoration: none;
        }
        .btn-outline-custom:hover { border-color: #94a3b8; color: var(--text-dark); background: #f8fafc; }

        /* Overlay */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.5); z-index: 1035;
            backdrop-filter: blur(2px);
        }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .main-content { margin-left: 0; }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <!-- Brand -->
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <img src="{{ asset(config('site.logo')) }}" alt="Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
        <div class="brand-icon" style="display:none"><i class="bi bi-shield-check"></i></div>
        <div>
            <div class="brand-text">{{ config('site.name') }}</div>
            <div class="brand-sub">{{ config('site.tagline') }}</div>
        </div>
    </a>

    <!-- Nav -->
    <nav class="sidebar-nav">
        <div class="sidebar-label">Menu Utama</div>

        <div class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </div>

        @if(auth()->user()->isStaff() || auth()->user()->isSuperadmin())
        <div class="nav-item">
            <a href="{{ route('surat.create') }}" class="nav-link {{ request()->routeIs('surat.create') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-plus"></i>
                <span>Pengajuan Surat</span>
            </a>
        </div>
        @endif

        <div class="nav-item">
            <a href="{{ route('surat.index') }}" class="nav-link {{ request()->routeIs('surat.index') ? 'active' : '' }}">
                <i class="bi bi-folder2-open"></i>
                <span>Riwayat Surat</span>
            </a>
        </div>

        @if((session('active_unit_role') === 'kepala') || auth()->user()->isSuperadmin())
        <div class="nav-item">
            <a href="{{ route('validasi.index') }}" class="nav-link {{ request()->routeIs('validasi.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard2-check"></i>
                <span>Validasi Surat</span>
                @php $pending = \App\Models\Surat::whereIn('status', ['diajukan','direvisi'])->when(!auth()->user()->isSuperadmin(), fn($q) => $q->where('unit_id', session('active_unit_id')))->count(); @endphp
                @if($pending > 0)
                    <span class="nav-badge">{{ $pending }}</span>
                @endif
            </a>
        </div>
        @endif

        {{-- MENU SERTIFIKAT --}}
        <div class="sidebar-label" style="margin-top:16px">Sertifikat</div>

        @if(auth()->user()->isStaff() || auth()->user()->isSuperadmin())
        <div class="nav-item">
            <a href="{{ route('sertifikat.create') }}" class="nav-link {{ request()->routeIs('sertifikat.create') ? 'active' : '' }}">
                <i class="bi bi-patch-plus"></i>
                <span>Pengajuan Sertifikat</span>
            </a>
        </div>
        @endif

        <div class="nav-item">
            <a href="{{ route('sertifikat.index') }}" class="nav-link {{ request()->routeIs('sertifikat.index') || request()->routeIs('sertifikat.show') ? 'active' : '' }}">
                <i class="bi bi-award"></i>
                <span>Riwayat Sertifikat</span>
            </a>
        </div>

        @if(auth()->user()->isKepala() || auth()->user()->isSuperadmin())
        <div class="nav-item">
            <a href="{{ route('validasi-sertifikat.index') }}" class="nav-link {{ request()->routeIs('validasi-sertifikat.*') ? 'active' : '' }}">
                <i class="bi bi-qr-code-scan"></i>
                <span>Validasi Sertifikat</span>
                @php $pendingCert = \App\Models\Sertifikat::whereIn('status', ['diajukan','direvisi'])->count(); @endphp
                @if($pendingCert > 0)
                    <span class="nav-badge">{{ $pendingCert }}</span>
                @endif
            </a>
        </div>
        @endif

        {{-- MENU ADMIN --}}
        @if(auth()->user()->isSuperadmin())
        <div class="sidebar-label" style="margin-top:16px">Administrasi</div>
        <div class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Kelola User</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('units.index') }}" class="nav-link {{ request()->routeIs('units.*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3-fill"></i>
                <span>Kelola Unit</span>
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i>
                <span>Pengaturan</span>
            </a>
        </div>
        @endif
    </nav>

    <!-- Footer — klik profil -->
    <div class="sidebar-footer">
        <a href="{{ route('profile.edit') }}" class="user-link">
            <div class="user-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ auth()->user()->role_label }}</div>
            </div>
            <i class="bi bi-chevron-right user-go"></i>
        </a>
    </div>
</aside>

<!-- Main Content -->
<div class="main-content">
    <div class="topbar">
        <div class="topbar-left">
            <button class="btn-outline-custom d-lg-none" onclick="toggleSidebar()" style="border:none;padding:8px">
                <i class="bi bi-list" style="font-size:1.3rem"></i>
            </button>
            <span class="topbar-title">@yield('pageTitle', 'Dashboard')</span>
            
            {{-- FITUR SWITCH UNIT AKTIF --}}
            @if(!auth()->user()->isSuperadmin() && session('active_unit_id'))
                @php $__activeUnit = \App\Models\Unit::find(session('active_unit_id')); @endphp
                @if($__activeUnit)
                <a href="{{ route('units.switch') }}" class="d-none d-md-inline-flex" style="align-items:center;gap:6px;margin-left:16px;padding:5px 12px;border-radius:8px;background:#f1f5f9;color:var(--text-muted);font-size:.76rem;font-weight:600;text-decoration:none">
                    <i class="bi bi-diagram-3-fill" style="font-size:.75rem"></i>
                    {{ $__activeUnit->name }} &middot; {{ session('active_unit_role') === 'kepala' ? 'Kepala' : 'Staff' }}
                    <i class="bi bi-arrow-repeat" style="font-size:.75rem"></i>
                </a>
                @endif
            @endif
        </div>
        <div class="topbar-right">
            <span class="topbar-time d-none d-md-inline" id="liveTime"></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="d-none d-sm-inline">Keluar</span>
                </button>
            </form>
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="flash-success">
                <i class="bi bi-check-circle-fill"></i>
                <span style="flex:1">{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:#065f46;cursor:pointer;font-size:1.1rem"><i class="bi bi-x-lg"></i></button>
            </div>
        @endif
        @if(session('error'))
            <div class="flash-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span style="flex:1">{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" style="background:none;border:none;color:#991b1b;cursor:pointer;font-size:1.1rem"><i class="bi bi-x-lg"></i></button>
            </div>
        @endif

        @yield('content')
    </div>

    <!-- Footer Copyright -->
    <div style="text-align:center; padding: 16px 28px 24px; color: var(--text-muted); font-size: .75rem; font-weight: 500; letter-spacing: .2px; margin-top: auto; border-top: 1px solid var(--border);">
        Bagian Administrasi Akademik dan Beasiswa &copy; {{ date('Y') }} Politeknik LPP Yogyakarta
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}

// Jam live di topbar
function updateClock() {
    const now = new Date();
    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
    const str = days[now.getDay()] + ', ' + now.getDate() + ' ' + months[now.getMonth()] + ' ' + now.getFullYear() + ' &middot; ' +
        String(now.getHours()).padStart(2,'0') + ':' + String(now.getMinutes()).padStart(2,'0');
    document.getElementById('liveTime').innerHTML = str;
}
updateClock();
setInterval(updateClock, 30000);
</script>
</body>
</html>