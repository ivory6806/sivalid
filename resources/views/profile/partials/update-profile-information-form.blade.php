<section>
    <div class="card-custom" style="overflow:hidden">
        <!-- Header Card -->
        <div style="padding:24px 28px; background:linear-gradient(135deg, var(--primary) 0%, #0d9488 100%); color:#fff; display:flex; align-items:center; gap:14px;">
            <div style="width:48px; height:48px; border-radius:12px; background:rgba(255,255,255,.15); backdrop-filter:blur(8px); display:flex; align-items:center; justify-content:center; border:1px solid rgba(255,255,255,.2)">
                <i class="bi bi-person-gear" style="font-size:1.3rem; color:#fff"></i>
            </div>
            <div>
                <h2 style="font-size:1.1rem; font-weight:800; margin:0; color:#fff">Informasi Profil</h2>
                <p style="font-size:.8rem; margin:4px 0 0; color:rgba(255,255,255,.75)">Perbarui nama dan alamat email akun Anda</p>
            </div>
        </div>

        <!-- Body Card -->
        <div style="padding:28px;">
            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <!-- Nama Lengkap -->
                <div style="margin-bottom:22px">
                    <label style="font-size:.82rem; font-weight:600; color:var(--text-dark); margin-bottom:8px; display:block">
                        Nama Lengkap <span style="color:#ef4444">*</span>
                    </label>
                    <div style="position:relative">
                        <i class="bi bi-person" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:.9rem"></i>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                            class="form-control @error('name') is-invalid @enderror"
                            style="border-radius:10px; padding:12px 16px 12px 42px; border:1.5px solid var(--border); font-size:.9rem; transition:all .2s; color:var(--text-dark)"
                            onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                            onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                    </div>
                    @error('name')
                    <div style="font-size:.78rem; color:#ef4444; margin-top:6px; display:flex; align-items:center; gap:4px">
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Email -->
                <div style="margin-bottom:28px">
                    <label style="font-size:.82rem; font-weight:600; color:var(--text-dark); margin-bottom:8px; display:block">
                        Alamat Email <span style="color:#ef4444">*</span>
                    </label>
                    <div style="position:relative">
                        <i class="bi bi-envelope" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:.9rem"></i>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                            class="form-control @error('email') is-invalid @enderror"
                            style="border-radius:10px; padding:12px 16px 12px 42px; border:1.5px solid var(--border); font-size:.9rem; transition:all .2s; color:var(--text-dark)"
                            onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                            onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'">
                    </div>
                    @error('email')
                    <div style="font-size:.78rem; color:#ef4444; margin-top:6px; display:flex; align-items:center; gap:4px">
                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                    </div>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div style="margin-top:12px; padding:14px 18px; background:#fffbeb; border:1px solid #fde68a; border-radius:10px; display:flex; align-items:center; gap:12px">
                        <i class="bi bi-exclamation-triangle-fill" style="font-size:1.1rem; color:#d97706; flex-shrink:0"></i>
                        <div style="flex:1; font-size:.84rem; color:#92400e">
                            Email Anda belum terverifikasi.
                            <button form="send-verification" type="button" style="background:none; border:none; color:var(--primary); font-weight:600; cursor:pointer; text-decoration:underline; padding:0; font-size:.84rem; font-family:inherit">
                                Kirim ulang email verifikasi.
                            </button>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                    <div style="margin-top:8px; padding:10px 16px; background:#ecfdf5; border:1px solid #a7f3d0; border-radius:8px; font-size:.82rem; color:#065f46; display:flex; align-items:center; gap:8px">
                        <i class="bi bi-check-circle-fill"></i> Tautan verifikasi baru telah dikirim ke email Anda.
                    </div>
                    @endif
                    @endif
                </div>

                <!-- Tombol Aksi -->
                <div style="display:flex; align-items:center; gap:16px">
                    <button type="submit" class="btn-primary-custom" style="padding:10px 24px; font-size:.88rem">
                        <i class="bi bi-check-lg me-1"></i> Simpan Perubahan
                    </button>

                    @if (session('status') === 'profile-updated')
                    <div id="successAlert" style="display:flex; align-items:center; gap:6px; font-size:.84rem; color:var(--primary); font-weight:600; opacity:1; transition: opacity .3s">
                        <i class="bi bi-check-circle-fill"></i> Data berhasil disimpan!
                    </div>
                    <script>
                        // Biar tulisan "Data berhasil disimpan!" hilang otomatis setelah 2 detik
                        setTimeout(() => {
                            const el = document.getElementById('successAlert');
                            if(el) el.style.opacity = '0';
                            setTimeout(() => { if(el) el.style.display = 'none'; }, 300);
                        }, 2000);
                    </script>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>