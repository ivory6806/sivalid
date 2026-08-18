@extends('layouts.app')
@section('title', 'Pengajuan Sertifikat')
@section('pageTitle', 'Pengajuan Validasi Sertifikat')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="card-custom" style="overflow:visible;position:relative;z-index:2">
            <div style="padding:32px 32px 28px;background:linear-gradient(135deg,#7c3aed 0%,#6d28d9 50%,#4f46e5 100%);border-radius:14px 14px 0 0;text-align:center;position:relative;overflow:hidden">
                <div style="position:absolute;top:-30px;right:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.06)"></div>
                <div style="position:absolute;bottom:-40px;left:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,.05)"></div>
                <div style="position:relative;z-index:1">
                    <div style="width:60px;height:60px;border-radius:16px;background:rgba(255,255,255,.15);backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;border:1px solid rgba(255,255,255,.2)">
                        <i class="bi bi-patch-plus" style="font-size:1.5rem;color:#fff"></i>
                    </div>
                    <div style="font-size:1.15rem;font-weight:800;color:#fff">Form Pengajuan Sertifikat</div>
                    <div style="font-size:.78rem;color:rgba(255,255,255,.65);margin-top:3px">Upload gambar sertifikat untuk divalidasi & diberi QR Code verifikasi</div>
                </div>
            </div>

            <div style="padding:28px 32px 32px;background:var(--card-bg);border-radius:0 0 14px 14px;border:1px solid var(--border);border-top:none">
                <form method="POST" action="{{ route('sertifikat.store') }}" enctype="multipart/form-data" x-data="{
                    fileName: '', fileSize: '', previewUrl: '', dragging: false, submitting: false,
                    allowedTypes: ['image/png', 'image/jpeg'],
                    handleFile(file) {
                        if (!file) return;
                        if (!this.allowedTypes.includes(file.type)) { alert('Hanya file PNG atau JPG yang diperbolehkan.'); return; }
                        if (file.size > 5 * 1024 * 1024) { alert('Ukuran file maksimal 5MB.'); return; }
                        this.fileName = file.name;
                        var kb = (file.size / 1024).toFixed(1);
                        var mb = (file.size / (1024 * 1024)).toFixed(2);
                        this.fileSize = file.size >= 1024 * 1024 ? mb + ' MB' : kb + ' KB';
                        // Buat preview thumbnail
                        var reader = new FileReader();
                        reader.onload = (e) => { this.previewUrl = e.target.result; };
                        reader.readAsDataURL(file);
                        var dt = new DataTransfer(); dt.items.add(file);
                        $refs.fileInput.files = dt.files;
                    },
                    removeFile() { 
                        this.fileName = ''; 
                        this.fileSize = ''; 
                        this.previewUrl = ''; 
                        $refs.fileInput.value = ''; 
                    },
                    submitForm() { if (this.submitting) return; this.submitting = true; $el.closest('form').submit(); }
                }">
                    @csrf

                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;padding-bottom:12px;border-bottom:1.5px solid var(--border)">
                        <div style="width:32px;height:32px;border-radius:8px;background:#f5f3ff;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi bi-award" style="color:#7c3aed;font-size:.9rem"></i>
                        </div>
                        <div>
                            <div style="font-size:.88rem;font-weight:700;color:var(--text-dark)">Data Sertifikat</div>
                        </div>
                    </div>

                    <div class="row g-3" style="margin-bottom:28px">
                        <div class="col-md-6">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Jenis Sertifikat <span style="color:#ef4444">*</span></label>
                            <div style="position:relative">
                                <i class="bi bi-award-fill" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.85rem"></i>
                                <input type="text" name="jenis_sertifikat" class="form-control" value="{{ old('jenis_sertifikat') }}" required placeholder="Contoh: Sertifikat Kompetensi RPL"
                                    style="border-radius:10px;padding:12px 16px 12px 42px;border:1.5px solid var(--border);font-size:.88rem;color:var(--text-dark)"
                                    onfocus="this.style.borderColor='#7c3aed';this.style.boxShadow='0 0 0 3px rgba(124,58,237,.1)'"
                                    onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                            </div>
                            @error('jenis_sertifikat')<div style="font-size:.76rem;color:#ef4444;margin-top:6px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Nomor Sertifikat <span style="color:#ef4444">*</span></label>
                            <div style="position:relative">
                                <i class="bi bi-hash" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.85rem"></i>
                                <input type="text" name="nomor_sertifikat" class="form-control" value="{{ old('nomor_sertifikat') }}" required placeholder="Contoh: 123/SERT/LPP/2026"
                                    style="border-radius:10px;padding:12px 16px 12px 42px;border:1.5px solid var(--border);font-size:.88rem;color:var(--text-dark)"
                                    onfocus="this.style.borderColor='#7c3aed';this.style.boxShadow='0 0 0 3px rgba(124,58,237,.1)'"
                                    onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                            </div>
                            @error('nomor_sertifikat')<div style="font-size:.76rem;color:#ef4444;margin-top:6px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;padding-bottom:12px;border-bottom:1.5px solid var(--border)">
                        <div style="width:32px;height:32px;border-radius:8px;background:#ede9fe;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi bi-image" style="color:#7c3aed;font-size:.9rem"></i>
                        </div>
                        <div>
                            <div style="font-size:.88rem;font-weight:700;color:var(--text-dark)">Upload Gambar Sertifikat</div>
                            <div style="font-size:.72rem;color:var(--text-muted)">Format PNG atau JPG — 1 gambar = 1 sertifikat</div>
                        </div>
                    </div>

                    <div style="margin-bottom:28px">
                        <input type="file" name="file_sertifikat" class="form-control" accept=".png,.jpg,.jpeg" required
                               x-ref="fileInput" @change="handleFile($el.files[0])" style="display:none">

                        <div @click="$refs.fileInput.click()"
                             @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                             @drop.prevent="dragging = false; if($event.dataTransfer.files.length){ handleFile($event.dataTransfer.files[0]); }"
                             :style="fileName
                                 ? (dragging ? 'border-color:#7c3aed;background:#f5f3ff' : 'border-color:#c4b5fd;background:#faf5ff')
                                 : (dragging ? 'border-color:#7c3aed;background:#f5f3ff' : 'border-color:var(--border);background:var(--card-bg)')"
                             style="border:2px dashed var(--border);border-radius:14px;transition:all .25s;cursor:pointer">

                            <!-- State: Belum pilih file -->
                            <div x-show="!fileName" style="padding:32px 24px;text-align:center">
                                <div style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#ede9fe,#ddd6fe);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                                    <i class="bi bi-image" style="font-size:1.4rem;color:#7c3aed"></i>
                                </div>
                                <div style="font-size:.9rem;font-weight:600;color:var(--text-dark);margin-bottom:6px">Seret & lepas gambar ke sini</div>
                                <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:16px">atau <span style="color:#7c3aed;font-weight:600;text-decoration:underline">klik untuk memilih file</span></div>
                                <div style="display:flex;align-items:center;justify-content:center;gap:16px;flex-wrap:wrap">
                                    <div style="display:flex;align-items:center;gap:5px;font-size:.74rem;color:var(--text-muted)"><i class="bi bi-check-circle-fill" style="color:#16a34a"></i> PNG / JPG</div>
                                    <div style="display:flex;align-items:center;gap:5px;font-size:.74rem;color:var(--text-muted)"><i class="bi bi-check-circle-fill" style="color:#16a34a"></i> Maks. 5MB</div>
                                    <div style="display:flex;align-items:center;gap:5px;font-size:.74rem;color:var(--text-muted)"><i class="bi bi-check-circle-fill" style="color:#16a34a"></i> Resolusi tinggi</div>
                                </div>
                            </div>

                            <!-- State: Sudah pilih file -->
                            <div x-show="fileName" style="padding:20px 24px">
                                <div style="display:flex;align-items:center;gap:16px">
                                    <!-- Thumbnail preview -->
                                    <div x-show="previewUrl" style="width:56px;height:56px;border-radius:10px;overflow:hidden;border:2px solid #ede9fe;flex-shrink:0;background:#f5f3ff">
                                        <img :src="previewUrl" style="width:100%;height:100%;object-fit:cover" alt="Preview">
                                    </div>
                                    <!-- Fallback icon kalau belum ada preview -->
                                    <div x-show="!previewUrl" style="width:56px;height:56px;border-radius:10px;background:linear-gradient(135deg,#ede9fe,#ddd6fe);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                        <i class="bi bi-file-earmark-image" style="font-size:1.3rem;color:#7c3aed"></i>
                                    </div>
                                    <div style="flex:1;min-width:0">
                                        <div style="font-size:.86rem;font-weight:600;color:var(--text-dark);overflow:hidden;text-overflow:ellipsis;white-space:nowrap" x-text="fileName"></div>
                                        <div style="display:flex;align-items:center;gap:10px;margin-top:4px;flex-wrap:wrap">
                                            <span style="font-size:.72rem;color:#7c3aed;font-weight:500" x-text="fileSize"></span>
                                            <span style="font-size:.72rem;color:var(--text-muted)"><i class="bi bi-check-circle-fill" style="color:#16a34a"></i> Siap upload</span>
                                        </div>
                                    </div>
                                    <button type="button" @click.stop="removeFile()"
                                        style="width:32px;height:32px;border-radius:8px;border:none;background:#fee2e2;color:#ef4444;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0"
                                        onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                                        <i class="bi bi-x-lg" style="font-size:.75rem"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('file_sertifikat')<div style="font-size:.76rem;color:#ef4444;margin-top:6px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div style="display:flex;gap:10px;padding-top:4px">
                        <button type="button" @click="submitForm()" :disabled="submitting"
                            style="flex:1;padding:13px 28px;font-size:.92rem;font-weight:600;border:none;border-radius:10px;background:linear-gradient(135deg,#7c3aed 0%,#4f46e5 100%);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 14px rgba(124,58,237,.25)">
                            <template x-if="!submitting"><span><i class="bi bi-send"></i> Ajukan Sertifikat</span></template>
                            <template x-if="submitting"><span><span class="spinner-border spinner-border-sm me-2" style="border-width:2px"></span>Mengajukan...</span></template>
                        </button>
                        <a href="{{ route('sertifikat.index') }}"
                            style="padding:13px 24px;font-size:.92rem;font-weight:500;border-radius:10px;border:1.5px solid var(--border);color:var(--text-dark);text-decoration:none;display:flex;align-items:center;gap:6px;background:var(--card-bg)">
                            <i class="bi bi-x-lg" style="font-size:.75rem"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div style="text-align:center;margin-top:16px">
            <div style="font-size:.74rem;color:var(--text-muted);display:flex;align-items:center;justify-content:center;gap:6px">
                <i class="bi bi-shield-check" style="color:#7c3aed"></i>
                Setelah diajukan, Kepala BAA & Beasiswa akan menerima notifikasi email untuk memvalidasi
            </div>
        </div>
    </div>
</div>
@endsection