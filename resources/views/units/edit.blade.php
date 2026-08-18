@extends('layouts.app')
@section('title', 'Kelola Unit')
@section('pageTitle', 'Kelola Unit: ' . $unit->name)
@section('content')

<div x-data="{
    showRemoveModal: false,
    removeName: '',
    removeFormId: '',
    confirmRemove() { document.getElementById(this.removeFormId).submit(); },
    askRemove(name, formId) { this.removeName = name; this.removeFormId = formId; this.showRemoveModal = true; },
    showJabatanModal: false,
    jabatanMemberName: '',
    jabatanValue: '',
    jabatanFormAction: '',
    openJabatan(name, action, current) { this.jabatanMemberName = name; this.jabatanFormAction = action; this.jabatanValue = current || ''; this.showJabatanModal = true; }
}" @keydown.escape.window="showRemoveModal = false; showJabatanModal = false">

    <div style="margin-bottom:20px">
        <a href="{{ route('units.index') }}" style="font-size:.82rem;color:var(--text-muted);text-decoration:none;display:inline-flex;align-items:center;gap:4px">
            <i class="bi bi-arrow-left"></i> Kembali ke daftar unit
        </a>
    </div>

    <div class="row g-4">
        <!-- Info Unit -->
        <div class="col-lg-5">
            <div class="card-custom">
                <div style="padding:22px">
                    <div style="font-size:.95rem;font-weight:700;color:var(--text-dark);margin-bottom:16px;display:flex;align-items:center;gap:8px">
                        <i class="bi bi-diagram-3-fill" style="color:var(--primary)"></i> Informasi Unit
                    </div>
                    <form method="POST" action="{{ route('units.update', $unit) }}">
                        @csrf @method('PUT')

                        <div style="margin-bottom:16px">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:6px;display:block">Nama Unit</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $unit->name) }}" required
                                style="border-radius:10px;padding:10px 14px;border:1.5px solid var(--border);font-size:.88rem">
                            @error('name')<div style="font-size:.76rem;color:#ef4444;margin-top:4px">{{ $message }}</div>@enderror
                        </div>

                        <div style="margin-bottom:16px">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:6px;display:block">Kode Unit</label>
                            <input type="text" name="code" class="form-control" value="{{ old('code', $unit->code) }}" required {{ $unit->code === 'akademik' ? 'readonly' : '' }}
                                style="border-radius:10px;padding:10px 14px;border:1.5px solid var(--border);font-size:.88rem;{{ $unit->code === 'akademik' ? 'background:#f8fafc;color:var(--text-muted)' : '' }}">
                            @error('code')<div style="font-size:.76rem;color:#ef4444;margin-top:4px">{{ $message }}</div>@enderror
                        </div>

                        <div style="margin-bottom:16px">
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:6px;display:block">Label Jenis Dokumen</label>
                            <input type="text" name="jenis_dokumen_label" class="form-control" value="{{ old('jenis_dokumen_label', $unit->jenis_dokumen_label) }}" required
                                style="border-radius:10px;padding:10px 14px;border:1.5px solid var(--border);font-size:.88rem">
                        </div>

                        <div style="margin-bottom:20px;padding:14px 16px;background:#f8fafc;border-radius:10px;border:1px solid var(--border)">
                            <div style="display:flex;align-items:center;justify-content:space-between">
                                <div>
                                    <div style="font-size:.84rem;font-weight:600;color:var(--text-dark)">Status Unit</div>
                                    <div style="font-size:.72rem;color:var(--text-muted);margin-top:2px">Unit nonaktif tidak muncul saat login/pilih unit</div>
                                </div>
                                <div class="form-check form-switch" style="margin:0">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ $unit->is_active ? 'checked' : '' }} value="1"
                                        style="width:44px;height:24px;cursor:pointer" {{ $unit->code === 'akademik' ? 'disabled' : '' }}>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary-custom" style="width:100%;padding:11px;font-size:.88rem">
                            <i class="bi bi-check-lg"></i> Simpan Perubahan
                        </button>
                    </form>

                    @if($unit->code !== 'akademik')
                    <form method="POST" action="{{ route('units.destroy', $unit) }}" style="margin-top:10px" onsubmit="return confirm('Hapus unit {{ addslashes($unit->name) }}? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-outline-custom" style="width:100%;padding:11px;font-size:.85rem;border-color:#fecaca;color:#ef4444">
                            <i class="bi bi-trash"></i> Hapus Unit
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kelola Anggota -->
        <div class="col-lg-7">
            <div class="card-custom">
                <div style="padding:22px">
                    <div style="font-size:.95rem;font-weight:700;color:var(--text-dark);margin-bottom:16px;display:flex;align-items:center;gap:8px">
                        <i class="bi bi-people-fill" style="color:var(--primary)"></i> Kepala & Staff Unit Ini
                    </div>

                    <!-- Form Tambah Anggota -->
                    <form method="POST" action="{{ route('units.members.add', $unit) }}" style="display:flex;gap:8px;margin-bottom:10px;flex-wrap:wrap">
                        @csrf
                        <select name="user_id" required class="form-select" style="flex:1;min-width:160px;border-radius:10px;padding:10px 12px;border:1.5px solid var(--border);font-size:.85rem">
                            <option value="">-- Pilih User --</option>
                            @foreach($availableUsers as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
                        <select name="role" required class="form-select" style="width:110px;border-radius:10px;padding:10px 12px;border:1.5px solid var(--border);font-size:.85rem">
                            <option value="staff">Staff</option>
                            <option value="kepala">Kepala</option>
                        </select>
                        <input type="text" name="jabatan" placeholder="Nama jabatan (opsional)" class="form-control" style="flex:1;min-width:160px;border-radius:10px;padding:10px 12px;border:1.5px solid var(--border);font-size:.85rem">
                        <button type="submit" class="btn-primary-custom" style="padding:10px 18px;font-size:.85rem">
                            <i class="bi bi-plus-lg"></i> Tambah
                        </button>
                    </form>
                    <div style="font-size:.72rem;color:var(--text-muted);margin-bottom:16px">Contoh nama jabatan: "Ka. BAA dan Beasiswa", "Ka. Prodi TRPL". Kosongkan untuk pakai label default (Kepala/Staff).</div>
                    @error('user_id')<div style="font-size:.78rem;color:#ef4444;margin:-12px 0 16px">{{ $message }}</div>@enderror

                    <!-- Daftar Kepala -->
                    <div style="font-size:.76rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.03em;margin-bottom:8px">
                        Kepala ({{ $unit->members->where('role', 'kepala')->count() }})
                    </div>
                    @forelse($unit->members->where('role', 'kepala') as $member)
                        @include('units.partials.member-row', ['member' => $member])
                    @empty
                        <div style="font-size:.8rem;color:var(--text-muted);padding:10px 0 16px">Belum ada Kepala di unit ini.</div>
                    @endforelse

                    <div style="font-size:.76rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:.03em;margin:18px 0 8px">
                        Staff ({{ $unit->members->where('role', 'staff')->count() }})
                    </div>
                    @forelse($unit->members->where('role', 'staff') as $member)
                        @include('units.partials.member-row', ['member' => $member])
                    @empty
                        <div style="font-size:.8rem;color:var(--text-muted);padding:10px 0">Belum ada Staff di unit ini.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Keluarkan Anggota -->
    <div x-show="showRemoveModal" x-transition.opacity style="position:fixed;inset:0;z-index:9999;display:none" @click.self="showRemoveModal = false">
        <div style="position:absolute;inset:0;background:rgba(15,23,42,.6);backdrop-filter:blur(6px)"></div>
        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;padding:20px">
            <div style="background:#fff;border-radius:20px;width:100%;max-width:400px;padding:28px;box-shadow:0 25px 60px rgba(0,0,0,.15)">
                <div style="font-size:1.05rem;font-weight:800;color:#1e293b;margin-bottom:8px">Keluarkan Anggota?</div>
                <div style="font-size:.86rem;color:#64748b;margin-bottom:20px">
                    <span x-text="removeName" style="font-weight:700;color:#ef4444"></span> akan dikeluarkan dari unit ini.
                </div>
                <div style="display:flex;gap:10px">
                    <button @click="showRemoveModal = false" style="flex:1;padding:10px;border-radius:10px;border:1.5px solid var(--border);background:#fff;font-size:.85rem;font-weight:600;cursor:pointer">Batal</button>
                    <button @click="confirmRemove()" style="flex:1;padding:10px;border-radius:10px;border:none;background:#ef4444;color:#fff;font-size:.85rem;font-weight:600;cursor:pointer">Ya, Keluarkan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Jabatan -->
    <div x-show="showJabatanModal" x-transition.opacity style="position:fixed;inset:0;z-index:9999;display:none" @click.self="showJabatanModal = false">
        <div style="position:absolute;inset:0;background:rgba(15,23,42,.6);backdrop-filter:blur(6px)"></div>
        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;padding:20px">
            <div style="background:#fff;border-radius:20px;width:100%;max-width:400px;padding:28px;box-shadow:0 25px 60px rgba(0,0,0,.15)">
                <div style="font-size:1.05rem;font-weight:800;color:#1e293b;margin-bottom:4px">Ubah Nama Jabatan</div>
                <div style="font-size:.86rem;color:#64748b;margin-bottom:18px">
                    Untuk <span x-text="jabatanMemberName" style="font-weight:700;color:#1e293b"></span>
                </div>
                <form method="POST" :action="jabatanFormAction">
                    @csrf @method('PUT')
                    <input type="text" name="jabatan" x-model="jabatanValue" placeholder="Contoh: Ka. Prodi TRPL"
                        style="width:100%;padding:10px 14px;border-radius:10px;border:1.5px solid var(--border);font-size:.86rem;margin-bottom:8px">
                    <div style="font-size:.72rem;color:var(--text-muted);margin-bottom:18px">Kosongkan untuk pakai label default (Kepala/Staff).</div>
                    <div style="display:flex;gap:10px">
                        <button type="button" @click="showJabatanModal = false" style="flex:1;padding:10px;border-radius:10px;border:1.5px solid var(--border);background:#fff;font-size:.85rem;font-weight:600;cursor:pointer">Batal</button>
                        <button type="submit" style="flex:1;padding:10px;border-radius:10px;border:none;background:var(--primary);color:#fff;font-size:.85rem;font-weight:600;cursor:pointer">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
