@extends('layouts.app')
@section('title', 'Pengajuan Surat')
@section('pageTitle', 'Pengajuan Surat — ' . $unit->name)
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card-custom" style="overflow:visible;position:relative;z-index:2">
            <div style="padding:32px 32px 28px;background:linear-gradient(135deg,#059669 0%,#0d9488 50%,#0891b2 100%);border-radius:14px 14px 0 0;text-align:center;position:relative;overflow:hidden">
                <div style="position:absolute;top:-30px;right:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.06)"></div>
                <div style="position:absolute;bottom:-40px;left:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,.05)"></div>
                <div style="position:relative;z-index:1">
                    <div style="width:60px;height:60px;border-radius:16px;background:rgba(255,255,255,.15);backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;border:1px solid rgba(255,255,255,.2)">
                        <i class="bi bi-file-earmark-plus" style="font-size:1.5rem;color:#fff"></i>
                    </div>
                    <div style="font-size:1.15rem;font-weight:800;color:#fff">{{ $unit->jenis_dokumen_label }}</div>
                    <div style="font-size:.78rem;color:rgba(255,255,255,.65);margin-top:3px">Unit {{ $unit->name }} — lengkapi data berikut untuk mengajukan surat</div>
                </div>
            </div>

            <div style="padding:28px 32px 32px;background:var(--card-bg);border-radius:0 0 14px 14px;border:1px solid var(--border);border-top:none">
                <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data" x-data="{
                    fileName: '', fileSize: '', dragging: false, submitting: false,
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
                    @csrf

                    <div style="margin-bottom:18px">
                        <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">
                            Nama Surat <span style="color:#ef4444">*</span>
                        </label>
                        <input type="text" name="nama_surat" class="form-control" value="{{ old('nama_surat') }}" required placeholder="Contoh: Surat Keterangan Bebas Pustaka"
                            style="border-radius:10px;padding:12px 16px;border:1.5px solid var(--border);font-size:.9rem">
                        @error('nama_surat')<div style="font-size:.78rem;color:#ef4444;margin-top:6px">{{ $message }}</div>@enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6" style="margin-bottom:18px">
                            <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">
                                Nomor Surat <span style="color:#ef4444">*</span>
                            </label>
                            <input type="text" name="nomor_surat" class="form-control" value="{{ old('nomor_surat') }}" required
                                style="border-radius:10px;padding:12px 16px;border:1.5px solid var(--border);font-size:.9rem">
                            @error('nomor_surat')<div style="font-size:.78rem;color:#ef4444;margin-top:6px">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6" style="margin-bottom:18px">
                            <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">
                                Tanggal Surat <span style="color:#ef4444">*</span>
                            </label>
                            <input type="date" name="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required
                                style="border-radius:10px;padding:12px 16px;border:1.5px solid var(--border);font-size:.9rem">
                            @error('tanggal_surat')<div style="font-size:.78rem;color:#ef4444;margin-top:6px">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="margin-bottom:24px">
                        <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">
                            File Surat (PDF) <span style="color:#ef4444">*</span>
                            <span style="font-size:.72rem;color:var(--text-muted);font-weight:400">— boleh lebih dari 1 halaman, maks 10MB</span>
                        </label>

                        <div @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                             @drop.prevent="dragging = false; handleFile($event.dataTransfer.files[0])"
                             @click="$refs.fileInput.click()"
                             :style="dragging ? 'border-color:var(--primary);background:var(--primary-light)' : ''"
                             style="border:2px dashed var(--border);border-radius:12px;padding:28px;text-align:center;cursor:pointer;transition:all .15s">
                            <input type="file" name="file_surat" x-ref="fileInput" accept="application/pdf" required
                                @change="handleFile($event.target.files[0])" style="display:none">

                            <template x-if="!fileName">
                                <div>
                                    <i class="bi bi-cloud-arrow-up" style="font-size:1.8rem;color:var(--text-muted)"></i>
                                    <div style="font-size:.85rem;color:var(--text-dark);margin-top:8px;font-weight:600">Klik atau seret file PDF ke sini</div>
                                </div>
                            </template>
                            <template x-if="fileName">
                                <div style="display:flex;align-items:center;justify-content:center;gap:10px">
                                    <i class="bi bi-file-earmark-pdf-fill" style="font-size:1.4rem;color:#ef4444"></i>
                                    <div style="text-align:left">
                                        <div style="font-size:.85rem;font-weight:600;color:var(--text-dark)" x-text="fileName"></div>
                                        <div style="font-size:.72rem;color:var(--text-muted)" x-text="fileSize"></div>
                                    </div>
                                    <button type="button" @click.stop="removeFile()" style="background:none;border:none;color:#ef4444;font-size:1rem"><i class="bi bi-x-circle"></i></button>
                                </div>
                            </template>
                        </div>
                        @error('file_surat')<div style="font-size:.78rem;color:#ef4444;margin-top:6px">{{ $message }}</div>@enderror
                    </div>

                    <div style="display:flex;gap:10px">
                        <button type="submit" class="btn-primary-custom" style="padding:12px 28px;font-size:.92rem;flex:1" :disabled="submitting">
                            <span x-show="!submitting"><i class="bi bi-send"></i> Ajukan Surat</span>
                            <span x-show="submitting"><i class="bi bi-arrow-repeat"></i> Mengirim...</span>
                        </button>
                        <a href="{{ route('surat.index') }}" class="btn-outline-custom" style="padding:12px 24px;font-size:.92rem">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
