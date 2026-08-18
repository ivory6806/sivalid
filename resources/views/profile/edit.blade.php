@extends('layouts.app')

@section('title', 'Profil Saya')
@section('pageTitle', 'Profil Saya')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        
        <!-- Card Update Profil -->
        <div class="card-custom" style="overflow:hidden; margin-bottom: 24px;">
            <div style="padding:24px 28px; background:linear-gradient(135deg, var(--primary) 0%, #0d9488 100%); color:#fff; display:flex; align-items:center; gap:14px;">
                <div style="width:48px; height:48px; border-radius:12px; background:rgba(255,255,255,.15); backdrop-filter:blur(8px); display:flex; align-items:center; justify-content:center; border:1px solid rgba(255,255,255,.2)">
                    <i class="bi bi-person-gear" style="font-size:1.3rem; color:#fff"></i>
                </div>
                <div>
                    <h2 style="font-size:1.1rem; font-weight:800; margin:0; color:#fff">Informasi Profil</h2>
                    <p style="font-size:.8rem; margin:4px 0 0; color:rgba(255,255,255,.75)">Perbarui nama dan alamat email akun Anda</p>
                </div>
            </div>

            <div style="padding:28px;">
                <form method="post" action="{{ route('profile.update') }}">
                    @csrf @method('patch')

                    <!-- Nama -->
                    <div style="margin-bottom:22px">
                        <label style="font-size:.82rem; font-weight:600; color:var(--text-dark); margin-bottom:8px; display:block">Nama Lengkap <span style="color:#ef4444">*</span></label>
                        <div style="position:relative">
                            <i class="bi bi-person" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:.9rem"></i>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                                class="form-control @error('name') is-invalid @enderror"
                                style="border-radius:10px; padding:12px 16px 12px 42px; border:1.5px solid var(--border); font-size:.9rem; transition:all .2s; color:var(--text-dark)"
                                onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                        </div>
                        @error('name')<div style="font-size:.78rem; color:#ef4444; margin-top:6px; display:flex; align-items:center; gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <!-- Email -->
                    <div style="margin-bottom:28px">
                        <label style="font-size:.82rem; font-weight:600; color:var(--text-dark); margin-bottom:8px; display:block">Alamat Email <span style="color:#ef4444">*</span></label>
                        <div style="position:relative">
                            <i class="bi bi-envelope" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:.9rem"></i>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                                class="form-control @error('email') is-invalid @enderror"
                                style="border-radius:10px; padding:12px 16px 12px 42px; border:1.5px solid var(--border); font-size:.9rem; transition:all .2s; color:var(--text-dark)"
                                onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                        </div>
                        @error('email')<div style="font-size:.78rem; color:#ef4444; margin-top:6px; display:flex; align-items:center; gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn-primary-custom" style="padding:10px 24px; font-size:.88rem">
                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        <!-- Card Update Password -->
        <div class="card-custom" style="overflow:hidden;">
            <div style="padding:24px 28px; background:linear-gradient(135deg, #1e293b 0%, #334155 100%); color:#fff; display:flex; align-items:center; gap:14px;">
                <div style="width:48px; height:48px; border-radius:12px; background:rgba(255,255,255,.1); backdrop-filter:blur(8px); display:flex; align-items:center; justify-content:center; border:1px solid rgba(255,255,255,.15)">
                    <i class="bi bi-shield-lock" style="font-size:1.3rem; color:#fff"></i>
                </div>
                <div>
                    <h2 style="font-size:1.1rem; font-weight:800; margin:0; color:#fff">Ubah Password</h2>
                    <p style="font-size:.8rem; margin:4px 0 0; color:rgba(255,255,255,.75)">Kosongkan jika tidak ingin mengubah password</p>
                </div>
            </div>

            <div style="padding:28px;">
                <form method="post" action="{{ route('password.update') }}">
                    @csrf @method('put')

                    <div style="margin-bottom:22px">
                        <label style="font-size:.82rem; font-weight:600; color:var(--text-dark); margin-bottom:8px; display:block">Password Saat Ini <span style="color:#ef4444">*</span></label>
                        <div style="position:relative">
                            <i class="bi bi-lock" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:.9rem"></i>
                            <input type="password" name="current_password" required autocomplete="current-password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                style="border-radius:10px; padding:12px 16px 12px 42px; border:1.5px solid var(--border); font-size:.9rem; transition:all .2s; color:var(--text-dark)"
                                onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'"
                                placeholder="Masukkan password lama">
                        </div>
                        @error('current_password')<div style="font-size:.78rem; color:#ef4444; margin-top:6px; display:flex; align-items:center; gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div style="margin-bottom:22px">
                        <label style="font-size:.82rem; font-weight:600; color:var(--text-dark); margin-bottom:8px; display:block">Password Baru</label>
                        <div style="position:relative">
                            <i class="bi bi-key" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:.9rem"></i>
                            <input type="password" name="password" autocomplete="new-password"
                                class="form-control @error('password') is-invalid @enderror"
                                style="border-radius:10px; padding:12px 16px 12px 42px; border:1.5px solid var(--border); font-size:.9rem; transition:all .2s; color:var(--text-dark)"
                                onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'"
                                placeholder="Minimal 8 karakter">
                        </div>
                        @error('password')<div style="font-size:.78rem; color:#ef4444; margin-top:6px; display:flex; align-items:center; gap:4px"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>@enderror
                    </div>

                    <div style="margin-bottom:28px">
                        <label style="font-size:.82rem; font-weight:600; color:var(--text-dark); margin-bottom:8px; display:block">Konfirmasi Password Baru</label>
                        <div style="position:relative">
                            <i class="bi bi-key-fill" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:.9rem"></i>
                            <input type="password" name="password_confirmation" autocomplete="new-password"
                                class="form-control"
                                style="border-radius:10px; padding:12px 16px 12px 42px; border:1.5px solid var(--border); font-size:.9rem; transition:all .2s; color:var(--text-dark)"
                                onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                                onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'"
                                placeholder="Ulangi password baru">
                        </div>
                    </div>

                    <button type="submit" class="btn-primary-custom" style="padding:10px 24px; font-size:.88rem; background:linear-gradient(135deg, #1e293b, #334155);">
                        <i class="bi bi-shield-check me-1"></i> Ubah Password
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection