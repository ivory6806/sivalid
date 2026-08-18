@extends('layouts.app')
@section('title', 'Validasi Sertifikat')
@section('pageTitle', 'Validasi Sertifikat')
@section('content')
<div style="background:linear-gradient(135deg,#1e1b4b 0%,#312e81 40%,#4338ca 100%);border-radius:20px;padding:1.75rem 2rem;color:#fff;margin-bottom:1.5rem">
    <h2 style="font-weight:700;font-size:1.35rem;margin-bottom:.25rem"><i class="bi bi-qr-code-scan"></i> Validasi Sertifikat</h2>
    <p style="font-size:.85rem;opacity:.7;margin:0">Tinjau dokumen, atur posisi QR Code, lalu generate sertifikat resmi</p>
    <div style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.1);padding:.4rem .9rem;border-radius:50px;font-size:.78rem;margin-top:.65rem">
        <span style="width:8px;height:8px;border-radius:50%;background:#a5b4fc"></span> {{ $sertifikats->total() }} menunggu validasi
    </div>
</div>

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))<div class="alert alert-danger" style="white-space:pre-line">{{ session('error') }}</div>@endif

<form method="GET" style="margin-bottom:1rem;max-width:400px;position:relative">
    <i class="bi bi-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor / jenis sertifikat..."
        style="width:100%;padding:.65rem 1rem .65rem 2.5rem;border-radius:12px;border:1.5px solid #e2e8f0;font-size:.88rem">
</form>

<div class="row g-3">
    @forelse($sertifikats as $s)
    <div class="col-md-6">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:20px;height:100%;display:flex;flex-direction:column">
            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:10px">
                <div>
                    <div style="font-size:.72rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.04em">{{ $s->jenis_sertifikat }}</div>
                    <div style="font-weight:700;font-size:.98rem;color:#0f172a">{{ $s->nomor_sertifikat }}</div>
                </div>
                <span class="badge text-bg-{{ $s->status_color }}">{{ $s->status_label }}</span>
            </div>
            <div style="font-size:.8rem;color:#64748b;margin-bottom:6px"><i class="bi bi-person"></i> {{ $s->uploader->name ?? '-' }}</div>
            <div style="font-size:.8rem;color:#64748b;margin-bottom:16px"><i class="bi bi-files"></i> {{ $s->jumlah_halaman ?? '-' }} halaman &middot; {{ $s->created_at->diffForHumans() }}</div>

            <div style="margin-top:auto;display:flex;gap:8px">
                <a href="{{ route('validasi-sertifikat.adjust', $s) }}" class="btn btn-primary btn-sm flex-fill">
                    <i class="bi bi-qr-code"></i> Tinjau & Atur QR
                </a>
                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#reviseModal{{ $s->id }}">
                    <i class="bi bi-arrow-return-left"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Revisi -->
    <div class="modal fade" id="reviseModal{{ $s->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('validasi-sertifikat.revise', $s) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Kembalikan untuk Revisi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label" style="font-size:.85rem">Catatan revisi untuk {{ $s->nomor_sertifikat }}</label>
                        <textarea name="catatan_revisi" class="form-control" rows="3" required placeholder="Jelaskan apa yang perlu diperbaiki..."></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Kirim Revisi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div style="text-align:center;padding:60px 20px;color:#94a3b8">
            <i class="bi bi-check2-circle" style="font-size:2.5rem"></i>
            <div style="margin-top:10px">Tidak ada sertifikat yang menunggu validasi.</div>
        </div>
    </div>
    @endforelse
</div>

<div style="margin-top:16px">{{ $sertifikats->links() }}</div>
@endsection
