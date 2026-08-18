@extends('layouts.app')
@section('title', 'Pengaturan')
@section('pageTitle', 'Pengaturan Sistem')
@section('content')

<style>
    .item-text {
        flex: 1;
        font-size: .84rem;
        font-weight: 500;
        color: var(--text-dark);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .spin { animation: spin 0.6s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>

<div x-data="settingManager()">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px">
        <div>
            <div style="font-size:1.1rem;font-weight:700;color:var(--text-dark)">Pengaturan Dropdown</div>
            <div style="font-size:.82rem;color:var(--text-muted);margin-top:2px">Kelola opsi Prodi, Semester, dan Tahun Ajaran</div>
        </div>
    </div>

    <div class="row g-4">
        <!-- ===== PRODI ===== -->
        <div class="col-lg-4">
            <div class="card-custom">
                <div style="padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px">
                    <div style="width:34px;height:34px;border-radius:9px;background:var(--primary-light);display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-mortarboard-fill" style="color:var(--primary);font-size:.95rem"></i>
                    </div>
                    <div>
                        <div style="font-size:.9rem;font-weight:700;color:var(--text-dark)">Program Studi</div>
                        <div style="font-size:.7rem;color:var(--text-muted)">{{ count($prodiList) }} opsi</div>
                    </div>
                </div>
                <div style="padding:16px 22px">
                    <div style="display:flex;gap:6px;margin-bottom:14px">
                        <input type="text" id="input-prodi"
                            placeholder="Tambah prodi baru..."
                            style="flex:1;border-radius:8px;padding:9px 14px;border:1.5px solid var(--border);font-size:.84rem;transition:all .2s;color:var(--text-dark)"
                            onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                            onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'"
                            onkeydown="if(event.key==='Enter'){event.preventDefault();addItem('master_prodi', this.value, event)}">
                        <button onclick="addItem('master_prodi', document.getElementById('input-prodi').value, event)"
                            style="padding:9px 14px;border-radius:8px;border:none;background:var(--primary);color:#fff;cursor:pointer;transition:all .15s;flex-shrink:0"
                            onmouseover="this.style.background='var(--primary-dark)'" onmouseout="this.style.background='var(--primary)'">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:4px;max-height:320px;overflow-y:auto">
                        @foreach($prodiList as $p)
                        <div class="item-row" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;background:#f8fafc;transition:all .15s"
                             onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                            <i class="bi bi-mortarboard" style="color:var(--text-muted);font-size:.8rem; flex-shrink:0"></i>
                            <span class="item-text" title="{{ $p }}">{{ $p }}</span>
                            <button type="button" onclick="removeItem('master_prodi', '{{ $p }}', this, event)"
                                style="width:26px;height:26px;border-radius:6px;border:none;background:transparent;color:#94a3b8;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s; flex-shrink:0"
                                onmouseover="this.style.background='#fee2e2';this.style.color='#ef4444'" onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">
                                <i class="bi bi-x" style="font-size:.75rem"></i>
                            </button>
                        </div>
                        @endforeach
                        @if(empty($prodiList))
                        <div style="text-align:center;padding:24px 0;color:var(--text-muted);font-size:.84rem">Belum ada opsi</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== SEMESTER ===== -->
        <div class="col-lg-4">
            <div class="card-custom">
                <div style="padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px">
                    <div style="width:34px;height:34px;border-radius:9px;background:#eff6ff;display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-book-fill" style="color:#3b82f6;font-size:.95rem"></i>
                    </div>
                    <div>
                        <div style="font-size:.9rem;font-weight:700;color:var(--text-dark)">Semester</div>
                        <div style="font-size:.7rem;color:var(--text-muted)">{{ count($semesterList) }} opsi</div>
                    </div>
                </div>
                <div style="padding:16px 22px">
                    <div style="display:flex;gap:6px;margin-bottom:14px">
                        <input type="text" id="input-semester"
                            placeholder="Tambah semester baru..."
                            style="flex:1;border-radius:8px;padding:9px 14px;border:1.5px solid var(--border);font-size:.84rem;transition:all .2s;color:var(--text-dark)"
                            onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                            onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'"
                            onkeydown="if(event.key==='Enter'){event.preventDefault();addItem('master_semester', this.value, event)}">
                        <button onclick="addItem('master_semester', document.getElementById('input-semester').value, event)"
                            style="padding:9px 14px;border-radius:8px;border:none;background:var(--primary);color:#fff;cursor:pointer;transition:all .15s;flex-shrink:0"
                            onmouseover="this.style.background='var(--primary-dark)'" onmouseout="this.style.background='var(--primary)'">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:4px;max-height:320px;overflow-y:auto">
                        @foreach($semesterList as $s)
                        <div class="item-row" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;background:#f8fafc;transition:all .15s"
                             onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                            <i class="bi bi-book" style="color:#3b82f6;font-size:.8rem; flex-shrink:0"></i>
                            <span class="item-text" title="Semester {{ $s }}">Semester {{ $s }}</span>
                            <button type="button" onclick="removeItem('master_semester', '{{ $s }}', this, event)"
                                style="width:26px;height:26px;border-radius:6px;border:none;background:transparent;color:#94a3b8;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s; flex-shrink:0"
                                onmouseover="this.style.background='#fee2e2';this.style.color='#ef4444'" onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">
                                <i class="bi bi-x" style="font-size:.75rem"></i>
                            </button>
                        </div>
                        @endforeach
                        @if(empty($semesterList))
                        <div style="text-align:center;padding:24px 0;color:var(--text-muted);font-size:.84rem">Belum ada opsi</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== TAHUN AJARAN ===== -->
        <div class="col-lg-4">
            <div class="card-custom">
                <div style="padding:18px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:10px">
                    <div style="width:34px;height:34px;border-radius:9px;background:#fef3c7;display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-calendar3-fill" style="color:#d97706;font-size:.95rem"></i>
                    </div>
                    <div>
                        <div style="font-size:.9rem;font-weight:700;color:var(--text-dark)">Tahun Ajaran</div>
                        <div style="font-size:.7rem;color:var(--text-muted)">{{ count($taList) }} opsi</div>
                    </div>
                </div>
                <div style="padding:16px 22px">
                    <div style="display:flex;gap:6px;margin-bottom:14px">
                        <input type="text" id="input-ta"
                            placeholder="Contoh: 2025/2026"
                            style="flex:1;border-radius:8px;padding:9px 14px;border:1.5px solid var(--border);font-size:.84rem;transition:all .2s;color:var(--text-dark)"
                            onfocus="this.style.borderColor='var(--primary)';this.style.boxShadow='0 0 0 3px rgba(5,150,105,.1)'"
                            onblur="this.style.borderColor='var(--border)';this.style.boxShadow='none'"
                            onkeydown="if(event.key==='Enter'){event.preventDefault();addItem('master_tahun_ajaran', this.value, event)}">
                        <button onclick="addItem('master_tahun_ajaran', document.getElementById('input-ta').value, event)"
                            style="padding:9px 14px;border-radius:8px;border:none;background:var(--primary);color:#fff;cursor:pointer;transition:all .15s;flex-shrink:0"
                            onmouseover="this.style.background='var(--primary-dark)'" onmouseout="this.style.background='var(--primary)'">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                    <div style="display:flex;flex-direction:column;gap:4px;max-height:320px;overflow-y:auto">
                        @foreach($taList as $t)
                        <div class="item-row" style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;background:#f8fafc;transition:all .15s"
                             onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                            <i class="bi bi-calendar3" style="color:#d97706;font-size:.8rem; flex-shrink:0"></i>
                            <span class="item-text" title="{{ $t }}">{{ $t }}</span>
                            <button type="button" onclick="removeItem('master_tahun_ajaran', '{{ $t }}', this, event)"
                                style="width:26px;height:26px;border-radius:6px;border:none;background:transparent;color:#94a3b8;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s; flex-shrink:0"
                                onmouseover="this.style.background='#fee2e2';this.style.color='#ef4444'" onmouseout="this.style.background='transparent';this.style.color='#94a3b8'">
                                <i class="bi bi-x" style="font-size:.75rem"></i>
                            </button>
                        </div>
                        @endforeach
                        @if(empty($taList))
                        <div style="text-align:center;padding:24px 0;color:var(--text-muted);font-size:.84rem">Belum ada opsi</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
console.log('⚠️ SCRIPT START');

// Test 1: Basic
console.log('CSRF meta:', document.querySelector('meta[name="csrf-token"]'));
console.log('Route URL:', '{{ route("settings.add") }}');

window.settingManager = function() {
    return { init() { console.log('Alpine init'); } };
};

window.addItem = async function(key, value, e) {
    console.log('🟢 addItem() called with:', key, value);
    
    if (!value || !value.trim()) {
        console.log('🔴 Value kosong, return');
        return;
    }
    value = value.trim();

    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (!token) {
        console.error('🔴 CSRF TOKEN TIDAK ADA!');
        alert('CSRF token tidak ditemukan!');
        return;
    }

    const url = '{{ route("settings.add") }}';
    console.log('🟡 Mengirim ke:', url);

    const fd = new FormData();
    fd.append('key', key);
    fd.append('value', value);
    fd.append('_token', token);

    try {
        const res = await fetch(url, { method: 'POST', body: fd });
        console.log('🟡 Status:', res.status);
        
        const json = await res.json();
        console.log('🟡 Response:', json);

        if (res.ok && json.success) {
            console.log('🟢 SUCCESS, reload...');
            window.location.reload();
        } else {
            console.log('🔴 FAILED:', json.message);
            alert(json.message || 'Gagal');
        }
    } catch(err) {
        console.error('🔴 ERROR:', err);
        alert('Error: ' + err.message);
    }
};

window.removeItem = async function(key, value, btnEl, e) {
    console.log('🟢 removeItem() called with:', key, value);
    
    if (!confirm('Hapus opsi ini?')) return;

    const row = btnEl?.closest('.item-row');
    if (row) row.style.opacity = '0.4';

    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    const url = '{{ route("settings.remove") }}';

    const fd = new FormData();
    fd.append('key', key);
    fd.append('value', value);
    fd.append('_token', token);

    try {
        const res = await fetch(url, { method: 'POST', body: fd });
        console.log('🟡 Status:', res.status);
        
        const json = await res.json();
        console.log('🟡 Response:', json);

        if (res.ok && json.success) {
            console.log('🟢 SUCCESS');
            window.location.reload();
        } else {
            if (row) row.style.opacity = '1';
            alert(json.message || 'Gagal');
        }
    } catch(err) {
        console.error('🔴 ERROR:', err);
        if (row) row.style.opacity = '1';
        alert('Error: ' + err.message);
    }
};

console.log('⚠️ SCRIPT END');
</script>
@endsection