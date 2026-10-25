<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat - {{ config('site.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset(config('site.favicon')) }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 24px; background: #0f172a; position: relative;
        }
        body::before {
            content: ''; position: absolute; top: -40%; width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(124,58,237,.14) 0%, transparent 70%); pointer-events: none;
        }
        .verify-card {
            width: 100%; max-width: 520px; background: #fff; border-radius: 16px;
            box-shadow: 0 0 0 1px rgba(255,255,255,.05), 0 25px 60px rgba(0,0,0,.35), 0 0 80px rgba(0,0,0,.15);
            position: relative; z-index: 1; overflow: hidden;
        }
        .verify-header {
            background: linear-gradient(135deg, #312e81 0%, #6d28d9 40%, #a78bfa 100%);
            padding: 28px 32px 24px; text-align: center; position: relative; border-bottom: 4px solid #eab308;
        }
        .verify-header > * { position: relative; z-index: 1; }
        .header-logo { width: 72px; height: 72px; margin: 0 auto 14px; object-fit: contain; filter: drop-shadow(0 4px 12px rgba(0,0,0,.25)); }
        .verify-header h1 { font-size: 1.25rem; font-weight: 800; color: #fff; letter-spacing: .08em; text-transform: uppercase; }
        .header-address { font-size: .7rem; color: rgba(255,255,255,.75); margin-top: 8px; line-height: 1.7; }
        .header-divider { width: 75%; height: 1px; background: rgba(255,255,255,.2); margin: 12px auto; }
        .header-sub { font-size: .8rem; font-weight: 600; color: rgba(255,255,255,.85); letter-spacing: .1em; text-transform: uppercase; }

        .status-banner { display: flex; flex-direction: column; align-items: center; padding: 24px 32px 20px; }
        .status-icon-wrap {
            width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            margin-bottom: 10px; border: 3px solid #ddd6fe; background: #f5f3ff; box-shadow: 0 2px 12px rgba(124,58,237,.08);
        }
        .status-icon-wrap i { font-size: 1.6rem; color: #7c3aed; }
        .status-banner h2 { font-size: 1.4rem; font-weight: 800; color: #0f172a; }
        .status-chip {
            display: inline-flex; align-items: center; gap: 5px; margin-top: 8px; padding: 4px 14px; border-radius: 50px;
            font-size: .68rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; background: #f5f3ff; color: #7c3aed;
        }

        .invalid-body { padding: 36px 32px; text-align: center; }
        .invalid-icon-big {
            width: 88px; height: 88px; border-radius: 50%; background: linear-gradient(135deg, #fef2f2, #fee2e2);
            display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;
        }
        .invalid-icon-big i { font-size: 2.2rem; color: #fca5a5; }
        .invalid-body h3 { font-size: 1.05rem; font-weight: 700; color: #1e293b; margin-bottom: 8px; }
        .invalid-body p { color: #64748b; font-size: .85rem; line-height: 1.7; max-width: 380px; margin: 0 auto; }
        .invalid-chip {
            display: inline-flex; align-items: center; gap: 6px; margin-top: 16px; padding: 6px 16px; border-radius: 50px;
            font-size: .75rem; font-weight: 700; background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;
        }

        .verify-body { padding: 0 32px 8px; }
        .info-group { margin-bottom: 14px; }
        .info-group-title {
            font-size: .65rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8;
            margin-bottom: 8px; display: flex; align-items: center; gap: 8px;
        }
        .info-group-title::after { content: ''; flex: 1; height: 1px; background: #f1f5f9; }
        .info-group-title i { font-size: .65rem; width: 20px; height: 20px; border-radius: 5px; display: flex; align-items: center; justify-content: center; }
        .ig-purple { background: #f5f3ff; color: #7c3aed; }
        .ig-green { background: #ecfdf5; color: #059669; }

        .info-rows { background: #f8fafc; border-radius: 10px; padding: 2px 0; }
        .info-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 14px; font-size: .85rem; }
        .info-row + .info-row { border-top: 1px solid #e2e8f0; }
        .info-row .label { color: #64748b; font-size: .8rem; display: flex; align-items: center; gap: 6px; }
        .info-row .value { font-weight: 600; color: #0f172a; text-align: right; font-size: .83rem; }

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
            .verify-body { padding: 0 20px 4px; }
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

        @if($valid && $sertifikat)
            <div class="status-banner">
                <div class="status-icon-wrap"><i class="bi bi-patch-check-fill"></i></div>
                <h2>Sertifikat Asli</h2>
                <span class="status-chip"><i class="bi bi-shield-check"></i> Tervalidasi</span>
            </div>

            <div class="verify-body">
                <div class="info-group">
                    <div class="info-group-title"><i class="bi bi-award ig-purple"></i> Informasi Sertifikat</div>
                    <div class="info-rows">
                        <div class="info-row"><span class="label"><i class="bi bi-bookmark"></i> Jenis Sertifikat</span><span class="value">{{ $sertifikat->jenis_sertifikat }}</span></div>
                        <div class="info-row"><span class="label"><i class="bi bi-hash"></i> Nomor Sertifikat</span><span class="value">{{ $sertifikat->nomor_sertifikat }}</span></div>
                        <!-- <div class="info-row"><span class="label"><i class="bi bi-files"></i> Jumlah Halaman</span><span class="value">{{ $sertifikat->jumlah_halaman ?? '-' }} halaman</span></div> -->
                    </div>
                </div>
                <div class="info-group" style="margin-bottom:12px">
                    <div class="info-group-title"><i class="bi bi-check2-circle ig-green"></i> Validasi</div>
                    <div class="info-rows">
                        <div class="info-row"><span class="label"><i class="bi bi-calendar-check"></i> Divalidasi Tanggal</span><span class="value">{{ $sertifikat->validated_at ? strtr($sertifikat->validated_at->format('d F Y, H:i '). 'WIB', $bulan) : '-' }}</span></div>
                        <div class="info-row" style="border-bottom:none"><span class="label"><i class="bi bi-person-badge"></i> Divalidasi Oleh</span><span class="value">{{ $sertifikat->validator->name ?? '-' }}</span></div>
                    </div>
                </div>
            </div>

            <div class="verify-footer">
                <!-- <p>Dokumen ini telah diverifikasi keasliannya oleh sistem SIAKTIF Politeknik LPP Yogyakarta.<br>QR Code ini hanya berlaku untuk dokumen dengan nomor sertifikat yang tertera di atas.</p> -->
                 <p>Bagian Administrasi Akademik dan Beasiswa &copy; {{ date('Y') }} Politeknik LPP Yogyakarta</p>
            </div>
        @else
            <div class="invalid-body">
                <div class="invalid-icon-big"><i class="bi bi-x-circle"></i></div>
                @if(!$sertifikat)
                    <h3>QR Code Tidak Dikenali</h3>
                    <p>Kode yang Anda pindai tidak terdaftar dalam sistem kami. Pastikan Anda memindai QR Code resmi dari dokumen sertifikat.</p>
                @else
                    <h3>Sertifikat Belum / Tidak Valid</h3>
                    <p>Sertifikat <strong>{{ $sertifikat->nomor_sertifikat }}</strong> ({{ $sertifikat->jenis_sertifikat }}) saat ini berstatus <strong>{{ $sertifikat->status_label }}</strong> dan belum tervalidasi secara resmi.</p>
                @endif
                <div class="invalid-chip"><i class="bi bi-exclamation-triangle-fill"></i> Tidak Terverifikasi</div>
            </div>
            <div class="verify-footer">
                <p>Bagian Administrasi Akademik dan Beasiswa &copy; {{ date('Y') }} Politeknik LPP Yogyakarta</p>
            </div>
        @endif
    </div>
</body>
</html>
