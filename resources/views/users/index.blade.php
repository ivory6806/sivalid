@extends('layouts.app')
@section('title', 'Kelola User')
@section('pageTitle', 'Kelola User')
@section('content')

<div x-data="{
    showDeleteModal: false,
    deleteName: '',
    deleteFormId: '',
    confirmDelete() {
        document.getElementById(this.deleteFormId).submit();
    },
    askDelete(name, formId) {
        this.deleteName = name;
        this.deleteFormId = formId;
        this.showDeleteModal = true;
    }
}" @keydown.escape.window="showDeleteModal = false">

    <!-- Header -->
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px">
        <div>
            <div style="font-size:1.1rem;font-weight:700;color:var(--text-dark)">{{ $users->count() }} User Terdaftar</div>
            <div style="font-size:.82rem;color:var(--text-muted);margin-top:2px">Kelola akun staff dan kepala staff</div>
        </div>
        <a href="{{ route('users.create') }}" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Tambah User
        </a>
    </div>

    <!-- Filter Chips -->
    <div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap">
        <span style="padding:6px 14px;border-radius:8px;background:var(--sidebar-bg);color:#e2e8f0;font-size:.78rem;font-weight:600;cursor:pointer" onclick="filterUsers('all')" id="chip-all">
            <i class="bi bi-grid-3x3-gap me-1"></i> Semua ({{ $users->count() }})
        </span>
        @php
            $staffCount = $users->where('role','staff')->count();
            $kepalaCount = $users->where('role','kepala')->count();
        @endphp
        <span style="padding:6px 14px;border-radius:8px;background:#f1f5f9;color:var(--text-muted);font-size:.78rem;font-weight:600;cursor:pointer" onclick="filterUsers('staff')" id="chip-staff">
            <i class="bi bi-person me-1"></i> Staff ({{ $staffCount }})
        </span>
        <span style="padding:6px 14px;border-radius:8px;background:#f1f5f9;color:var(--text-muted);font-size:.78rem;font-weight:600;cursor:pointer" onclick="filterUsers('kepala')" id="chip-kepala">
            <i class="bi bi-person-badge me-1"></i> Kepala ({{ $kepalaCount }})
        </span>
    </div>

    <!-- User Cards -->
    @forelse($users as $u)
    <div class="card-custom mb-3 user-row" data-role="{{ $u->role }}" style="transition:all .2s">
        <div style="padding:18px 22px;display:flex;align-items:center;gap:16px">
            <!-- Avatar -->
            <div style="width:50px;height:50px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;flex-shrink:0;
                {{ $u->is_active
                    ? ($u->role === 'kepala' ? 'background:linear-gradient(135deg,#ecfdf5,#d1fae5);color:#059669' : 'background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#3b82f6')
                    : 'background:#f1f5f9;color:#94a3b8'
                }}">
                {{ strtoupper(substr($u->name, 0, 1)) }}
            </div>

            <!-- Info -->
            <div style="flex:1;min-width:0">
                <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <span style="font-size:.92rem;font-weight:700;color:var(--text-dark)">{{ $u->name }}</span>
                    @if($u->is_active)
                        <span style="padding:2px 8px;border-radius:6px;background:#ecfdf5;color:#059669;font-size:.65rem;font-weight:600">AKTIF</span>
                    @else
                        <span style="padding:2px 8px;border-radius:6px;background:#f1f5f9;color:#94a3b8;font-size:.65rem;font-weight:600">NONAKTIF</span>
                    @endif
                </div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px">{{ $u->jabatan }}</div>
                <div style="display:flex;gap:16px;margin-top:6px;flex-wrap:wrap">
                    <span style="font-size:.76rem;color:var(--text-muted);display:flex;align-items:center;gap:4px">
                        <i class="bi bi-envelope" style="font-size:.7rem"></i>{{ $u->email }}
                    </span>
                    @if($u->nip)
                    <span style="font-size:.76rem;color:var(--text-muted);display:flex;align-items:center;gap:4px">
                        <i class="bi bi-card-text" style="font-size:.7rem"></i>{{ $u->nip }}
                    </span>
                    @endif
                </div>
            </div>

            <!-- Role Badge -->
            <div style="flex-shrink:0;margin-right:8px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px">
                <div style="width:56px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;
                    {{ $u->role === 'kepala' ? 'background:#ecfdf5;color:#059669' : 'background:#eff6ff;color:#3b82f6' }}">
                    <i class="{{ $u->role === 'kepala' ? 'bi bi-person-badge' : 'bi bi-person' }}" style="font-size:.85rem"></i>
                </div>
                <span style="font-size:.66rem;font-weight:600;color:var(--text-muted);white-space:nowrap">{{ $u->role_label }}</span>
            </div>

            <!-- Actions -->
            <div style="display:flex;gap:6px;flex-shrink:0">
                <a href="{{ route('users.edit', $u) }}" class="btn-outline-custom" style="padding:8px 10px" title="Edit User">
                    <i class="bi bi-pencil"></i>
                </a>
                <button type="button" class="btn-outline-custom" style="padding:8px 10px;border-color:#fecaca;color:#ef4444" title="Hapus User"
                    onmouseover="this.style.background='#fef2f2';this.style.borderColor='#fca5a5'"
                    onmouseout="this.style.background='transparent';this.style.borderColor='#fecaca'"
                    @click="askDelete('{{ addslashes($u->name) }}', 'delete-form-{{ $u->id }}')">
                    <i class="bi bi-trash"></i>
                </button>
                <form method="POST" action="{{ route('users.destroy', $u) }}" id="delete-form-{{ $u->id }}" style="display:none">
                    @csrf @method('DELETE')
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="card-custom">
        <div style="padding:60px 22px;text-align:center">
            <div style="width:64px;height:64px;border-radius:16px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
                <i class="bi bi-people" style="font-size:1.6rem;color:#cbd5e1"></i>
            </div>
            <div style="font-size:.92rem;font-weight:600;color:var(--text-muted)">Belum ada user</div>
            <div style="font-size:.82rem;color:#94a3b8;margin-top:4px">Tambahkan user pertama untuk mulai menggunakan sistem.</div>
            <a href="{{ route('users.create') }}" class="btn-primary-custom" style="margin-top:16px;display:inline-flex">
                <i class="bi bi-plus-lg"></i> Tambah User
            </a>
        </div>
    </div>
    @endforelse

    <!-- ============================== -->
    <!-- ===== MODAL HAPUS USER ======= -->
    <!-- ============================== -->
    <div x-show="showDeleteModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="position:fixed;inset:0;z-index:9999;display:none;"
         @click.self="showDeleteModal = false">

        <!-- Overlay -->
        <div style="position:absolute;inset:0;background:rgba(15,23,42,.6);backdrop-filter:blur(6px);-webkit-backdrop-filter:blur(6px)"></div>

        <!-- Modal Card -->
        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;padding:20px">
            <div x-show="showDeleteModal"
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-90 translate-y-4"
                 style="background:#fff;border-radius:20px;width:100%;max-width:420px;overflow:hidden;position:relative;
                        box-shadow:0 25px 60px rgba(0,0,0,.15),0 0 0 1px rgba(0,0,0,.05)">

                <!-- Top danger bar -->
                <div style="height:4px;background:linear-gradient(90deg,#ef4444,#f97316,#ef4444);background-size:200% 100%;animation:dangerShimmer 2s linear infinite"></div>

                <!-- Close button -->
                <button @click="showDeleteModal = false"
                    style="position:absolute;top:16px;right:16px;width:32px;height:32px;border-radius:8px;border:none;background:#f1f5f9;color:var(--text-muted);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;z-index:2"
                    onmouseover="this.style.background='#fee2e2';this.style.color='#ef4444'"
                    onmouseout="this.style.background='#f1f5f9';this.style.color='var(--text-muted)'">
                    <i class="bi bi-x-lg" style="font-size:.75rem"></i>
                </button>

                <!-- Content -->
                <div style="padding:36px 32px 12px;text-align:center">
                    <!-- Animated icon -->
                    <div style="position:relative;width:72px;height:72px;margin:0 auto 20px">
                        <!-- Pulse ring -->
                        <div style="position:absolute;inset:-6px;border-radius:50%;border:2px solid #fecaca;animation:modalPulse 1.8s ease-out infinite"></div>
                        <!-- BG circle -->
                        <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#fef2f2 0%,#fee2e2 100%);display:flex;align-items:center;justify-content:center;position:relative">
                            <!-- Trash icon -->
                            <i class="bi bi-trash3" style="font-size:1.6rem;color:#ef4444"></i>
                            <!-- Warning badge -->
                            <div style="position:absolute;top:-2px;right:-2px;width:24px;height:24px;border-radius:50%;background:#ef4444;display:flex;align-items:center;justify-content:center;border:2.5px solid #fff;box-shadow:0 2px 6px rgba(239,68,68,.3)">
                                <i class="bi bi-exclamation" style="font-size:.65rem;color:#fff;font-weight:900"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Text -->
                    <div style="font-size:1.1rem;font-weight:800;color:#1e293b;margin-bottom:6px;letter-spacing:-.02em">Hapus User?</div>
                    <div style="font-size:.88rem;color:#64748b;line-height:1.6;max-width:300px;margin:0 auto">
                        Anda akan menghapus
                        <span x-text="deleteName" style="font-weight:700;color:#ef4444;background:#fef2f2;padding:2px 8px;border-radius:6px;font-size:.84rem"></span>
                        dari sistem. Tindakan ini <strong style="color:#1e293b">tidak dapat dibatalkan</strong>.
                    </div>
                </div>

                <!-- Warning box -->
                <div style="margin:0 32px 20px;padding:12px 16px;border-radius:10px;background:#fffbeb;border:1px solid #fde68a;display:flex;align-items:flex-start;gap:10px">
                    <i class="bi bi-triangle-exclamation" style="color:#d97706;font-size:.9rem;margin-top:1px;flex-shrink:0"></i>
                    <div style="font-size:.76rem;color:#92400e;line-height:1.5">
                        <strong>Perhatian:</strong> Semua data terkait user ini akan dihapus permanen termasuk riwayat aktivitasnya.
                    </div>
                </div>

                <!-- Buttons -->
                <div style="padding:0 32px 28px;display:flex;gap:10px">
                    <button @click="showDeleteModal = false"
                        style="flex:1;padding:12px 20px;border-radius:10px;border:1.5px solid var(--border);background:var(--card-bg);color:var(--text-dark);font-size:.88rem;font-weight:600;cursor:pointer;transition:all .15s;display:flex;align-items:center;justify-content:center;gap:6px"
                        onmouseover="this.style.background='#f8fafc';this.style.borderColor='#cbd5e1'"
                        onmouseout="this.style.background='var(--card-bg)';this.style.borderColor='var(--border)'">
                        <i class="bi bi-arrow-left" style="font-size:.8rem"></i> Batal
                    </button>
                    <button @click="confirmDelete()"
                        style="flex:1;padding:12px 20px;border-radius:10px;border:none;background:linear-gradient(135deg,#ef4444 0%,#dc2626 100%);color:#fff;font-size:.88rem;font-weight:600;cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 14px rgba(239,68,68,.3)"
                        onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 6px 20px rgba(239,68,68,.4)'"
                        onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 14px rgba(239,68,68,.3)'">
                        <i class="bi bi-trash3" style="font-size:.85rem"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Filter Script + Modal Animations -->
<style>
.user-row { opacity: 1; transform: scale(1); transition: all .25s ease; }
.user-row.hidden { display: none; }
.user-row:hover { box-shadow: 0 4px 20px rgba(0,0,0,.05); }
.chip-active { background: var(--sidebar-bg) !important; color: #e2e8f0 !important; }

/* Danger bar shimmer */
@keyframes dangerShimmer {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
/* Pulse ring on icon */
@keyframes modalPulse {
    0%   { transform: scale(1);   opacity: .6; }
    70%  { transform: scale(1.35); opacity: 0; }
    100% { transform: scale(1.35); opacity: 0; }
}
</style>
<script>
function filterUsers(role) {
    document.querySelectorAll('.user-row').forEach(row => {
        row.classList.remove('hidden');
        if (role !== 'all' && row.dataset.role !== role) {
            row.classList.add('hidden');
        }
    });
    document.querySelectorAll('[id^="chip-"]').forEach(chip => {
        chip.classList.remove('chip-active');
        chip.style.background = '#f1f5f9';
        chip.style.color = 'var(--text-muted)';
    });
    const active = document.getElementById('chip-' + role);
    active.classList.add('chip-active');
    active.style.background = '';
    active.style.color = '';
}
</script>
@endsection