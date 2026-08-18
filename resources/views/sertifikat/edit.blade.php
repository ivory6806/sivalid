@extends('layouts.app')

@section('title', 'Revisi Sertifikat')
@section('pageTitle', 'Revisi & Ajukan Ulang Sertifikat')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        @if($sertifikat->catatan_revisi)
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:16px 20px;margin-bottom:20px">
            <div style="font-size:.78rem;font-weight:700;color:#dc2626;margin-bottom:4px"><i class="bi bi-exclamation-triangle-fill"></i> CATATAN REVISI DARI KEPALA</div>
            <div style="font-size:.88rem;color:#7f1d1d">{{ $sertifikat->catatan_revisi }}</div>
        </div>
        @endif

        <div class="card-custom" style="overflow:visible;position:relative;z-index:2">
            <div style="padding:24px 32px;background:linear-gradient(135deg,#7c3aed 0%,#4f46e5 100%);border-radius:14px 14px 0 0;text-align:center">
                <div style="font-size:1.1rem;font-weight:800;color:#fff"><i class="bi bi-arrow-repeat"></i> Revisi Sertifikat</div>
            </div>
            <div style="padding:28px 32px 32px;background:var(--card-bg);border-radius:0 0 14px 14px;border:1px solid var(--border);border-top:none">
                <form method="POST" action="{{ route('sertifikat.update', $sertifikat) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    <div class="row g-3" style="margin-bottom:24px">
                        <div class="col-md-6">
                            <label style="font-size:.8rem;font-weight:600;margin-bottom:8px;display:block">Jenis Sertifikat <span style="color:#ef4444">*</span></label>
                            <input type="text" name="jenis_sertifikat" class="form-control" value="{{ old('jenis_sertifikat', $sertifikat->jenis_sertifikat) }}" required
                                style="border-radius:10px;padding:12px 16px;border:1.5px solid var(--border);font-size:.88rem">
                            @error('jenis_sertifikat')<div style="font-size:.76rem;color:#ef4444;margin-top:6px">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label style="font-size:.8rem;font-weight:600;margin-bottom:8px;display:block">Nomor Sertifikat <span style="color:#ef4444">*</span></label>
                            <input type="text" name="nomor_sertifikat" class="form-control" value="{{ old('nomor_sertifikat', $sertifikat->nomor_sertifikat) }}" required
                                style="border-radius:10px;padding:12px 16px;border:1.5px solid var(--border);font-size:.88rem">
                            @error('nomor_sertifikat')<div style="font-size:.76rem;color:#ef4444;margin-top:6px">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="margin-bottom:24px">
                        <label style="font-size:.8rem;font-weight:600;margin-bottom:8px;display:block">Ganti File Gambar (opsional)</label>
                        <input type="file" name="file_sertifikat" class="form-control" accept=".png, .jpg, .jpeg, image/png, image/jpeg"
                            style="border-radius:10px;padding:10px 16px;border:1.5px solid var(--border);font-size:.86rem">
                        <div style="font-size:.74rem;color:var(--text-muted);margin-top:6px">Kosongkan jika ingin memakai file gambar yang sudah ada. Format yang didukung: PNG, JPG, JPEG. Maks. 10MB.</div>
                        @error('file_sertifikat')<div style="font-size:.76rem;color:#ef4444;margin-top:6px">{{ $message }}</div>@enderror
                    </div>

                    <div style="display:flex;gap:10px">
                        <button type="submit" style="flex:1;padding:13px 28px;font-size:.92rem;font-weight:600;border:none;border-radius:10px;background:linear-gradient(135deg,#7c3aed 0%,#4f46e5 100%);color:#fff">
                            <i class="bi bi-send"></i> Ajukan Ulang
                        </button>
                        <a href="{{ route('sertifikat.index') }}" style="padding:13px 24px;font-size:.92rem;border-radius:10px;border:1.5px solid var(--border);color:var(--text-dark);text-decoration:none">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection