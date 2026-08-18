@extends('layouts.app')
@section('title', isset($user) ? 'Edit User' : 'Tambah User')
@section('pageTitle', isset($user) ? 'Edit User' : 'Tambah User Baru')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card-custom" style="overflow:visible;margin-bottom:-10px;position:relative;z-index:2">
            <div style="padding:32px 32px 24px;background:linear-gradient(135deg,var(--primary) 0%,#0d9488 100%);border-radius:14px 14px 0 0;text-align:center;position:relative;overflow:hidden">
                <div style="position:absolute;top:-20px;left:-20px;width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.08)"></div>
                <div style="position:absolute;bottom:-30px;right:-15px;width:60px;height:60px;border-radius:50%;background:rgba(255,255,255,.06)"></div>
                <div style="position:relative;z-index:1">
                    <div style="width:56px;height:56px;border-radius:14px;background:rgba(255,255,255,.15);backdrop-filter:blur(8px);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;border:1px solid rgba(255,255,255,.2)">
                        <i class="bi bi-{{ isset($user) ? 'pencil-square' : 'person-plus' }}" style="font-size:1.4rem;color:#fff"></i>
                    </div>
                    <div style="font-size:1.15rem;font-weight:800;color:#fff">{{ isset($user) ? 'Edit Data User' : 'Tambah User Baru' }}</div>
                    <div style="font-size:.8rem;color:rgba(255,255,255,.7);margin-top:3px">{{ isset($user) ? 'Perbarui informasi akun pengguna sistem' : 'Isi form berikut untuk membuat akun baru' }}</div>
                </div>
            </div>

            <div style="padding:28px 32px 32px;margin-top:1px;background:var(--card-bg);border-radius:0 0 14px 14px;border:1px solid var(--border);border-top:none">
                <form method="POST" action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" id="userForm">
                    @csrf @if(isset($user)) @method('PUT') @endif

                    <!-- Nama -->
                    <div style="margin-bottom:20px">
                        <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">
                            Nama Lengkap <span style="color:#ef4444">*</span>
                        </label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', isset($user) ? $user->name : '') }}" required placeholder="Masukkan nama lengkap"
                            style="border-radius:10px;padding:12px 16px;border:1.5px solid var(--border);font-size:.9rem;transition:all .2s;color:var(--text-dark)"
                            onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                            onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                        @error('name')<div style="font-size:.78rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <!-- Email -->
                    <div style="margin-bottom:20px">
                        <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">
                            Email <span style="color:#ef4444">*</span>
                        </label>
                        <div style="position:relative">
                            <i class="bi bi-envelope" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.9rem"></i>
                            <input type="email" name="email" class="form-control" value="{{ old('email', isset($user) ? $user->email : '') }}" required placeholder="contoh@email.com"
                                style="border-radius:10px;padding:12px 16px 12px 42px;border:1.5px solid var(--border);font-size:.9rem;transition:all .2s;color:var(--text-dark)"
                                onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                        </div>
                        @error('email')<div style="font-size:.78rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <!-- Password -->
                    <div style="margin-bottom:20px">
                        <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">
                            Password
                            @if(!isset($user))<span style="color:#ef4444">*</span>@endif
                            @if(isset($user))<span style="font-size:.72rem;color:var(--text-muted);font-weight:400;margin-left:6px">(kosongkan jika tidak diubah)</span>@endif
                        </label>
                        <div style="position:relative">
                            <i class="bi bi-lock" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.9rem"></i>
                            <input type="password" name="password" class="form-control" id="pwdInput" {{ !isset($user) ? 'required' : '' }} autocomplete="new-password" placeholder="{{ isset($user) ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' }}"
                                style="border-radius:10px;padding:12px 16px 12px 42px;border:1.5px solid var(--border);font-size:.9rem;transition:all .2s;color:var(--text-dark)"
                                onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                            <button type="button" onclick="togglePwd()" style="position:absolute;right:4px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--text-muted);cursor:pointer;padding:4px 8px" id="pwdToggle">
                                <i class="bi bi-eye" id="pwdIcon"></i>
                            </button>
                        </div>
                        @error('password')<div style="font-size:.78rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    @if(!isset($user))
                    <div style="margin-bottom:20px">
                        <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">
                            Konfirmasi Password <span style="color:#ef4444">*</span>
                        </label>
                        <div style="position:relative">
                            <i class="bi bi-lock-fill" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.9rem"></i>
                            <input type="password" name="password_confirmation" class="form-control" required placeholder="Ulangi password di atas"
                                style="border-radius:10px;padding:12px 16px 12px 42px;border:1.5px solid var(--border);font-size:.9rem;transition:all .2s;color:var(--text-dark)"
                                onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                        </div>
                    </div>
                    @endif

                    <!-- Role & NIP -->
                    <div class="row g-3" style="margin-bottom:20px">
                        <div class="col-md-6">
                            <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">
                                Role <span style="color:#ef4444">*</span>
                            </label>

                            <!-- ===== CUSTOM DROPDOWN (menggunakan pola dd-component) ===== -->
                            <div x-data="{
                                selected: '{{ old('role', isset($user) ? $user->role : '') }}',
                                open: false,
                                options: [
                                    { value: 'staff',  label: 'Staff Administrasi',     icon: 'bi-person' },
                                    { value: 'kepala', label: 'Kepala BAA & Beasiswa',  icon: 'bi-person-badge' }
                                ],
                                get label() {
                                    var o = this.options.find(i => i.value === this.selected);
                                    return o ? o.label : '-- Pilih Role --';
                                },
                                get icon() {
                                    var o = this.options.find(i => i.value === this.selected);
                                    return o ? o.icon : 'bi-person-badge';
                                },
                                pick(val) {
                                    this.selected = val;
                                    this.open = false;
                                }
                            }" @click.outside="open = false" style="position:relative">

                                <!-- hidden input untuk submit -->
                                <input type="hidden" name="role" :value="selected">

                                <!-- trigger -->
                                <div @click="open = !open"
                                     class="dd-trigger"
                                     :aria-expanded="open.toString()"
                                     style="padding:12px 36px 12px 42px;font-size:.9rem;width:100%">
                                    <i class="bi" :class="[icon, selected ? 'dd-icon-active' : 'dd-icon-muted']"
                                       style="position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:.9rem;transition:color .2s"></i>
                                    <span x-text="label" :class="selected ? '' : 'dd-placeholder'"></span>
                                </div>

                                <!-- panel -->
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute z-50 mt-2 w-full dd-panel"
                                     style="display:none;">
                                    <div class="dd-body">
                                        <template x-for="opt in options" :key="opt.value">
                                            <a href="#" @click.prevent="pick(opt.value)" :class="{ 'active': selected === opt.value }">
                                                <i class="bi" :class="opt.icon"></i>
                                                <span x-text="opt.label"></span>
                                                <i x-show="selected === opt.value" class="bi bi-check-lg" style="margin-left:auto"></i>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                            <!-- ===== END CUSTOM DROPDOWN ===== -->

                            @error('role')<div style="font-size:.78rem;color:#ef4444;margin-top:6px;display:flex;align-items:center;gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">NIP</label>
                            <div style="position:relative">
                                <i class="bi bi-card-text" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.9rem"></i>
                                <input type="text" name="nip" class="form-control" value="{{ old('nip', isset($user) ? $user->nip : '') }}" placeholder="Nomor Induk Pegawai"
                                    style="border-radius:10px;padding:12px 16px 12px 42px;border:1.5px solid var(--border);font-size:.9rem;transition:all .2s;color:var(--text-dark)"
                                    onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                    onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                            </div>
                        </div>
                    </div>

                    <!-- Jabatan -->
                    <div style="margin-bottom:24px">
                        <label style="font-size:.82rem;font-weight:600;color:var(--text-dark);margin-bottom:8px;display:block">Jabatan</label>
                        <div style="position:relative">
                            <i class="bi bi-briefcase" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-muted);font-size:.9rem"></i>
                            <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', isset($user) ? $user->jabatan : '') }}" placeholder="Jabatan di lingkungan kampus"
                                style="border-radius:10px;padding:12px 16px 12px 42px;border:1.5px solid var(--border);font-size:.9rem;transition:all .2s;color:var(--text-dark)"
                                onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                        </div>
                    </div>

                    @if(isset($user))
                    <div style="margin-bottom:24px;padding:16px 18px;background:#f8fafc;border-radius:10px;border:1px solid var(--border)">
                        <div style="display:flex;align-items:center;justify-content:space-between">
                            <div>
                                <div style="font-size:.88rem;font-weight:600;color:var(--text-dark)">Status User</div>
                                <div style="font-size:.76rem;color:var(--text-muted);margin-top:2px">{{ $user->is_active ? 'User dapat login dan menggunakan sistem' : 'User tidak dapat login ke sistem' }}</div>
                            </div>
                            <div class="form-check form-switch" style="margin:0">
                                <input class="form-check-input" type="checkbox" name="is_active" id="isActive" {{ $user->is_active ? 'checked' : '' }} value="1"
                                    style="width:48px;height:26px;cursor:pointer">
                                <label class="form-check-label" for="isActive" style="margin-left:4px"></label>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Buttons -->
                    <div style="display:flex;gap:10px">
                        <button type="submit" class="btn-primary-custom" style="padding:12px 28px;font-size:.92rem;flex:1">
                            <i class="bi bi-{{ isset($user) ? 'check-lg' : 'plus-lg' }}"></i> {{ isset($user) ? 'Simpan Perubahan' : 'Buat User' }}
                        </button>
                        <a href="{{ route('users.index') }}" class="btn-outline-custom" style="padding:12px 24px;font-size:.92rem">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== DROPDOWN COMPONENT STYLES ===== */
/* Hapus blok ini jika CSS-nya sudah di-load dari component dd-wrapper lain di halaman yang sama */

/* Trigger */
.dd-trigger {
    display: flex;
    align-items: center;
    gap: 8px;
    border-radius: 10px;
    border: 1.5px solid var(--border);
    background: var(--card-bg);
    cursor: pointer;
    transition: all 0.2s;
    color: var(--text-dark);
    font-weight: 500;
    white-space: nowrap;
    user-select: none;
    position: relative;
}
.dd-trigger::after {
    content: '';
    position: absolute;
    right: 12px;
    top: 50%;
    width: 6px;
    height: 6px;
    border-right: 2px solid var(--text-muted);
    border-bottom: 2px solid var(--text-muted);
    transform: translateY(-50%) rotate(45deg);
    transition: transform 0.2s;
}
.dd-trigger:hover {
    border-color: var(--primary);
    background: var(--primary-light);
}
.dd-trigger:hover::after {
    border-color: var(--primary);
    transform: translateY(-50%) rotate(225deg);
}
.dd-trigger[aria-expanded="true"] {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(5,150,105,.1);
}
.dd-trigger[aria-expanded="true"]::after {
    border-color: var(--primary);
    transform: translateY(-50%) rotate(-45deg);
}

/* Icon states */
.dd-icon-muted  { color: var(--text-muted); }
.dd-icon-active { color: var(--primary); }

/* Placeholder text */
.dd-placeholder { color: var(--text-muted); font-weight: 400; }

/* Panel */
.dd-panel {
    background: #fff;
    border-radius: 14px;
    border: 1px solid var(--border);
    box-shadow: 0 20px 50px rgba(0,0,0,.08), 0 4px 15px rgba(0,0,0,.03);
    overflow: hidden;
}
.dd-body { padding: 6px; }

/* Menu items */
.dd-body a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 8px;
    color: var(--text-dark);
    font-size: .86rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.15s;
}
.dd-body a:hover {
    background: var(--primary-light);
    color: var(--primary);
    font-weight: 600;
}
.dd-body a:active {
    background: #d1fae5;
}
.dd-body a.active {
    background: var(--primary);
    color: #fff;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(5,150,105,.2);
}
.dd-body a.active i {
    color: #fff !important;
}
</style>

<script>
function togglePwd() {
    const i = document.getElementById('pwdInput');
    const ic = document.getElementById('pwdIcon');
    if (i.type === 'password') {
        i.type = 'text';
        ic.className = 'bi bi-eye-slash';
    } else {
        i.type = 'password';
        ic.className = 'bi bi-eye';
    }
}
</script>
@endsection