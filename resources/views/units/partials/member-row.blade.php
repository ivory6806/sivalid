<div style="display:flex;align-items:center;gap:12px;padding:10px 12px;border-radius:10px;background:{{ $member->is_active ? '#f8fafc' : '#fef2f2' }};margin-bottom:8px">
    <div style="width:34px;height:34px;border-radius:10px;background:{{ $member->role === 'kepala' ? '#ecfdf5' : '#eff6ff' }};color:{{ $member->role === 'kepala' ? '#059669' : '#3b82f6' }};display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;flex-shrink:0">
        {{ strtoupper(substr($member->user->name, 0, 1)) }}
    </div>
    <div style="flex:1;min-width:0">
        <div style="font-size:.85rem;font-weight:600;color:var(--text-dark)">{{ $member->user->name }}</div>
        <div style="font-size:.72rem;color:var(--text-muted)">
            {{ $member->user->email }}
            @if($member->jabatan)
                &middot; <span style="color:{{ $member->role === 'kepala' ? '#059669' : '#3b82f6' }};font-weight:600">{{ $member->jabatan }}</span>
            @endif
        </div>
    </div>
    @if(!$member->is_active)
        <span style="padding:2px 8px;border-radius:6px;background:#fee2e2;color:#ef4444;font-size:.62rem;font-weight:700">NONAKTIF</span>
    @endif
    <button type="button" class="btn-outline-custom" style="padding:6px 10px;font-size:.72rem" title="Ubah Jabatan"
        @click="openJabatan('{{ addslashes($member->user->name) }}', '{{ route('units.members.jabatan', $member) }}', '{{ addslashes($member->jabatan ?? '') }}')">
        <i class="bi bi-pencil"></i>
    </button>
    <form method="POST" action="{{ route('units.members.toggle', $member) }}">
        @csrf
        <button type="submit" class="btn-outline-custom" style="padding:6px 10px;font-size:.72rem" title="{{ $member->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
            <i class="bi bi-{{ $member->is_active ? 'pause' : 'play' }}-fill"></i>
        </button>
    </form>
    <button type="button" class="btn-outline-custom" style="padding:6px 10px;font-size:.72rem;border-color:#fecaca;color:#ef4444" title="Keluarkan"
        @click="askRemove('{{ addslashes($member->user->name) }}', 'remove-form-{{ $member->id }}')">
        <i class="bi bi-x-lg"></i>
    </button>
    <form method="POST" action="{{ route('units.members.remove', $member) }}" id="remove-form-{{ $member->id }}" style="display:none">
        @csrf @method('DELETE')
    </form>
</div>
