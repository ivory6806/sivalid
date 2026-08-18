@extends('layouts.app')
@section('title', 'Tambah Unit')
@section('pageTitle', 'Tambah Unit Baru')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card-custom" style="overflow:visible;margin-bottom:-10px;position:relative;z-index:2">
            <div style="padding:32px 32px 24px;background:linear-gradient(135deg,var(--primary) 0%,#0d9488 100%);border-radius:14px 14px 0 0;text-align:center;position:relative;overflow:hidden">
                <div style="position:absolute;top:-20px;left:-20px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.08)"></div>
                <div style="position:absolute;bottom:-30px;right:-15px;width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,.06)"></div>
                <div style="position:relative;z-index:1">
                    <div style="width:56px;height:56px;border-radius:14px;background:rgba(255,255,255,.15);backdrop-filter:blur(8px);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;border:1px solid rgba(255,255,255,.2)">
                        <i class="bi bi-diagram-3-fill" style="font-size:1.4rem;color:#fff"></i>
                    </div>
                    <div style="font-size:1.15rem;font-weight:800;color:#fff">Tambah Unit Baru</div>
                    <div style="font-size:.8rem;color:rgba(255,255,255,.7);margin-top:3px">Isi form berikut, lalu tambahkan Kepala & Staff di halaman berikutnya</div>
                </div>
            </div>

            <div style="padding:28px 32px 32px;margin-top:1px;background:var(--card-bg);border-radius:0 0 14px 14px;border:1px solid var(--border);border-top:none">
                <form method="POST" action="{{ route('units.store') }}">
                    @csrf

                    <div style="margin-bottom:20px">
                        <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">
                            Nama Unit <span style="color:#ef4444">*</span>
                        </label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Contoh: Perpustakaan"
                            style="border-radius:10px;padding:12px 16px;border:1.5px solid var(--border);font-size:.9rem;color:var(--text-dark)">
                        @error('name')<div style="font-size:.78rem;color:#ef4444;margin-top:6px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div style="margin-bottom:20px">
                        <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">
                            Kode Unit <span style="font-size:.72rem;color:var(--text-muted);font-weight:400">(opsional, otomatis dari nama jika kosong)</span>
                        </label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="Contoh: perpustakaan"
                            style="border-radius:10px;padding:12px 16px;border:1.5px solid var(--border);font-size:.9rem;color:var(--text-dark)">
                        @error('code')<div style="font-size:.78rem;color:#ef4444;margin-top:6px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div style="margin-bottom:24px">
                        <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">
                            Label Jenis Dokumen <span style="font-size:.72rem;color:var(--text-muted);font-weight:400">(default: "Pengajuan Surat")</span>
                        </label>
                        <input type="text" name="jenis_dokumen_label" class="form-control" value="{{ old('jenis_dokumen_label') }}" placeholder="Pengajuan Surat"
                            style="border-radius:10px;padding:12px 16px;border:1.5px solid var(--border);font-size:.9rem;color:var(--text-dark)">
                        <div style="font-size:.76rem;color:var(--text-muted);margin-top:6px">Label ini muncul di halaman input surat unit ini, misal "Pengajuan Surat" atau nama spesifik lain.</div>
                    </div>

                    <div style="display:flex;gap:10px">
                        <button type="submit" class="btn-primary-custom" style="padding:12px 28px;font-size:.92rem;flex:1">
                            <i class="bi bi-plus-lg"></i> Buat Unit
                        </button>
                        <a href="{{ route('units.index') }}" class="btn-outline-custom" style="padding:12px 24px;font-size:.92rem">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
