<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Surat - {{ config('site.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset(config('site.favicon')) }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: #0f172a;
            position: relative;
        }
        body::before {
            content: '';
            position: absolute;
            top: -40%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(5,150,105,.12) 0%, transparent 70%);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: absolute;
            left: -15%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(67,56,202,.08) 0%, transparent 70%);
            pointer-events: none;
        }

        .verify-card {
            width: 100%;
            max-width: 520px;
            background: #fff;
            border-radius: 16px;
            box-shadow:
                0 0 0 1px rgba(255,255,255,.05),
                0 25px 60px rgba(0,0,0,.35),
                0 0 80px rgba(0,0,0,.15);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        .verify-header {
            background: linear-gradient(135deg, #064e3b 0%, #059669 40%, #34d399 100%);
            padding: 28px 32px 24px;
            text-align: center;
            position: relative;
            border-bottom: 4px solid #eab308;
        }
        .verify-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40%;
            background: linear-gradient(to top, rgba(0,0,0,.12), transparent);
            pointer-events: none;
        }
        .verify-header > * { position: relative; z-index: 1; }
        .header-logo {
            width: 72px;
            height: 72px;
            margin: 0 auto 14px;
            object-fit: contain;
            filter: drop-shadow(0 4px 12px rgba(0,0,0,.25));
        }
        .verify-header h1 {
            font-size: 1.25rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: .08em;
            text-transform: uppercase;
            line-height: 1.3;
            text-shadow: 0 2px 8px rgba(0,0,0,.2);
        }
        .header-address {
            font-size: .7rem;
            color: rgba(255,255,255,.75);
            margin-top: 8px;
            line-height: 1.7;
        }
        .header-divider {
            width: 75%;
            height: 1px;
            background: rgba(255,255,255,.2);
            margin: 12px auto;
        }
        .header-sub {
            font-size: .8rem;
            font-weight: 600;
            color: rgba(255,255,255,.85);
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .status-banner {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 24px 32px 20px;
        }
        .status-icon-wrap {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            border: 3px solid #bbf7d0;
            background: #f0fdf4;
            box-shadow: 0 2px 12px rgba(5,150,105,.08);
            position: relative;
        }
        @keyframes pulse-ring {
            0% { transform: scale(1); opacity: .6; }
            50% { transform: scale(1.08); opacity: .25; }
            100% { transform: scale(1); opacity: .6; }
        }
        .status-icon-wrap::after {
            content: '';
            position: absolute;
            inset: -5px;
            border-radius: 50%;
            border: 2px solid rgba(5,150,105,.3);
            animation: pulse-ring 2.5s ease-in-out infinite;
        }
        .status-icon-wrap i { font-size: 1.6rem; color: #16a34a; }
        .status-banner h2 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: .02em;
        }
        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 8px;
            padding: 4px 14px;
            border-radius: 50px;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            background: #f0fdf4;
            color: #16a34a;
        }
        .status-chip i { font-size: .6rem; }

        .invalid-body { padding: 36px 32px; text-align: center; }
        .invalid-icon-big {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: linear-gradient(135deg, #fef2f2, #fee2e2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 4px 20px rgba(220,38,38,0.1);
        }
        .invalid-icon-big i { font-size: 2.2rem; color: #fca5a5; }
        .invalid-body h3 {
            font-size: 1.05rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }
        .invalid-body p {
            color: #64748b;
            font-size: .85rem;
            line-height: 1.7;
            max-width: 380px;
            margin: 0 auto;
        }
        .invalid-body strong { color: #0f172a; }
        .invalid-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 16px;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: .75rem;
            font-weight: 700;
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        .invalid-chip i { font-size: .7rem; }

        .verify-body { padding: 0 32px 8px; }

        .info-group { margin-bottom: 14px; }
        .info-group-title {
            font-size: .65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #94a3b8;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .info-group-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #f1f5f9;
        }
        .info-group-title i {
            font-size: .65rem;
            width: 20px;
            height: 20px;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .ig-green { background: #ecfdf5; color: #059669; }
        .ig-blue { background: #eff6ff; color: #2563eb; }
        .ig-amber { background: #fffbeb; color: #d97706; }
        .ig-purple { background: #f5f3ff; color: #7c3aed; }

        .info-rows {
            background: #f8fafc;
            border-radius: 10px;
            padding: 2px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 14px;
            font-size: .85rem;
            transition: background .15s;
        }
        .info-row:hover { background: #f1f5f9; }
        .info-row + .info-row { border-top: 1px solid #e2e8f0; }
        .info-row .label {
            color: #64748b;
            font-size: .8rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .info-row .label i {
            font-size: .6rem;
            color: #cbd5e1;
            width: 14px;
            text-align: center;
        }
        .info-row .value {
            font-weight: 600;
            color: #0f172a;
            text-align: right;
            font-size: .83rem;
        }

        .verify-footer {
            text-align: center;
            padding: 16px 32px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
        .verify-footer p {
            font-size: .7rem;
            color: #94a3b8;
            line-height: 1.6;
        }

        @media (max-width: 520px) {
            body { padding: 16px; }
            .verify-header { padding: 22px 20px 20px; }
            .header-logo { width: 60px; height: 60px; }
            .verify-header h1 { font-size: 1.05rem; }
            .status-banner { padding: 20px 20px 16px; }
            .verify-body { padding: 0 20px 4px; }
            .info-row { padding: 7px 12px; font-size: .8rem; }
            .info-row .value { font-size: .78rem; }
            .invalid-body { padding: 28px 20px; }
            .verify-footer { padding: 14px 20px; }
        }
    </style>
</head>
<body>
    @php $bulan = [ 'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April', 'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember' ]; @endphp
    <div class="verify-card">
        <div class="verify-header">
            <img src="/images/logo.png" class="header-logo" alt="Logo Politeknik LPP">
            <h1>Politeknik LPP Yogyakarta</h1>
            <p class="header-address">
                Jl. LPP No.1 Yogyakarta 55222 Telp: 0274555776 Fax: 0274585274<br>
                e-mail: surat@polteklpp.ac.id
            </p>
            <div class="header-divider"></div>
            <p class="header-sub">Sistem Informasi Validasi</p>
        </div>

        @if($valid && $surat)
            <div class="status-banner">
                <div class="status-icon-wrap">
                    <i class="bi bi-patch-check-fill"></i>
                </div>
                <h2>{{ ($surat->unit && $surat->unit->code !== 'akademik') ? $surat->nama_surat : 'Surat Keterangan Aktif' }}</h2>
                <span class="status-chip">
                    <i class="bi bi-shield-check"></i>
                    Tervalidasi
                </span>
            </div>

            <div class="verify-body">
                @if($surat->unit && $surat->unit->code !== 'akademik')
                <div class="info-group">
                    <div class="info-group-title">
                        <i class="bi bi-file-earmark-text ig-green"></i>
                        Informasi Surat
                    </div>
                    <div class="info-rows">
                        <div class="info-row">
                            <span class="label"><i class="bi bi-tag"></i> Nama Surat</span>
                            <span class="value">{{ $surat->nama_surat }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label"><i class="bi bi-hash"></i> Nomor Surat</span>
                            <span class="value">{{ $surat->nomor_surat }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label"><i class="bi bi-diagram-3"></i> Unit</span>
                            <span class="value">{{ $surat->unit->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label"><i class="bi bi-calendar3"></i> Tanggal Surat</span>
                            <span class="value">{{ $surat->tanggal_surat ? strtr($surat->tanggal_surat->translatedFormat('d F Y'), $bulan) : '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label"><i class="bi bi-clock"></i> Tanggal Diajukan</span>
                            <span class="value">{{ $surat->created_at ? strtr($surat->created_at->format('d F Y, H.i').' WIB', $bulan) : '-' }}</span>
                        </div>
                        <div class="info-row" style="border-bottom:none">
                            <span class="label"><i class="bi bi-person-plus"></i> Diajukan Oleh</span>
                            <span class="value">{{ $surat->user ? $surat->user->name : '-' }}</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="info-group">
                    <div class="info-group-title">
                        <i class="bi bi-file-earmark-text ig-green"></i>
                        Informasi Surat
                    </div>
                    <div class="info-rows">
                        <div class="info-row">
                            <span class="label"><i class="bi bi-hash"></i> Nomor Surat</span>
                            <span class="value">{{ $surat->nomor_surat }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label"><i class="bi bi-clock"></i> Tanggal Diajukan</span>
                            <span class="value">{{ $surat->created_at ? strtr($surat->created_at->format('d F Y, H.i').' WIB', $bulan) : '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label"><i class="bi bi-person-plus"></i> Diajukan Oleh</span>
                            <span class="value">{{ $surat->user ? $surat->user->name : '-' }}</span>
                        </div>
                        <div class="info-row" style="border-bottom:none">
                            <span class="label"><i class="bi bi-book"></i> Periode</span>
                            <span class="value">Semester {{ $surat->semester }} — {{ $surat->tahun_ajaran }}</span>
                        </div>
                    </div>
                </div>
                <div class="info-group">
                    <div class="info-group-title">
                        <i class="bi bi-person ig-blue"></i>
                        Data Mahasiswa
                    </div>
                    <div class="info-rows">
                        <div class="info-row">
                            <span class="label"><i class="bi bi-person-badge"></i> Nama</span>
                            <span class="value">{{ $surat->nama_mahasiswa }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label"><i class="bi bi-upc-scan"></i> NIM</span>
                            <span class="value" style="font-family:'SF Mono','Fira Code','Consolas',monospace;letter-spacing:.05em">{{ $surat->nim }}</span>
                        </div>
                        <div class="info-row" style="border-bottom:none">
                            <span class="label"><i class="bi bi-mortarboard"></i> Prodi</span>
                            <span class="value">{{ $surat->prodi }}</span>
                        </div>
                    </div>
                </div>
                @endif
                <div class="info-group" style="margin-bottom:12px">
                    <div class="info-group-title">
                        <i class="bi bi-check2-circle ig-purple"></i>
                        Diverifikasi Oleh
                    </div>
                    <div class="info-rows" style="border-radius:0 0 10px 10px">
                        <div class="info-row">
                            <span class="label"><i class="bi bi-person-check"></i> Nama</span>
                            <span class="value">{{ $surat->validator ? $surat->validator->name : '-' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="label"><i class="bi bi-briefcase"></i> Jabatan</span>
                            <span class="value">{{ $surat->validator ? $surat->validator_jabatan : '-' }}</span>
                        </div>
                        <div class="info-row" style="border-bottom:none">
                            <span class="label"><i class="bi bi-clock-history"></i> Tanggal</span>
                            <span class="value">{{ $surat->validated_at ? strtr($surat->validated_at->format('d F Y, H.i').' WIB', $bulan) : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="invalid-body">
                <div class="invalid-icon-big">
                    <i class="bi bi-shield-x"></i>
                </div>
                <h3>Dokumen Belum Terverifikasi</h3>
                <p>
                    @if($surat)
                        Surat dengan nomor <strong>{{ $surat->nomor_surat }}</strong> saat ini berstatus <strong>"{{ $surat->status_label }}"</strong> dan belum mendapatkan pengesahan resmi.
                    @else
                        Tautan verifikasi ini tidak valid. Pastikan Anda memindai QR Code dari surat yang telah diverifikasi.
                    @endif
                </p>
                @if($surat)
                <div class="invalid-chip">
                    <i class="bi bi-exclamation-triangle"></i>
                    Status: {{ $surat->status_label }}
                </div>
                @endif
            </div>
        @endif

        <div class="verify-footer">
            <p>Bagian Administrasi Akademik dan Beasiswa &copy; {{ date('Y') }} Politeknik LPP Yogyakarta</p>
        </div>
    </div>
</body>
</html>