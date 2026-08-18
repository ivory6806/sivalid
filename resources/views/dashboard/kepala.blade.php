@extends('layouts.app')
@section('title', 'Dashboard Kepala Staff')
@section('pageTitle', 'Dashboard Kepala Staff')
@section('content')

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card blue">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-lbl" style="margin-bottom:4px">Total Surat</div>
                    <div class="stat-val">{{ $total }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-folder2"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card amber">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-lbl" style="margin-bottom:4px">Menunggu Validasi</div>
                    <div class="stat-val" style="color:#d97706">{{ $diajukan }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card green">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-lbl" style="margin-bottom:4px">Disetujui</div>
                    <div class="stat-val" style="color:#059669">{{ $disetujui }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card red">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-lbl" style="margin-bottom:4px">Direvisi</div>
                    <div class="stat-val" style="color:#ef4444">{{ $direvisi }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-arrow-counterclockwise"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Banner -->
@if($diajukan > 0)
<div style="background:linear-gradient(135deg,#fffbeb 0%,#fef3c7 100%);border:1px solid #fde68a;border-radius:14px;padding:18px 22px;margin-bottom:24px;display:flex;align-items:center;gap:14px">
    <div style="width:48px;height:48px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;animation:pulse-icon 2s ease-in-out infinite">
        <i class="bi bi-bell-fill" style="font-size:1.3rem;color:#d97706"></i>
    </div>
    <div style="flex:1">
        <div style="font-size:.92rem;font-weight:700;color:#92400e">Ada {{ $diajukan }} surat menunggu validasi</div>
        <div style="font-size:.8rem;color:#a16207;margin-top:2px">Periksa dan berikan persetujuan atau kembalikan untuk direvisi.</div>
    </div>
    <a href="{{ route('validasi.index') }}" style="padding:10px 22px;border-radius:10px;background:#d97706;color:#fff;font-size:.85rem;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:all .2s;flex-shrink:0" onmouseover="this.style.background='#b45309'" onmouseout="this.style.background='#d97706'">
        <i class="bi bi-clipboard2-check"></i> Validasi Sekarang
    </a>
</div>
<style>@keyframes pulse-icon{0%,100%{transform:scale(1)}50%{transform:scale(1.08)}}</style>
@endif

<!-- Content Grid -->
<div class="row g-4">
    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <!-- Quick Action -->
        <div class="card-custom mb-4">
            <div class="card-header-custom"><span><i class="bi bi-lightning-charge me-2" style="color:#d97706"></i>Aksi Cepat</span></div>
            <div style="padding:6px">
                <a href="{{ route('validasi.index') }}" style="padding:14px 16px;border-radius:10px;text-decoration:none;color:var(--text-dark);transition:all .15s;display:flex;align-items:center;gap:12px" onmouseover="this.style.background='#fffbeb'" onmouseout="this.style.background='transparent'">
                    <div style="width:40px;height:40px;border-radius:10px;background:#fffbeb;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">
                        <i class="bi bi-clipboard2-check"></i>
                    </div>
                    <div style="flex:1">
                        <div style="font-size:.88rem;font-weight:600">Validasi Surat</div>
                        <div style="font-size:.74rem;color:var(--text-muted)">{{ $diajukan }} surat menunggu</div>
                    </div>
                    @if($diajukan > 0)
                    <span style="background:#d97706;color:#fff;font-size:.65rem;font-weight:700;padding:3px 8px;border-radius:10px">{{ $diajukan }}</span>
                    @endif
                </a>
                <a href="{{ route('surat.index') }}" style="padding:14px 16px;border-radius:10px;text-decoration:none;color:var(--text-dark);transition:all .15s;display:flex;align-items:center;gap:12px" onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='transparent'">
                    <div style="width:40px;height:40px;border-radius:10px;background:#eff6ff;color:#3b82f6;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <div style="flex:1">
                        <div style="font-size:.88rem;font-weight:600">Riwayat Semua Surat</div>
                        <div style="font-size:.74rem;color:var(--text-muted)">{{ $total }} surat terdaftar</div>
                    </div>
                    <i class="bi bi-chevron-right" style="color:#cbd5e1;font-size:.8rem"></i>
                </a>
            </div>
        </div>

        <!-- Status Ringkasan -->
        <div class="card-custom">
            <div class="card-header-custom"><span><i class="bi bi-pie-chart me-2" style="color:#7c3aed"></i>Ringkasan Status</span></div>
            <div style="padding:20px 22px">
                @php $pctTotal = max($total, 1); @endphp
                <div style="margin-bottom:16px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-size:.8rem;font-weight:600;color:var(--text-dark)">Disetujui</span>
                        <span style="font-size:.8rem;font-weight:700;color:#059669">{{ $disetujui }} &middot; {{ round($disetujui/$pctTotal*100) }}%</span>
                    </div>
                    <div style="height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:{{ round($disetujui/$pctTotal*100) }}%;background:linear-gradient(90deg,#059669,#34d399);border-radius:4px;transition:width .6s"></div>
                    </div>
                </div>
                <div style="margin-bottom:16px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-size:.8rem;font-weight:600;color:var(--text-dark)">Menunggu Validasi</span>
                        <span style="font-size:.8rem;font-weight:700;color:#d97706">{{ $diajukan }} &middot; {{ round($diajukan/$pctTotal*100) }}%</span>
                    </div>
                    <div style="height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:{{ round($diajukan/$pctTotal*100) }}%;background:linear-gradient(90deg,#d97706,#fbbf24);border-radius:4px;transition:width .6s"></div>
                    </div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-size:.8rem;font-weight:600;color:var(--text-dark)">Direvisi</span>
                        <span style="font-size:.8rem;font-weight:700;color:#ef4444">{{ $direvisi }} &middot; {{ round($direvisi/$pctTotal*100) }}%</span>
                    </div>
                    <div style="height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:{{ round($direvisi/$pctTotal*100) }}%;background:linear-gradient(90deg,#ef4444,#f87171);border-radius:4px;transition:width .6s"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Surat Terbaru -->
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <span><i class="bi bi-clock-history me-2" style="color:var(--primary)"></i>Surat Terbaru</span>
                <a href="{{ route('validasi.index') }}" class="btn-primary-custom" style="padding:8px 16px;font-size:.82rem;background:#d97706" onmouseover="this.style.background='#b45309'" onmouseout="this.style.background='#d97706'">
                    <i class="bi bi-clipboard2-check"></i> Ke Validasi
                </a>
            </div>

            <!-- Filter Tabs -->
            <div style="padding:12px 22px 0;display:flex;gap:6px;flex-wrap:wrap">
                <button onclick="filterSurat('semua', this)" class="filter-tab active">
                    Semua <span class="filter-count">{{ $total }}</span>
                </button>
                <button onclick="filterSurat('diajukan', this)" class="filter-tab">
                    <i class="bi bi-hourglass-split" style="font-size:.7rem"></i> Menunggu
                    <span class="filter-count" style="background:#fffbeb;color:#d97706">{{ $diajukan }}</span>
                </button>
                <button onclick="filterSurat('disetujui', this)" class="filter-tab">
                    <i class="bi bi-check-circle" style="font-size:.7rem"></i> Disetujui
                    <span class="filter-count" style="background:#ecfdf5;color:#059669">{{ $disetujui }}</span>
                </button>
                <button onclick="filterSurat('direvisi', this)" class="filter-tab">
                    <i class="bi bi-arrow-counterclockwise" style="font-size:.7rem"></i> Direvisi
                    <span class="filter-count" style="background:#fef2f2;color:#ef4444">{{ $direvisi }}</span>
                </button>
            </div>

            <div style="margin:12px 22px 0;height:1px;background:var(--border)"></div>

            <!-- Surat List -->
            <div id="suratList">
                @forelse($recent as $item)
                <div class="surat-row" data-status="{{ $item->status }}" style="padding:14px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:14px;transition:background .15s;cursor:pointer" onmouseover="if(this.style.display!=='none')this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'" onclick="window.location='{{ route('surat.show', $item) }}'">
                    <div style="width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;flex-shrink:0;
                        {{ $item->status === 'disetujui' ? 'background:#ecfdf5;color:#059669' : ($item->status === 'direvisi' ? 'background:#fef2f2;color:#ef4444' : 'background:#fffbeb;color:#d97706') }}">
                        {{ strtoupper(substr($item->nama_mahasiswa ?? $item->nama_surat ?? '?', 0, 1)) }}
                    </div>
                    <div style="flex:1;min-width:0">
                        <div style="font-size:.88rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $item->nomor_surat }}</div>
                        <div style="font-size:.78rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            {{ $item->nama_mahasiswa ? $item->nama_mahasiswa . ' · ' . $item->prodi : $item->nama_surat }}
                        </div>
                        <div style="font-size:.72rem;color:#94a3b8;margin-top:2px"><i class="bi bi-person me-1"></i>{{ $item->user->name }}</div>
                    </div>
                    <div style="text-align:right;flex-shrink:0">
                        <span class="badge-status" style="
                            background:{{ $item->status === 'disetujui' ? '#ecfdf5' : ($item->status === 'direvisi' ? '#fef2f2' : '#fffbeb') }};
                            color:{{ $item->status === 'disetujui' ? '#059669' : ($item->status === 'direvisi' ? '#ef4444' : '#d97706') }}
                        ">{{ $item->status_label }}</span>
                        <div style="font-size:.7rem;color:var(--text-muted);margin-top:3px">{{ $item->created_at->format('d M Y') }}</div>
                    </div>
                    <i class="bi bi-chevron-right" style="color:var(--text-muted);font-size:.85rem;flex-shrink:0"></i>
                </div>
                @empty
                <div id="emptyDefault" style="padding:60px 22px;text-align:center">
                    <div style="width:64px;height:64px;border-radius:16px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
                        <i class="bi bi-check-all" style="font-size:1.6rem;color:#cbd5e1"></i>
                    </div>
                    <div style="font-size:.92rem;font-weight:600;color:var(--text-muted)">Semua surat sudah diproses</div>
                    <div style="font-size:.82rem;color:#94a3b8;margin-top:4px">Tidak ada surat yang menunggu validasi saat ini.</div>
                </div>
                @endforelse

                <div id="emptyFilter" style="display:none;padding:50px 22px;text-align:center">
                    <div style="width:64px;height:64px;border-radius:16px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
                        <i class="bi bi-funnel" style="font-size:1.5rem;color:#cbd5e1"></i>
                    </div>
                    <div style="font-size:.92rem;font-weight:600;color:var(--text-muted)">Tidak ada surat ditemukan</div>
                    <div style="font-size:.82rem;color:#94a3b8;margin-top:4px">Tidak ada surat dengan status yang dipilih.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.filter-tab {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 8px;
    border: 1px solid var(--border, #e2e8f0);
    background: #fff;
    color: var(--text-muted, #64748b);
    font-size: .78rem;
    font-weight: 600;
    cursor: pointer;
    transition: all .15s;
    font-family: inherit;
    line-height: 1;
}
.filter-tab:hover {
    border-color: #cbd5e1;
    color: var(--text-dark, #1e293b);
    background: #f8fafc;
}
.filter-tab.active {
    background: var(--text-dark, #1e293b);
    color: #fff;
    border-color: var(--text-dark, #1e293b);
}
.filter-tab.active .filter-count {
    background: rgba(255,255,255,0.2) !important;
    color: #fff !important;
}
.filter-count {
    font-size: .68rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 6px;
    background: #f1f5f9;
    color: var(--text-muted, #64748b);
    line-height: 1.3;
}
</style>

<script>
function filterSurat(status, btn) {
    // 1. Update tab aktif
    var tabs = document.querySelectorAll('.filter-tab');
    for (var i = 0; i < tabs.length; i++) {
        tabs[i].classList.remove('active');
    }
    btn.classList.add('active');

    // 2. Filter setiap baris surat
    var rows = document.querySelectorAll('.surat-row');
    var count = 0;

    for (var i = 0; i < rows.length; i++) {
        if (status === 'semua' || rows[i].getAttribute('data-status') === status) {
            rows[i].style.display = 'flex';
            count++;
        } else {
            rows[i].style.display = 'none';
        }
    }

    // 3. Tampilkan/sembunyikan empty state
    var emptyDefault = document.getElementById('emptyDefault');
    var emptyFilter = document.getElementById('emptyFilter');

    if (emptyFilter) {
        emptyFilter.style.display = (count === 0 && status !== 'semua') ? 'block' : 'none';
    }
    if (emptyDefault) {
        emptyDefault.style.display = (count === 0 && status === 'semua') ? 'block' : 'none';
    }
}
</script>

@endsection