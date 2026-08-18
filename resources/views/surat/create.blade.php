@extends('layouts.app')
@section('title', 'Pengajuan Surat')
@section('pageTitle', 'Pengajuan Surat Keterangan Aktif')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- <div style="text-align:center;margin-bottom:20px;position:relative;z-index:1">
            <div style="display:inline-flex;align-items:center;gap:10px;padding:10px 20px;border-radius:50px;background:var(--primary-light);border:1px solid rgba(5,150,105,.15)">
                <i class="bi bi-file-earmark-text" style="color:var(--primary);font-size:1rem"></i>
                <span style="font-size:.82rem;font-weight:600;color:var(--primary)">Surat Keterangan Aktif Kuliah</span>
            </div>
        </div> -->

        <div class="card-custom" style="overflow:visible;position:relative;z-index:2">
            <div style="padding:32px 32px 28px;background:linear-gradient(135deg,#059669 0%,#0d9488 50%,#0891b2 100%);border-radius:14px 14px 0 0;text-align:center;position:relative;overflow:hidden">
                <div style="position:absolute;top:-30px;right:-30px;width:120px;height:120px;border-radius:50%;background:rgba(255,255,255,.06)"></div>
                <div style="position:absolute;bottom:-40px;left:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,.05)"></div>
                <div style="position:relative;z-index:1">
                    <div style="width:60px;height:60px;border-radius:16px;background:rgba(255,255,255,.15);backdrop-filter:blur(10px);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;border:1px solid rgba(255,255,255,.2)">
                        <i class="bi bi-file-earmark-plus" style="font-size:1.5rem;color:#fff"></i>
                    </div>
                    <div style="font-size:1.15rem;font-weight:800;color:#fff">Form Pengajuan Surat</div>
                    <div style="font-size:.78rem;color:rgba(255,255,255,.65);margin-top:3px">Lengkapi data berikut untuk mengajukan surat keterangan aktif</div>
                </div>
            </div>

            <div style="padding:28px 32px 32px;background:var(--card-bg);border-radius:0 0 14px 14px;border:1px solid var(--border);border-top:none">
                <form method="POST" action="{{ route('surat.store') }}" enctype="multipart/form-data" id="suratForm" x-data="{
                    hubungan: '{{ old('hubungan', 'Ayah') }}',
                    fileName: '',
                    fileSize: '',
                    fileObject: null,
                    dragging: false,
                    submitting: false,
                    handleFile(file) {
                        if (!file) return;
                        if (file.type !== 'application/pdf') {
                            alert('Hanya file PDF yang diperbolehkan.');
                            return;
                        }
                        if (file.size > 5 * 1024 * 1024) {
                            alert('Ukuran file maksimal 5MB.');
                            return;
                        }
                        this.fileObject = file;
                        this.fileName = file.name;
                        var kb = (file.size / 1024).toFixed(1);
                        var mb = (file.size / (1024 * 1024)).toFixed(2);
                        this.fileSize = file.size >= 1024 * 1024 ? mb + ' MB' : kb + ' KB';
                        var dt = new DataTransfer();
                        dt.items.add(file);
                        $refs.fileInput.files = dt.files;
                    },
                    removeFile() {
                        this.fileObject = null;
                        this.fileName = '';
                        this.fileSize = '';
                        $refs.fileInput.value = '';
                    },
                    submitForm() {
                        if (this.submitting) return;
                        this.submitting = true;
                        $el.closest('form').submit();
                    }
                }">
                    @csrf

                    <!-- ── DATA MAHASISWA ── -->
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;padding-bottom:12px;border-bottom:1.5px solid var(--border)">
                        <div style="width:32px;height:32px;border-radius:8px;background:var(--primary-light);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi bi-person" style="color:var(--primary);font-size:.9rem"></i>
                        </div>
                        <div>
                            <div style="font-size:.88rem;font-weight:700;color:var(--text-dark)">Data Mahasiswa</div>
                            <!-- <div style="font-size:.72rem;color:var(--text-muted)">Informasi identitas mahasiswa pengaju</div> -->
                        </div>
                    </div>

                    <div class="row g-3" style="margin-bottom:28px">
                        <div class="col-md-6">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Nama Lengkap <span style="color:#ef4444">*</span></label>
                            <div style="position:relative">
                                <i class="bi bi-person-fill" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.85rem"></i>
                                <input type="text" name="nama_mahasiswa" class="form-control" value="{{ old('nama_mahasiswa') }}" required placeholder="Nama Lengkap"
                                    style="border-radius:10px;padding:12px 16px 12px 42px;border:1.5px solid var(--border);font-size:.88rem;transition:all .2s;color:var(--text-dark)"
                                    onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                    onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                            </div>
                            @error('nama_mahasiswa')<div style="font-size:.76rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">NIM <span style="color:#ef4444">*</span></label>
                            <div style="position:relative">
                                <i class="bi bi-hash" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.85rem"></i>
                                <input type="text" name="nim" class="form-control" value="{{ old('nim') }}" required placeholder="Nomor Induk Mahasiswa"
                                    style="border-radius:10px;padding:12px 16px 12px 42px;border:1.5px solid var(--border);font-size:.88rem;transition:all .2s;color:var(--text-dark)"
                                    onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                    onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                            </div>
                            @error('nim')<div style="font-size:.76rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Nomor Surat <span style="color:#ef4444">*</span></label>
                            <div style="position:relative">
                                <i class="bi bi-hash" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.85rem"></i>
                                <input type="text" name="nomor_surat" class="form-control" value="{{ old('nomor_surat') }}" required placeholder="Contoh: 081/Sket/AK/20252/2026"
                                    style="border-radius:10px;padding:12px 16px 12px 42px;border:1.5px solid var(--border);font-size:.88rem;transition:all .2s;color:var(--text-dark)"
                                    onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                    onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                            </div>
                            @error('nomor_surat')<div style="font-size:.76rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Program Studi <span style="color:#ef4444">*</span></label>
                            <div x-data="mhsDD('prodiList','initProdi','')" @click.outside="open=false" style="position:relative">
                                <input type="hidden" name="prodi" :value="selected">
                                <div @click="open=!open" class="dd-trigger" :aria-expanded="open.toString()" style="padding:12px 36px 12px 42px;font-size:.88rem;width:100%">
                                    <i class="bi bi-mortarboard" :class="selected?'dd-icon-active':'dd-icon-muted'" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:.85rem;transition:color .2s"></i>
                                    <span x-text="label" :class="selected?'':'dd-placeholder'"></span>
                                </div>
                                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute z-50 mt-2 w-full dd-panel" style="display:none">
                                    <div class="dd-body" style="max-height:220px;overflow-y:auto">
                                        <template x-for="opt in options" :key="opt"><a href="#" @click.prevent="pick(opt)" :class="{'active':selected===opt}"><i class="bi bi-mortarboard"></i><span x-text="opt"></span><i x-show="selected===opt" class="bi bi-check-lg" style="margin-left:auto"></i></a></template>
                                        <template x-if="options.length===0"><div style="padding:16px;text-align:center;font-size:.82rem;color:var(--text-muted)">Belum ada opsi.</div></template>
                                    </div>
                                </div>
                            </div>
                            @error('prodi')<div style="font-size:.76rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Semester <span style="color:#ef4444">*</span></label>
                            <div x-data="mhsDD('semesterList','initSemester','Semester ')" @click.outside="open=false" style="position:relative">
                                <input type="hidden" name="semester" :value="selected">
                                <div @click="open=!open" class="dd-trigger" :aria-expanded="open.toString()" style="padding:12px 36px 12px 42px;font-size:.88rem;width:100%">
                                    <i class="bi bi-book" :class="selected?'dd-icon-active':'dd-icon-muted'" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:.85rem;transition:color .2s"></i>
                                    <span x-text="label" :class="selected?'':'dd-placeholder'"></span>
                                </div>
                                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute z-50 mt-2 w-full dd-panel" style="display:none">
                                    <div class="dd-body" style="max-height:220px;overflow-y:auto">
                                        <template x-for="opt in options" :key="opt"><a href="#" @click.prevent="pick(opt)" :class="{'active':selected===opt}"><i class="bi bi-book"></i><span x-text="'Semester '+opt"></span><i x-show="selected===opt" class="bi bi-check-lg" style="margin-left:auto"></i></a></template>
                                    </div>
                                </div>
                            </div>
                            @error('semester')<div style="font-size:.76rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-3">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Tahun Ajaran <span style="color:#ef4444">*</span></label>
                            <div x-data="mhsDD('taList','initTa','')" @click.outside="open=false" style="position:relative">
                                <input type="hidden" name="tahun_ajaran" :value="selected">
                                <div @click="open=!open" class="dd-trigger" :aria-expanded="open.toString()" style="padding:12px 36px 12px 42px;font-size:.88rem;width:100%">
                                    <i class="bi bi-calendar3" :class="selected?'dd-icon-active':'dd-icon-muted'" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:.85rem;transition:color .2s"></i>
                                    <span x-text="label" :class="selected?'':'dd-placeholder'"></span>
                                </div>
                                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute z-50 mt-2 w-full dd-panel" style="display:none">
                                    <div class="dd-body">
                                        <template x-for="opt in options" :key="opt"><a href="#" @click.prevent="pick(opt)" :class="{'active':selected===opt}"><i class="bi bi-calendar3"></i><span x-text="opt"></span><i x-show="selected===opt" class="bi bi-check-lg" style="margin-left:auto"></i></a></template>
                                    </div>
                                </div>
                            </div>
                            @error('tahun_ajaran')<div style="font-size:.76rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div>

                    <!-- ── DATA ORANG TUA ── -->
                    <!-- <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;padding-bottom:12px;border-bottom:1.5px solid var(--border)">
                        <div style="width:32px;height:32px;border-radius:8px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi bi-people" style="color:#d97706;font-size:.9rem"></i>
                        </div>
                        <div>
                            <div style="font-size:.88rem;font-weight:700;color:var(--text-dark)">Data Orang Tua</div>
                        </div>
                    </div>

                    <div class="row g-3" style="margin-bottom:28px">
                        <div class="col-md-8">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Nama Orang Tua <span style="color:#ef4444">*</span></label>
                            <div style="display:flex">
                                <input type="hidden" name="hubungan" :value="hubungan">

                                <button type="button" @click="hubungan='Ayah'"
                                    :style="hubungan==='Ayah'
                                        ? 'padding:12px 16px;border-radius:10px 0 0 10px;border:1.5px solid var(--primary);border-right:none;background:var(--primary);color:#fff;font-size:.82rem;font-weight:600;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:6px;transition:all .15s'
                                        : 'padding:12px 16px;border-radius:10px 0 0 10px;border:1.5px solid var(--border);border-right:none;background:var(--card-bg);color:var(--text-muted);font-size:.82rem;font-weight:500;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:6px;transition:all .15s'">
                                    <i class="bi bi-gender-male" style="font-size:.85rem"></i> Ayah
                                </button>

                                <button type="button" @click="hubungan='Ibu'"
                                    :style="hubungan==='Ibu'
                                        ? 'padding:12px 16px;border-radius:0 10px 10px 0;border:1.5px solid var(--primary);border-left:none;background:var(--primary);color:#fff;font-size:.82rem;font-weight:600;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:6px;transition:all .15s'
                                        : 'padding:12px 16px;border-radius:0 10px 10px 0;border:1.5px solid var(--border);border-left:none;background:var(--card-bg);color:var(--text-muted);font-size:.82rem;font-weight:500;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:6px;transition:all .15s'">
                                    <i class="bi bi-gender-female" style="font-size:.85rem"></i> Ibu
                                </button>

                                <input type="text" name="nama_orangtua" class="form-control" value="{{ old('nama_orangtua') }}" required placeholder="Nama Lengkap"
                                    style="border-radius:0 10px 10px 0;padding:12px 16px;border:1.5px solid var(--border);border-left:none;font-size:.88rem;transition:all .2s;color:var(--text-dark);flex:1"
                                    onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                    onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                            </div>
                            @error('nama_orangtua')<div style="font-size:.76rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-4">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Pekerjaan <span style="color:#ef4444">*</span></label>
                            <div style="position:relative">
                                <i class="bi bi-briefcase" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.85rem"></i>
                                <input type="text" name="pekerjaan" class="form-control" value="{{ old('pekerjaan') }}" required placeholder="Pekerjaan"
                                    style="border-radius:10px;padding:12px 16px 12px 42px;border:1.5px solid var(--border);font-size:.88rem;transition:all .2s;color:var(--text-dark)"
                                    onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                    onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                            </div>
                            @error('pekerjaan')<div style="font-size:.76rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="col-12">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Alamat Orang Tua <span style="color:#ef4444">*</span></label>
                            <div style="position:relative">
                                <i class="bi bi-geo-alt" style="position:absolute;left:14px;top:16px;color:var(--text-muted);font-size:.85rem"></i>
                                <textarea name="alamat_orangtua" class="form-control" rows="2" required placeholder="Tulis alamat lengkap orang tua"
                                    style="border-radius:10px;padding:12px 16px 12px 42px;border:1.5px solid var(--border);font-size:.88rem;transition:all .2s;color:var(--text-dark);resize:vertical;min-height:72px"
                                    onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                    onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">{{ old('alamat_orangtua') }}</textarea>
                            </div>
                            @error('alamat_orangtua')<div style="font-size:.76rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>
                    </div> -->

                    <!-- ── UPLOAD FILE ── -->
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:18px;padding-bottom:12px;border-bottom:1.5px solid var(--border)">
                        <div style="width:32px;height:32px;border-radius:8px;background:#ede9fe;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <i class="bi bi-cloud-arrow-up" style="color:#7c3aed;font-size:.9rem"></i>
                        </div>
                        <div>
                            <div style="font-size:.88rem;font-weight:700;color:var(--text-dark)">Upload File Surat</div>
                            <div style="font-size:.72rem;color:var(--text-muted)">Dokumen surat keterangan yang akan diajukan</div>
                        </div>
                    </div>

                    <div style="margin-bottom:28px">
                        <input type="file" name="file_surat" class="form-control" accept=".pdf" required
                               x-ref="fileInput"
                               @change="handleFile($el.files[0])"
                               style="display:none">

                        <div @click="$refs.fileInput.click()"
                             @dragover.prevent="dragging = true"
                             @dragleave.prevent="dragging = false"
                             @drop.prevent="dragging = false; if($event.dataTransfer.files.length){ handleFile($event.dataTransfer.files[0]); }"
                             :style="fileName
                                 ? (dragging ? 'border-color:var(--primary);background:var(--primary-light);transform:scale(1.01)' : 'border-color:#a7f3d0;background:#f0fdf4')
                                 : (dragging ? 'border-color:var(--primary);background:var(--primary-light);transform:scale(1.01)' : 'border-color:var(--border);background:var(--card-bg)')"
                             style="border:2px dashed var(--border);border-radius:14px;transition:all .25s;cursor:pointer">

                            <!-- Belum ada file -->
                            <div x-show="!fileName" x-transition style="padding:32px 24px;text-align:center">
                                <div style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,#ede9fe,#ddd6fe);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
                                    <i class="bi bi-cloud-arrow-up" style="font-size:1.4rem;color:#7c3aed"></i>
                                </div>
                                <div style="font-size:.9rem;font-weight:600;color:var(--text-dark);margin-bottom:6px">Seret & lepas file PDF ke sini</div>
                                <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:16px">atau <span style="color:var(--primary);font-weight:600;text-decoration:underline;cursor:pointer">klik untuk memilih file</span></div>
                                <div style="display:flex;align-items:center;justify-content:center;gap:16px;flex-wrap:wrap">
                                    <div style="display:flex;align-items:center;gap:5px;font-size:.74rem;color:var(--text-muted)">
                                        <i class="bi bi-check-circle-fill" style="color:#16a34a;font-size:.85rem"></i> Format PDF
                                    </div>
                                    <div style="display:flex;align-items:center;gap:5px;font-size:.74rem;color:var(--text-muted)">
                                        <i class="bi bi-check-circle-fill" style="color:#16a34a;font-size:.85rem"></i> Maks. 5MB
                                    </div>
                                    <div style="display:flex;align-items:center;gap:5px;font-size:.74rem;color:var(--text-muted)">
                                        <i class="bi bi-check-circle-fill" style="color:#16a34a;font-size:.85rem"></i> Tidak terproteksi password
                                    </div>
                                </div>
                            </div>

                            <!-- File sudah dipilih -->
                            <div x-show="fileName" x-transition style="padding:20px 24px">
                                <div style="display:flex;align-items:center;gap:16px">
                                    <div style="width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                        <i class="bi bi-file-earmark-pdf-fill" style="font-size:1.3rem;color:#16a34a"></i>
                                    </div>
                                    <div style="flex:1;min-width:0">
                                        <div style="font-size:.86rem;font-weight:600;color:var(--text-dark);overflow:hidden;text-overflow:ellipsis;white-space:nowrap" x-text="fileName"></div>
                                        <div style="display:flex;align-items:center;gap:10px;margin-top:4px;flex-wrap:wrap">
                                            <span style="font-size:.72rem;color:#16a34a;font-weight:500;display:flex;align-items:center;gap:4px">
                                                <i class="bi bi-filetype-pdf" style="font-size:.75rem"></i>
                                                <span x-text="fileSize"></span>
                                            </span>
                                            <span style="font-size:.72rem;color:var(--text-muted);display:flex;align-items:center;gap:4px">
                                                <i class="bi bi-check-circle-fill" style="font-size:.7rem;color:#16a34a"></i>
                                                Siap upload
                                            </span>
                                        </div>
                                    </div>
                                    <button type="button" @click.stop="removeFile()"
                                        style="width:32px;height:32px;border-radius:8px;border:none;background:#fee2e2;color:#ef4444;cursor:pointer;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .15s"
                                        onmouseover="this.style.background='#fecaca';this.style.transform='scale(1.05)'"
                                        onmouseout="this.style.background='#fee2e2';this.style.transform='scale(1)'">
                                        <i class="bi bi-x-lg" style="font-size:.75rem"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @error('file_surat')<div style="font-size:.76rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <!-- ── BUTTONS ── -->
                    <div style="display:flex;gap:10px;padding-top:4px">
                        <button type="button" @click="submitForm()" x-ref="btnSubmit"
                            :disabled="submitting"
                            :style="submitting
                                ? 'flex:1;padding:13px 28px;font-size:.92rem;font-weight:600;border:none;border-radius:10px;background:linear-gradient(135deg,#059669 0%,#0d9488 100%);color:#fff;cursor:not-allowed;display:flex;align-items:center;justify-content:center;gap:8px;opacity:.8'
                                : 'flex:1;padding:13px 28px;font-size:.92rem;font-weight:600;border:none;border-radius:10px;background:linear-gradient(135deg,#059669 0%,#0d9488 100%);color:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .2s;box-shadow:0 4px 14px rgba(5,150,105,.25)'"
                            @mouseover="if(!submitting){$el.style.transform='translateY(-1px)';$el.style.boxShadow='0 6px 20px rgba(5,150,105,.35)'}"
                            @mouseout="if(!submitting){$el.style.transform='translateY(0)';$el.style.boxShadow='0 4px 14px rgba(5,150,105,.25)'}">
                            <template x-if="!submitting">
                                <span><i class="bi bi-send"></i> Ajukan Surat</span>
                            </template>
                            <template x-if="submitting">
                                <span>
                                    <span class="spinner-border spinner-border-sm me-2" style="border-width:2px"></span>
                                    Mengajukan...
                                </span>
                            </template>
                        </button>
                        <a href="{{ route('surat.index') }}"
                            style="padding:13px 24px;font-size:.92rem;font-weight:500;border-radius:10px;border:1.5px solid var(--border);color:var(--text-dark);text-decoration:none;display:flex;align-items:center;gap:6px;transition:all .2s;background:var(--card-bg)"
                            onmouseover="this.style.borderColor='#ef4444';this.style.color='#ef4444';this.style.background='#fef2f2'"
                            onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text-dark)';this.style.background='var(--card-bg)'">
                            <i class="bi bi-x-lg" style="font-size:.75rem"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <div style="text-align:center;margin-top:16px">
            <div style="font-size:.74rem;color:var(--text-muted);display:flex;align-items:center;justify-content:center;gap:6px">
                <i class="bi bi-shield-check" style="color:var(--primary)"></i>
                Data yang Anda masukkan akan diverifikasi oleh admin BAA & Beasiswa
            </div>
        </div>
    </div>
</div>

<script>
window.__ddForm = {
    prodiList: @json($prodiList),
    semesterList: @json($semesterList),
    taList: @json($taList),
    initProdi: @json(old('prodi', '')),
    initSemester: @json(old('semester', '')),
    initTa: @json(old('tahun_ajaran', ''))
};

function mhsDD(listKey, initKey, prefix) {
    return {
        selected: window.__ddForm[initKey] || '',
        open: false,
        options: window.__ddForm[listKey] || [],
        prefix: prefix,
        get label() {
            var o = this.options.find(function(i) { return i === this.selected; }.bind(this));
            return o ? this.prefix + o : '-- Pilih --';
        },
        pick: function(v) { this.selected = v; this.open = false; }
    };
}
</script>

<style>
.dd-trigger{display:flex;align-items:center;gap:8px;border-radius:10px;border:1.5px solid var(--border);background:var(--card-bg);cursor:pointer;transition:all .2s;color:var(--text-dark);font-weight:500;white-space:nowrap;user-select:none;position:relative}
.dd-trigger::after{content:'';position:absolute;right:12px;top:50%;width:6px;height:6px;border-right:2px solid var(--text-muted);border-bottom:2px solid var(--text-muted);transform:translateY(-50%) rotate(45deg);transition:transform .2s}
.dd-trigger:hover{border-color:var(--primary);background:var(--primary-light)}
.dd-trigger:hover::after{border-color:var(--primary);transform:translateY(-50%) rotate(225deg)}
.dd-trigger[aria-expanded="true"]{border-color:var(--primary);box-shadow:0 0 0 3px rgba(5,150,105,.1)}
.dd-trigger[aria-expanded="true"]::after{border-color:var(--primary);transform:translateY(-50%) rotate(-45deg)}
.dd-icon-muted{color:var(--text-muted)}.dd-icon-active{color:var(--primary)}
.dd-placeholder{color:var(--text-muted);font-weight:400}
.dd-panel{background:#fff;border-radius:14px;border:1px solid var(--border);box-shadow:0 20px 50px rgba(0,0,0,.08),0 4px 15px rgba(0,0,0,.03);overflow:hidden}
.dd-body{padding:6px}
.dd-body a{display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;color:var(--text-dark);font-size:.84rem;font-weight:500;text-decoration:none;transition:all .15s}
.dd-body a:hover{background:var(--primary-light);color:var(--primary);font-weight:600}
.dd-body a:active{background:#d1fae5}
.dd-body a.active{background:var(--primary);color:#fff;font-weight:600;box-shadow:0 2px 8px rgba(5,150,105,.2)}
.dd-body a.active i{color:#fff!important}
.dd-body::-webkit-scrollbar{width:4px}.dd-body::-webkit-scrollbar-track{background:transparent}.dd-body::-webkit-scrollbar-thumb{background:#d1d5db;border-radius:4px}
</style>
@endsection