@extends('layouts.app')
@section('title', 'Kelola Unit')
@section('pageTitle', 'Kelola Unit')
@section('content')

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px">
    <div>
        <div style="font-size:1.1rem;font-weight:700;color:var(--text-dark)">{{ $units->count() }} Unit Terdaftar</div>
        <div style="font-size:.82rem;color:var(--text-muted);margin-top:2px">Kelola unit, Kepala, dan Staff yang menjabat di dalamnya</div>
    </div>
    <a href="{{ route('units.create') }}" class="btn-primary-custom">
        <i class="bi bi-plus-lg"></i> Tambah Unit
    </a>
</div>

@forelse($units as $unit)
<div class="card-custom mb-3">
    <div style="padding:18px 22px;display:flex;align-items:center;gap:16px;flex-wrap:wrap">
        <div style="width:50px;height:50px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;flex-shrink:0;
            {{ $unit->is_active ? 'background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#3b82f6' : 'background:#f1f5f9;color:#94a3b8' }}">
            <i class="bi bi-diagram-3-fill"></i>
        </div>

        <div style="flex:1;min-width:200px">
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                <span style="font-size:.95rem;font-weight:700;color:var(--text-dark)">{{ $unit->name }}</span>
                @if($unit->code === 'akademik')
                    <span style="padding:2px 8px;border-radius:6px;background:#eff6ff;color:#3b82f6;font-size:.65rem;font-weight:600">DEFAULT</span>
                @endif
                @if($unit->is_active)
                    <span style="padding:2px 8px;border-radius:6px;background:#ecfdf5;color:#059669;font-size:.65rem;font-weight:600">AKTIF</span>
                @else
                    <span style="padding:2px 8px;border-radius:6px;background:#f1f5f9;color:#94a3b8;font-size:.65rem;font-weight:600">NONAKTIF</span>
                @endif
            </div>
            <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px">Kode: {{ $unit->code }} &middot; Jenis dokumen: {{ $unit->jenis_dokumen_label }}</div>
        </div>

        <div style="display:flex;gap:18px;flex-shrink:0">
            <div style="text-align:center">
                <div style="font-size:1rem;font-weight:800;color:#059669">{{ $unit->kepala_count }}</div>
                <div style="font-size:.68rem;color:var(--text-muted);font-weight:600">Kepala</div>
            </div>
            <div style="text-align:center">
                <div style="font-size:1rem;font-weight:800;color:#3b82f6">{{ $unit->staff_count }}</div>
                <div style="font-size:.68rem;color:var(--text-muted);font-weight:600">Staff</div>
            </div>
        </div>

        <div style="display:flex;gap:6px;flex-shrink:0">
            <a href="{{ route('units.edit', $unit) }}" class="btn-outline-custom" style="padding:8px 14px">
                <i class="bi bi-gear"></i> Kelola
            </a>
        </div>
    </div>
</div>
@empty
<div class="card-custom">
    <div style="padding:60px 22px;text-align:center">
        <div style="width:64px;height:64px;border-radius:16px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
            <i class="bi bi-diagram-3" style="font-size:1.6rem;color:#cbd5e1"></i>
        </div>
        <div style="font-size:.92rem;font-weight:600;color:var(--text-muted)">Belum ada unit</div>
        <div style="font-size:.82rem;color:#94a3b8;margin-top:4px">Tambahkan unit pertama untuk mulai mengelola Kepala dan Staff-nya.</div>
        <a href="{{ route('units.create') }}" class="btn-primary-custom" style="margin-top:16px;display:inline-flex">
            <i class="bi bi-plus-lg"></i> Tambah Unit
        </a>
    </div>
</div>
@endforelse

@endsection
