@extends('layouts.app')
@section('title', 'Detail Surat')
@section('pageTitle', 'Detail Surat ' . $surat->nomor_surat)
@section('content')

@php
    $statusColor = match($surat->status) {
        'diajukan' => ['bg' => '#fffbeb', 'text' => '#d97706', 'icon' => 'hourglass-split'],
        'disetujui' => ['bg' => '#ecfdf5', 'text' => '#059669', 'icon' => 'patch-check-fill'],
        'direvisi' => ['bg' => '#fef2f2', 'text' => '#dc2626', 'icon' => 'exclamation-circle-fill'],
        default => ['bg' => '#f1f5f9', 'text' => '#64748b', 'icon' => 'question-circle'],
    };
@endphp

<div style="margin-bottom:16px">
    <a href="{{ route('surat.index') }}" style="font-size:.82rem;color:var(--text-muted);text-decoration:none;display:inline-flex;align-items:center;gap:4px">
        <i class="bi bi-arrow-left"></i> Kembali ke daftar surat
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card-custom">
            <div style="padding:24px">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px">
                    <div style="width:52px;height:52px;border-radius:14px;background:{{ $statusColor['bg'] }};color:{{ $statusColor['text'] }};display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0">
                        <i class="bi bi-{{ $statusColor['icon'] }}"></i>
                    </div>
                    <div>
                        <div style="font-size:1.05rem;font-weight:800;color:var(--text-dark)">{{ $surat->nama_surat }}</div>
                        <span style="display:inline-block;margin-top:4px;padding:3px 10px;border-radius:6px;font-size:.7rem;font-weight:700;background:{{ $statusColor['bg'] }};color:{{ $statusColor['text'] }}">{{ $surat->status_label }}</span>
                    </div>
                </div>

                @if($surat->status === 'direvisi' && $surat->catatan_revisi)
                <div style="padding:12px 16px;background:#fef2f2;border-radius:10px;margin-bottom:20px;font-size:.84rem;color:#dc2626">
                    <i class="bi bi-chat-left-text-fill"></i> {{ $surat->catatan_revisi }}
                </div>
                @endif

                <div style="background:#f8fafc;border-radius:12px;padding:4px 0">
                    <div style="display:flex;justify-content:space-between;padding:10px 16px;font-size:.85rem"><span style="color:var(--text-muted)">Nomor Surat</span><span style="font-weight:600">{{ $surat->nomor_surat }}</span></div>
                    <div style="display:flex;justify-content:space-between;padding:10px 16px;font-size:.85rem;border-top:1px solid var(--border)"><span style="color:var(--text-muted)">Tanggal Surat</span><span style="font-weight:600">{{ $surat->tanggal_surat?->translatedFormat('d F Y') ?? '-' }}</span></div>
                    @if($surat->unit)
                    <div style="display:flex;justify-content:space-between;padding:10px 16px;font-size:.85rem;border-top:1px solid var(--border)"><span style="color:var(--text-muted)">Unit</span><span style="font-weight:600">{{ $surat->unit->name }}</span></div>
                    @endif
                    <div style="display:flex;justify-content:space-between;padding:10px 16px;font-size:.85rem;border-top:1px solid var(--border)"><span style="color:var(--text-muted)">Diajukan Oleh</span><span style="font-weight:600">{{ $surat->user->name }}</span></div>
                    <div style="display:flex;justify-content:space-between;padding:10px 16px;font-size:.85rem;border-top:1px solid var(--border)"><span style="color:var(--text-muted)">Tanggal Diajukan</span><span style="font-weight:600">{{ $surat->created_at->translatedFormat('d F Y, H:i') }}</span></div>
                    @if($surat->status === 'disetujui')
                    <div style="display:flex;justify-content:space-between;padding:10px 16px;font-size:.85rem;border-top:1px solid var(--border)"><span style="color:var(--text-muted)">Divalidasi Oleh</span><span style="font-weight:600">{{ $surat->validator->name ?? '-' }}{{ $surat->validator_jabatan ? ' — ' . $surat->validator_jabatan : '' }}</span></div>
                    <div style="display:flex;justify-content:space-between;padding:10px 16px;font-size:.85rem;border-top:1px solid var(--border)"><span style="color:var(--text-muted)">Tanggal Validasi</span><span style="font-weight:600">{{ $surat->validated_at?->translatedFormat('d F Y, H:i') ?? '-' }}</span></div>
                    @endif
                </div>

                <div style="display:flex;gap:8px;margin-top:20px">
                    <a href="{{ route('surat.download', $surat) }}" class="btn-primary-custom" style="flex:1;text-align:center;padding:11px" target="_blank">
                        <i class="bi bi-download"></i> Download PDF
                    </a>
                    @if($surat->status === 'direvisi' && $surat->user_id === auth()->id())
                    <a href="{{ route('surat.edit', $surat) }}" class="btn-outline-custom" style="padding:11px 20px">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card-custom" style="height:100%">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border);font-size:.86rem;font-weight:700;color:var(--text-dark)">
                <i class="bi bi-file-earmark-pdf-fill" style="color:#ef4444"></i> Preview Dokumen
            </div>
            <div style="height:500px">
                <iframe src="{{ route('surat.preview', $surat) }}" style="width:100%;height:100%;border:none;border-radius:0 0 14px 14px"></iframe>
            </div>
        </div>
    </div>
</div>

@endsection
