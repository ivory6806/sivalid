@extends('layouts.app')
@section('title', 'Dashboard Staff')
@section('pageTitle', 'Dashboard Staff')
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
                    <div class="stat-lbl" style="margin-bottom:4px">Sudah Disetujui</div>
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
                    <div class="stat-lbl" style="margin-bottom:4px">Revisi</div>
                    <div class="stat-val" style="color:#ef4444">{{ $direvisi }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-arrow-counterclockwise"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Content Grid -->
<div class="row g-4">
    <!-- Tabel Surat Terbaru -->
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <span><i class="bi bi-clock-history me-2" style="color:var(--primary)"></i>Surat Terbaru</span>
                <a href="{{ route('surat.create') }}" class="btn-primary-custom" style="padding:8px 16px;font-size:.82rem">
                    <i class="bi bi-plus-lg"></i> Ajukan Surat
                </a>
            </div>
            @forelse($recent as $item)
            <div style="padding:14px 22px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:14px;transition:background .15s;cursor:pointer" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'" onclick="window.location='{{ route('surat.show', $item) }}'">
                <div style="width:42px;height:42px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.8rem;flex-shrink:0;
                    {{ $item->status === 'disetujui' ? 'background:#ecfdf5;color:#059669' : ($item->status === 'direvisi' ? 'background:#fef2f2;color:#ef4444' : 'background:#fffbeb;color:#d97706') }}">
                    {{ strtoupper(substr($item->nama_mahasiswa, 0, 1)) }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:.88rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $item->nomor_surat }}</div>
                    <div style="font-size:.78rem;color:var(--text-muted)">{{ $item->nama_mahasiswa }} &middot; {{ $item->prodi }}</div>
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
            <div style="padding:60px 22px;text-align:center">
                <div style="width:64px;height:64px;border-radius:16px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
                    <i class="bi bi-inbox" style="font-size:1.6rem;color:#cbd5e1"></i>
                </div>
                <div style="font-size:.92rem;font-weight:600;color:var(--text-muted)">Belum ada surat</div>
                <div style="font-size:.82rem;color:#94a3b8;margin-top:4px">Mulai dengan mengajukan surat keterangan aktif baru.</div>
                <a href="{{ route('surat.create') }}" class="btn-primary-custom" style="margin-top:16px;display:inline-flex">
                    <i class="bi bi-plus-lg"></i> Ajukan Surat Pertama
                </a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <!-- Quick Action -->
        <div class="card-custom mb-4">
            <div class="card-header-custom"><span><i class="bi bi-lightning-charge me-2" style="color:#d97706"></i>Aksi Cepat</span></div>
            <div style="padding:6px">
                <a href="{{ route('surat.create') }}" class="d-flex align-items-center gap-12" style="padding:14px 16px;border-radius:10px;text-decoration:none;color:var(--text-dark);transition:all .15s;display:flex;align-items:center;gap:12px" onmouseover="this.style.background='var(--primary-light)'" onmouseout="this.style.background='transparent'">
                    <div style="width:40px;height:40px;border-radius:10px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.1rem">
                        <i class="bi bi-file-earmark-plus"></i>
                    </div>
                    <div>
                        <div style="font-size:.88rem;font-weight:600">Ajukan Surat Baru</div>
                        <div style="font-size:.74rem;color:var(--text-muted)">Upload PDF & isi data</div>
                    </div>
                </a>
                <a href="{{ route('surat.index') }}" class="d-flex align-items-center gap-12" style="padding:14px 16px;border-radius:10px;text-decoration:none;color:var(--text-dark);transition:all .15s;display:flex;align-items:center;gap:12px" onmouseover="this.style.background='#eff6ff'" onmouseout="this.style.background='transparent'">
                    <div style="width:40px;height:40px;border-radius:10px;background:#eff6ff;color:#3b82f6;display:flex;align-items:center;justify-content:center;font-size:1.1rem">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <div>
                        <div style="font-size:.88rem;font-weight:600">Lihat Semua Riwayat</div>
                        <div style="font-size:.74rem;color:var(--text-muted)">{{ $total }} surat terdaftar</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Status Ringkasan -->
        <div class="card-custom">
            <div class="card-header-custom"><span><i class="bi bi-pie-chart me-2" style="color:#7c3aed"></i>Ringkasan Status</span></div>
            <div style="padding:20px 22px">
                @php $pctTotal = max($total, 1); @endphp
                <!-- Disetujui -->
                <div style="margin-bottom:16px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-size:.8rem;font-weight:600;color:var(--text-dark)">Disetujui</span>
                        <span style="font-size:.8rem;font-weight:700;color:#059669">{{ $disetujui }} &middot; {{ round($disetujui/$pctTotal*100) }}%</span>
                    </div>
                    <div style="height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:{{ round($disetujui/$pctTotal*100) }}%;background:linear-gradient(90deg,#059669,#34d399);border-radius:4px;transition:width .6s"></div>
                    </div>
                </div>
                <!-- Menunggu -->
                <div style="margin-bottom:16px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-size:.8rem;font-weight:600;color:var(--text-dark)">Menunggu Validasi</span>
                        <span style="font-size:.8rem;font-weight:700;color:#d97706">{{ $diajukan }} &middot; {{ round($diajukan/$pctTotal*100) }}%</span>
                    </div>
                    <div style="height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:{{ round($diajukan/$pctTotal*100) }}%;background:linear-gradient(90deg,#d97706,#fbbf24);border-radius:4px;transition:width .6s"></div>
                    </div>
                </div>
                <!-- Revisi -->
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-size:.8rem;font-weight:600;color:var(--text-dark)">Revisi</span>
                        <span style="font-size:.8rem;font-weight:700;color:#ef4444">{{ $direvisi }} &middot; {{ round($direvisi/$pctTotal*100) }}%</span>
                    </div>
                    <div style="height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:{{ round($direvisi/$pctTotal*100) }}%;background:linear-gradient(90deg,#ef4444,#f87171);border-radius:4px;transition:width .6s"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection