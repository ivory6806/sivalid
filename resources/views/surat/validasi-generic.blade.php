@extends('layouts.app')
@section('title', 'Validasi Surat')
@section('pageTitle', 'Validasi Surat')
@section('content')

<div style="margin-bottom:20px">
    <form method="GET" action="{{ route('validasi.index') }}" style="display:flex;gap:10px">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama surat / nomor surat..."
            class="form-control" style="border-radius:10px;padding:10px 16px;border:1.5px solid var(--border);font-size:.86rem;max-width:340px">
        <button type="submit" class="btn-outline-custom" style="padding:10px 18px"><i class="bi bi-search"></i></button>
    </form>
</div>

@forelse($surats as $s)
<div class="card-custom mb-3">
    <div style="padding:18px 22px;display:flex;align-items:center;gap:16px;flex-wrap:wrap">
        <div style="width:46px;height:46px;border-radius:12px;background:#fef2f2;color:#ef4444;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <i class="bi bi-file-earmark-pdf-fill"></i>
        </div>

        <div style="flex:1;min-width:220px">
            <div style="font-size:.92rem;font-weight:700;color:var(--text-dark)">{{ $s->nama_surat }}</div>
            <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px">
                No. {{ $s->nomor_surat }} &middot; {{ $s->tanggal_surat?->translatedFormat('d F Y') }}
                @if(Auth::user()->isSuperadmin() && $s->unit) &middot; {{ $s->unit->name }} @endif
                &middot; Diajukan oleh {{ $s->user->name }}
            </div>
            @if($s->status === 'direvisi' && $s->catatan_revisi)
            <div style="font-size:.76rem;color:#dc2626;margin-top:6px;background:#fef2f2;padding:6px 10px;border-radius:8px;display:inline-flex;gap:6px;align-items:center">
                <i class="bi bi-chat-left-text-fill"></i> {{ $s->catatan_revisi }}
            </div>
            @endif
        </div>

        @php
            $statusColor = match($s->status) {
                'diajukan' => ['bg' => '#fffbeb', 'text' => '#d97706'],
                'disetujui' => ['bg' => '#ecfdf5', 'text' => '#059669'],
                'direvisi' => ['bg' => '#fef2f2', 'text' => '#dc2626'],
                default => ['bg' => '#f1f5f9', 'text' => '#64748b'],
            };
        @endphp
        <span style="flex-shrink:0;padding:4px 12px;border-radius:8px;font-size:.72rem;font-weight:700;background:{{ $statusColor['bg'] }};color:{{ $statusColor['text'] }}">{{ $s->status_label }}</span>

        <div style="display:flex;gap:6px;flex-shrink:0">
            <a href="{{ route('surat.preview', $s) }}" target="_blank" class="btn-outline-custom" style="padding:8px 12px" title="Lihat PDF">
                <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('validasi.atur-qr', $s) }}" class="btn-outline-custom" style="padding:8px 14px;border-color:#bbf7d0;color:#059669" title="Setujui & Atur QR">
                <i class="bi bi-check-lg"></i> Setujui
            </a>
            <button type="button" class="btn-outline-custom" style="padding:8px 12px;border-color:#fecaca;color:#ef4444" data-bs-toggle="modal" data-bs-target="#reviseModal{{ $s->id }}" title="Tolak">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="reviseModal{{ $s->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;border:none">
            <div class="modal-body" style="padding:24px">
                <div style="font-size:1rem;font-weight:700;color:var(--text-dark);margin-bottom:12px">Kembalikan untuk Revisi</div>
                <form method="POST" action="{{ route('validasi.revise', $s) }}">
                    @csrf
                    <textarea name="catatan_revisi" required rows="3" class="form-control" placeholder="Tulis catatan revisi..."
                        style="border-radius:10px;padding:10px 14px;border:1.5px solid var(--border);font-size:.86rem;margin-bottom:14px"></textarea>
                    <div style="display:flex;gap:8px">
                        <button type="submit" class="btn-primary-custom" style="flex:1;padding:10px">Kirim</button>
                        <button type="button" class="btn-outline-custom" style="padding:10px 18px" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@empty
<div class="card-custom">
    <div style="padding:60px 22px;text-align:center">
        <i class="bi bi-inbox" style="font-size:2rem;color:#cbd5e1"></i>
        <div style="font-size:.9rem;color:var(--text-muted);margin-top:10px">Tidak ada surat yang menunggu validasi.</div>
    </div>
</div>
@endforelse

@if($surats->hasPages())
<div style="margin-top:16px">{{ $surats->appends(request()->query())->links() }}</div>
@endif

@endsection
