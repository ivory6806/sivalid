@extends('layouts.app')
@section('title', 'Edit Surat')
@section('pageTitle', 'Edit & Ajukan Ulang')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-custom" style="overflow:visible;position:relative;z-index:2">
            <div style="padding:32px 32px 28px;background:linear-gradient(135deg,#d97706 0%,#f59e0b 100%);border-radius:14px 14px 0 0;text-align:center">
                <div style="width:60px;height:60px;border-radius:16px;background:rgba(255,255,255,.15);backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;border:1px solid rgba(255,255,255,.2)">
                    <i class="bi bi-pencil-square" style="font-size:1.5rem;color:#fff"></i>
                </div>
                <div style="font-size:1.15rem;font-weight:800;color:#fff">Edit & Ajukan Ulang</div>
                <div style="font-size:.78rem;color:rgba(255,255,255,.75);margin-top:3px">Perbaiki data sesuai catatan revisi, lalu ajukan kembali</div>
            </div>

            <div style="padding:28px 32px 32px;background:var(--card-bg);border-radius:0 0 14px 14px;border:1px solid var(--border);border-top:none">
                @if($surat->catatan_revisi)
                <div style="padding:12px 16px;background:#fef2f2;border-radius:10px;margin-bottom:20px;font-size:.84rem;color:#dc2626">
                    <i class="bi bi-chat-left-text-fill"></i> {{ $surat->catatan_revisi }}
                </div>
                @endif

                <form method="POST" action="{{ route('surat.update', $surat) }}" enctype="multipart/form-data" x-data="{
                    fileName: '{{ $surat->file_surat }}', fileSize: '', dragging: false, submitting: false,
                    handleFile(file) {
                        if (!file) return;
                        if (file.type !== 'application/pdf') { alert('Hanya file PDF yang diperbolehkan.'); return; }
                        if (file.size > 10 * 1024 * 1024) { alert('Ukuran file maksimal 10MB.'); return; }
                        this.fileName = file.name;
                        var kb = (file.size / 1024).toFixed(1);
                        var mb = (file.size / (1024*1024)).toFixed(2);
                        this.fileSize = file.size >= 1024*1024 ? mb + ' MB' : kb + ' KB';
                        var dt = new DataTransfer(); dt.items.add(file);
                        $refs.fileInput.files = dt.files;
                    },
                    removeFile() { this.fileName = ''; this.fileSize = ''; $refs.fileInput.value = ''; }
                }" @submit="submitting = true">
                    @csrf @method('PUT')

                    <div style="margin-bottom:18px">
                        <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Nama Surat <span style="color:#ef4444">*</span></label>
                        <input type="text" name="nama_surat" class="form-control" value="{{ old('nama_surat', $surat->nama_surat) }}" required
                            style="border-radius:10px;padding:12px 16px;border:1.5px solid var(--border);font-size:.9rem">
                        @error('nama_surat')<div style="font-size:.78rem;color:#ef4444;margin-top:6px">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6" style="margin-bottom:18px">
                            <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Nomor Surat <span style="color:#ef4444">*</span></label>
                            <input type="text" name="nomor_surat" class="form-control" value="{{ old('nomor_surat', $surat->nomor_surat) }}" required
                                style="border-radius:10px;padding:12px 16px;border:1.5px solid var(--border);font-size:.9rem">
                            @error('nomor_surat')<div style="font-size:.78rem;color:#ef4444;margin-top:6px">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6" style="margin-bottom:18px">
                            <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Tanggal Surat <span style="color:#ef4444">*</span></label>
                            <input type="date" name="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', $surat->tanggal_surat?->format('Y-m-d')) }}" required
                                style="border-radius:10px;padding:12px 16px;border:1.5px solid var(--border);font-size:.9rem">
                            @error('tanggal_surat')<div style="font-size:.78rem;color:#ef4444;margin-top:6px">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="margin-bottom:24px">
                        <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">
                            File Surat (PDF) <span style="font-size:.72rem;color:var(--text-muted);font-weight:400">— kosongkan jika tidak ganti file</span>
                        </label>
                        <div @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                             @drop.prevent="dragging = false; handleFile($event.dataTransfer.files[0])"
                             @click="$refs.fileInput.click()"
                             :style="dragging ? 'border-color:var(--primary);background:var(--primary-light)' : ''"
                             style="border:2px dashed var(--border);border-radius:12px;padding:24px;text-align:center;cursor:pointer">
                            <input type="file" name="file_surat" x-ref="fileInput" accept="application/pdf"
                                @change="handleFile($event.target.files[0])" style="display:none">
                            <div style="display:flex;align-items:center;justify-content:center;gap:10px">
                                <i class="bi bi-file-earmark-pdf-fill" style="font-size:1.3rem;color:#ef4444"></i>
                                <div style="text-align:left">
                                    <div style="font-size:.85rem;font-weight:600;color:var(--text-dark)" x-text="fileName"></div>
                                    <div style="font-size:.72rem;color:var(--text-muted)" x-text="fileSize || 'File saat ini — klik untuk ganti'"></div>
                                </div>
                            </div>
                        </div>
                        @error('file_surat')<div style="font-size:.78rem;color:#ef4444;margin-top:6px">{{ $message }}</div>@enderror
                    </div>

                    <div style="display:flex;gap:10px">
                        <button type="submit" class="btn-primary-custom" style="padding:12px 28px;font-size:.92rem;flex:1" :disabled="submitting">
                            <i class="bi bi-send"></i> Ajukan Ulang
                        </button>
                        <a href="{{ route('surat.index') }}" class="btn-outline-custom" style="padding:12px 24px;font-size:.92rem">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
