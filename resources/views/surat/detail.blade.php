@extends('layouts.app')

@section('title', 'Detail Surat')
@section('pageTitle', 'Detail Surat ' . $surat->nomor_surat)

@section('content')
<style>
    .detail-status-hero {
        border-radius: 20px;
        padding: 2rem 2rem 1.75rem;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
        border: none;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 6px 16px rgba(0,0,0,0.04);
    }
    .detail-status-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 20px;
        padding: 3px;
        background: linear-gradient(135deg, {{ $surat->status === 'disetujui' ? '#059669, #34d399' : ($surat->status === 'direvisi' ? '#dc2626, #f87171' : '#d97706, #fbbf24') }});
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
    }
    .detail-status-hero .hero-bg-icon {
        position: absolute;
        right: -10px;
        top: 50%;
        transform: translateY(-50%) rotate(-12deg);
        font-size: 7rem;
        opacity: .04;
        z-index: 0;
    }
    .hero-content {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        position: relative;
        z-index: 1;
    }
    .hero-icon-box {
        width: 60px;
        height: 60px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
        background: {{ $surat->status === 'disetujui' ? 'linear-gradient(135deg, #ecfdf5, #d1fae5)' : ($surat->status === 'direvisi' ? 'linear-gradient(135deg, #fef2f2, #fee2e2)' : 'linear-gradient(135deg, #fffbeb, #fef3c7)') }};
        color: {{ $surat->status === 'disetujui' ? '#059669' : ($surat->status === 'direvisi' ? '#dc2626' : '#d97706') }};
        box-shadow: 0 4px 12px {{ $surat->status === 'disetujui' ? 'rgba(5,150,105,0.15)' : ($surat->status === 'direvisi' ? 'rgba(220,38,38,0.15)' : 'rgba(217,119,6,0.15)') }};
    }
    .hero-label {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: .3rem;
        display: flex;
        align-items: center;
        gap: .5rem;
    }
    .hero-label .status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: {{ $surat->status === 'disetujui' ? '#059669' : ($surat->status === 'direvisi' ? '#dc2626' : '#d97706') }};
        @if($surat->status !== 'disetujui' && $surat->status !== 'direvisi')
        animation: pulse-dot 1.5s ease-in-out infinite;
        @endif
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .4; transform: scale(1.4); }
    }
    .hero-meta {
        font-size: .82rem;
        color: #64748b;
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        align-items: center;
    }
    .hero-meta .meta-sep {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: #cbd5e1;
    }
    .hero-meta i {
        font-size: .72rem;
        opacity: .6;
        margin-right: .2rem;
    }

    .info-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 4px 12px rgba(0,0,0,0.02);
        margin-bottom: 1rem;
        overflow: hidden;
        transition: box-shadow .2s ease;
    }
    .info-card:hover {
        box-shadow: 0 2px 6px rgba(0,0,0,0.04), 0 8px 20px rgba(0,0,0,0.04);
    }
    .info-card-header {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: 1rem 1.5rem;
        font-size: .82rem;
        font-weight: 700;
        color: #334155;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    .info-card-header .header-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .9rem;
    }
    .icon-green { background: #ecfdf5; color: #059669; }
    .icon-blue { background: #eff6ff; color: #2563eb; }
    .icon-amber { background: #fffbeb; color: #d97706; }
    .icon-red { background: #fef2f2; color: #dc2626; }

    .info-card-body {
        padding: 1.25rem 1.5rem;
    }

    .detail-row {
        display: flex;
        padding: .65rem 0;
        border-bottom: 1px dashed #f1f5f9;
        font-size: .88rem;
        gap: 1rem;
        align-items: flex-start;
    }
    .detail-row:last-child { border-bottom: none; }
    .detail-row .label {
        width: 170px;
        flex-shrink: 0;
        color: #94a3b8;
        font-size: .8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        padding-top: 2px;
    }
    .detail-row .value {
        color: #0f172a;
        font-weight: 600;
        flex: 1;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0;
    }
    .detail-grid .detail-cell {
        padding: .7rem 0;
        border-bottom: 1px dashed #f1f5f9;
    }
    .detail-grid .detail-cell .cell-label {
        font-size: .72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .05em;
        color: #94a3b8;
        margin-bottom: .2rem;
    }
    .detail-grid .detail-cell .cell-value {
        font-size: .88rem;
        font-weight: 600;
        color: #0f172a;
    }
    .detail-grid .detail-cell:nth-child(odd) { padding-right: 1rem; }
    .detail-grid .detail-cell:nth-child(even) { padding-left: 1rem; border-left: 1px solid #f8fafc; }
    .detail-grid .detail-cell:nth-last-child(-n+2) { border-bottom: none; }
    .detail-grid .detail-cell.full-width {
        grid-column: 1 / -1;
        padding-left: 0;
        border-left: none;
    }

    .revisi-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03), 0 4px 12px rgba(0,0,0,0.02);
        border: 1px solid #fecaca;
    }
    .revisi-card .info-card-header {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        color: #dc2626;
        border-bottom-color: #fecaca;
    }
    .revisi-card .info-card-body {
        background: #fffbfb;
        color: #7f1d1d;
        font-size: .88rem;
        line-height: 1.7;
        position: relative;
        padding-left: 3rem;
    }
    .revisi-card .info-card-body::before {
        content: '\201C';
        position: absolute;
        left: 1.25rem;
        top: .5rem;
        font-size: 2.5rem;
        color: #fca5a5;
        font-family: Georgia, serif;
        line-height: 1;
    }

    .action-bar {
        display: flex;
        gap: .75rem;
        margin-top: 1.5rem;
    }
    .btn-modern {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        padding: .75rem 1.5rem;
        border-radius: 12px;
        font-size: .85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .25s ease;
        border: none;
        cursor: pointer;
    }
    .btn-primary-modern {
        background: linear-gradient(135deg, #059669, #10b981);
        color: #fff;
        box-shadow: 0 4px 14px rgba(5,150,105,0.3);
    }
    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(5,150,105,0.4);
        color: #fff;
    }
    .btn-secondary-modern {
        background: #fff;
        color: #475569;
        border: 1px solid #e2e8f0;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .btn-secondary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        color: #0f172a;
        border-color: #cbd5e1;
    }

    .nomor-highlight {
        font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace;
        font-size: .82rem;
        font-weight: 700;
        background: #f1f5f9;
        padding: .25rem .7rem;
        border-radius: 8px;
        color: #334155;
        letter-spacing: .02em;
    }

    .validator-badge {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        padding: .35rem .85rem;
        border-radius: 10px;
        font-size: .82rem;
        color: #1e40af;
        font-weight: 600;
    }
    .validator-badge i {
        font-size: .75rem;
        opacity: .7;
    }

    @media (max-width: 768px) {
        .detail-grid { grid-template-columns: 1fr; }
        .detail-grid .detail-cell:nth-child(even) { padding-left: 0; border-left: none; }
        .detail-grid .detail-cell:nth-child(odd) { padding-right: 0; }
        .detail-row { flex-direction: column; gap: .2rem; }
        .detail-row .label { width: auto; }
        .hero-content { flex-direction: column; text-align: center; gap: .75rem; }
        .hero-meta { justify-content: center; }
        .action-bar { flex-direction: column; }
        .action-bar .btn-modern { justify-content: center; }
    }
</style>

<div class="row justify-content-center">
    <div class="col-lg-8">

        {{-- Status Hero --}}
        <div class="detail-status-hero">
            <div class="hero-content">
                <div class="hero-icon-box">
                    @if($surat->status === 'disetujui')<i class="bi bi-check-circle-fill"></i>
                    @elseif($surat->status === 'direvisi')<i class="bi bi-arrow-counterclockwise"></i>
                    @else<i class="bi bi-hourglass-split"></i>@endif
                </div>
                <div>
                    <div class="hero-label">
                        <span class="status-dot"></span>
                        {{ $surat->status_label }}
                    </div>
                    <div class="hero-meta">
                        <span><i class="bi bi-calendar3"></i>{{ $surat->created_at->format('d F Y, H:i') }}</span>
                        @if($surat->validated_at)
                            <span class="meta-sep"></span>
                            <span><i class="bi bi-patch-check"></i>Diverifikasi: {{ $surat->validated_at->format('d F Y, H:i') }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <i class="bi bi-file-earmark-check hero-bg-icon"></i>
        </div>

        {{-- Informasi Surat --}}
        <div class="info-card">
            <div class="info-card-header">
                <div class="header-icon icon-green"><i class="bi bi-info-circle"></i></div>
                Informasi Surat
            </div>
            <div class="info-card-body">
                <div class="detail-row">
                    <span class="label">Nomor Surat</span>
                    <span class="value"><span class="nomor-highlight">{{ $surat->nomor_surat }}</span></span>
                </div>
                <div class="detail-row">
                    <span class="label">Diajukan Oleh</span>
                    <span class="value">{{ $surat->user->name }}</span>
                </div>
                @if($surat->validator)
                <div class="detail-row">
                    <span class="label">Diverifikasi Oleh</span>
                    <span class="value">
                        <span class="validator-badge">
                            <i class="bi bi-shield-check"></i>
                            {{ $surat->validator->name }} — {{ $surat->validator_jabatan }}
                        </span>
                    </span>
                </div>
                @endif
            </div>
        </div>

        {{-- Data Mahasiswa --}}
        <div class="info-card">
            <div class="info-card-header">
                <div class="header-icon icon-blue"><i class="bi bi-person"></i></div>
                Data Mahasiswa
            </div>
            <div class="info-card-body">
                <div class="detail-grid">
                    <div class="detail-cell">
                        <div class="cell-label">Nama</div>
                        <div class="cell-value">{{ $surat->nama_mahasiswa }}</div>
                    </div>
                    <div class="detail-cell">
                        <div class="cell-label">NIM</div>
                        <div class="cell-value" style="font-family:'SF Mono','Fira Code','Consolas',monospace;letter-spacing:.03em">{{ $surat->nim }}</div>
                    </div>
                    <div class="detail-cell">
                        <div class="cell-label">Program Studi</div>
                        <div class="cell-value">{{ $surat->prodi }}</div>
                    </div>
                    <div class="detail-cell">
                        <div class="cell-label">Semester</div>
                        <div class="cell-value">{{ $surat->semester }} — {{ $surat->tahun_ajaran }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Orang Tua --}}
        <!-- <div class="info-card">
            <div class="info-card-header">
                <div class="header-icon icon-amber"><i class="bi bi-house"></i></div>
                Data Orang Tua
            </div>
            <div class="info-card-body">
                <div class="detail-grid">
                    <div class="detail-cell">
                        <div class="cell-label">Nama Ayah</div>
                        <div class="cell-value">{{ $surat->nama_ayah }}</div>
                    </div>
                    <div class="detail-cell">
                        <div class="cell-label">Nama Ibu</div>
                        <div class="cell-value">{{ $surat->nama_ibu }}</div>
                    </div>
                    <div class="detail-cell">
                        <div class="cell-label">Pekerjaan</div>
                        <div class="cell-value">{{ $surat->pekerjaan }}</div>
                    </div>
                    <div class="detail-cell">
                        <div class="cell-label">Alamat</div>
                        <div class="cell-value">{{ $surat->alamat_orangtua }}</div>
                    </div>
                </div>
            </div>
        </div> -->

        {{-- Catatan Revisi --}}
        @if($surat->catatan_revisi)
        <div class="revisi-card">
            <div class="info-card-header">
                <div class="header-icon icon-red"><i class="bi bi-chat-left-text"></i></div>
                Catatan Revisi
            </div>
            <div class="info-card-body">
                {{ $surat->catatan_revisi }}
            </div>
        </div>
        @endif

        {{-- Actions --}}
        <div class="action-bar">
            <a href="{{ route('surat.download', $surat) }}" class="btn-modern btn-primary-modern">
                <i class="bi bi-download"></i> Download PDF
            </a>
            <a href="{{ route('surat.index') }}" class="btn-modern btn-secondary-modern">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>

    </div>
</div>
@endsection