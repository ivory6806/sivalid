@extends('layouts.app')

@section('title', 'Detail Sertifikat')
@section('pageTitle', 'Detail Sertifikat')

@section('content')
@php
    // Tentukan file mana yang akan ditampilkan
    if ($sertifikat->status === 'disetujui' && $sertifikat->file_disahkan) {
        $previewRelativePath = 'sertifikat_disahkan/' . $sertifikat->file_disahkan;
    } else {
        $previewRelativePath = 'sertifikat/' . $sertifikat->file_asli;
    }

    // DETEKSI BERDASARKAN EKSTENSI FILE YANG AKAN DITAMPILKAN
    $actualExt = strtolower(pathinfo($previewRelativePath, PATHINFO_EXTENSION));
    $isImage = in_array($actualExt, ['png', 'jpg', 'jpeg']);
    
    // URL preview langsung menggunakan Storage
    $previewSrc = Storage::url($previewRelativePath);
        
    // Teks untuk tombol download
    $downloadText = 'Download Gambar';
@endphp

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-custom" style="border:1px solid var(--border);border-radius:16px;overflow:hidden">
            <div style="padding:24px 28px;background:linear-gradient(135deg,#312e81 0%,#7c3aed 100%);color:#fff">
                <div style="font-size:.75rem;opacity:.7;text-transform:uppercase;letter-spacing:.05em">{{ $sertifikat->jenis_sertifikat }}</div>
                <div style="font-size:1.25rem;font-weight:800;margin-top:2px">{{ $sertifikat->nomor_sertifikat }}</div>
                <span class="badge text-bg-{{ $sertifikat->status_color }}" style="margin-top:10px">{{ $sertifikat->status_label }}</span>
            </div>
            <div style="padding:24px 28px">
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if($sertifikat->status === 'direvisi' && $sertifikat->catatan_revisi)
                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:14px 16px;margin-bottom:20px">
                    <div style="font-size:.76rem;font-weight:700;color:#dc2626">CATATAN REVISI</div>
                    <div style="font-size:.86rem;color:#7f1d1d;margin-top:4px">{{ $sertifikat->catatan_revisi }}</div>
                </div>
                @endif

                <div class="row g-3" style="font-size:.88rem">
                    <div class="col-6">
                        <div style="color:#94a3b8;font-size:.75rem">Diajukan Oleh</div>
                        <div style="font-weight:600">{{ $sertifikat->uploader->name ?? '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div style="color:#94a3b8;font-size:.75rem">Tanggal Pengajuan</div>
                        <div style="font-weight:600">{{ $sertifikat->submitted_at?->format('d F Y, H:i') ?? $sertifikat->created_at->format('d F Y, H:i') }}</div>
                    </div>
                    @if($sertifikat->status === 'disetujui')
                    <div class="col-6">
                        <div style="color:#94a3b8;font-size:.75rem">Divalidasi Oleh</div>
                        <div style="font-weight:600">{{ $sertifikat->validator->name ?? '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div style="color:#94a3b8;font-size:.75rem">Tanggal Validasi</div>
                        <div style="font-weight:600">{{ $sertifikat->validated_at?->format('d F Y, H:i') }}</div>
                    </div>
                    @endif
                </div>

                {{-- PREVIEW AREA --}}
                <div style="margin-top:24px;border:1.5px solid var(--border);border-radius:12px;overflow:hidden;background:#f8fafc;">
                    <div style="display:flex;justify-content:center;align-items:center;min-height:400px;padding:20px; background: repeating-conic-gradient(#e2e8f0 0% 25%, transparent 0% 50%) 50% / 20px 20px;">
                        <img 
                            src="{{ $previewSrc }}" 
                            alt="Preview Sertifikat" 
                            style="max-width:100%;max-height:600px;object-fit:contain;border-radius:8px;box-shadow:0 4px 20px rgba(0,0,0,0.08);"
                            onerror="this.style.display='none'; document.getElementById('previewError').style.display='block';"
                        >
                        <div id="previewError" style="display:none;text-align:center;padding:40px 20px;color:#dc2626;">
                            <i class="bi bi-exclamation-triangle" style="font-size:2rem;"></i>
                            <p style="margin-top:10px;color:#64748b;">Gagal memuat preview. File mungkin tidak tersedia.</p>
                            <a href="{{ route('sertifikat.download', $sertifikat) }}" class="btn btn-outline-primary btn-sm mt-2">
                                <i class="bi bi-download"></i> Download File
                            </a>
                        </div>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div style="display:flex;gap:10px;margin-top:20px;flex-wrap:wrap;">
                    <a href="{{ route('sertifikat.download', $sertifikat) }}" class="btn btn-primary">
                        <i class="bi bi-download"></i> {{ $downloadText }}
                    </a>
                    
                    @if($sertifikat->status === 'direvisi' && $sertifikat->uploaded_by === auth()->id())
                        <a href="{{ route('sertifikat.edit', $sertifikat) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Revisi
                        </a>
                    @endif
                    
                    @if($sertifikat->status === 'disetujui')
                        <a href="{{ route('verify.sertifikat', $sertifikat->qr_token) }}" target="_blank" class="btn btn-outline-secondary">
                            <i class="bi bi-qr-code"></i> Lihat Halaman Verifikasi
                        </a>
                    @endif
                    
                    <a href="{{ route('sertifikat.index') }}" class="btn btn-outline-secondary ms-auto">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection