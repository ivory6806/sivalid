@extends('layouts.app')
@section('title', 'Dashboard Superadmin')
@section('pageTitle', 'Dashboard Superadmin')
@section('content')

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card blue">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-lbl" style="margin-bottom:4px">Total User</div>
                    <div class="stat-val">{{ $totalUsers }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card green">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-lbl" style="margin-bottom:4px">User Aktif</div>
                    <div class="stat-val" style="color:#059669">{{ $activeUsers }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card amber">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-lbl" style="margin-bottom:4px">Total Surat</div>
                    <div class="stat-val">{{ $totalSurat }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-file-earmark-text"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card purple">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-lbl" style="margin-bottom:4px">Jumlah Staff</div>
                    <div class="stat-val" style="color:#7c3aed">{{ $staffCount }}</div>
                </div>
                <div class="stat-icon"><i class="bi bi-person-badge"></i></div>
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
                <a href="{{ route('surat.index') }}" class="btn-outline-custom" style="font-size:.8rem">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
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
                    <div style="font-size:.78rem;color:var(--text-muted)">{{ $item->nama_mahasiswa }}</div>
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
                    <i class="bi bi-file-earmark-text" style="font-size:1.6rem;color:#cbd5e1"></i>
                </div>
                <div style="font-size:.92rem;font-weight:600;color:var(--text-muted)">Belum ada data surat</div>
                <div style="font-size:.82rem;color:#94a3b8;margin-top:4px">Surat akan muncul setelah staff mengajukan pengajuan.</div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="col-lg-4">
        <!-- Superadmin Badge -->
        <div style="background:linear-gradient(135deg,#0f172a 0%,#1e293b 100%);border-radius:14px;padding:24px;margin-bottom:20px;color:#f1f5f9;position:relative;overflow:hidden">
            <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:rgba(5,150,105,.15)"></div>
            <div style="position:absolute;bottom:-30px;left:-10px;width:60px;height:60px;border-radius:50%;background:rgba(124,58,237,.1)"></div>
            <div style="position:relative;z-index:1">
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
                    <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,var(--primary),#0d9488);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.2rem;box-shadow:0 4px 12px rgba(5,150,105,.3)">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <div>
                        <div style="font-size:1rem;font-weight:800">Superadmin</div>
                        <div style="font-size:.72rem;color:#94a3b8;font-weight:500">Full Access System</div>
                    </div>
                </div>
                <div style="display:flex;gap:20px">
                    <div>
                        <div style="font-size:1.4rem;font-weight:900">{{ $totalUsers }}</div>
                        <div style="font-size:.7rem;color:#94a3b8;font-weight:500;text-transform:uppercase;letter-spacing:.5px">User</div>
                    </div>
                    <div style="width:1px;background:rgba(255,255,255,.1)"></div>
                    <div>
                        <div style="font-size:1.4rem;font-weight:900">{{ $totalSurat }}</div>
                        <div style="font-size:.7rem;color:#94a3b8;font-weight:500;text-transform:uppercase;letter-spacing:.5px">Surat</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card-custom mb-4">
            <div class="card-header-custom"><span><i class="bi bi-lightning-charge me-2" style="color:#d97706"></i>Menu Cepat</span></div>
            <div style="padding:6px">
                <a href="{{ route('users.index') }}" style="padding:14px 16px;border-radius:10px;text-decoration:none;color:var(--text-dark);transition:all .15s;display:flex;align-items:center;gap:12px" onmouseover="this.style.background='#f5f3ff'" onmouseout="this.style.background='transparent'">
                    <div style="width:40px;height:40px;border-radius:10px;background:#f5f3ff;color:#7c3aed;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div style="flex:1">
                        <div style="font-size:.88rem;font-weight:600">Kelola User</div>
                        <div style="font-size:.74rem;color:var(--text-muted)">Tambah, edit, nonaktifkan akun</div>
                    </div>
                    <i class="bi bi-chevron-right" style="color:#cbd5e1;font-size:.8rem"></i>
                </a>
                <a href="{{ route('validasi.index') }}" style="padding:14px 16px;border-radius:10px;text-decoration:none;color:var(--text-dark);transition:all .15s;display:flex;align-items:center;gap:12px" onmouseover="this.style.background='#fffbeb'" onmouseout="this.style.background='transparent'">
                    <div style="width:40px;height:40px;border-radius:10px;background:#fffbeb;color:#d97706;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">
                        <i class="bi bi-clipboard2-check"></i>
                    </div>
                    <div style="flex:1">
                        <div style="font-size:.88rem;font-weight:600">Validasi Surat</div>
                        <div style="font-size:.74rem;color:var(--text-muted)">Setujui atau tolak pengajuan</div>
                    </div>
                    <i class="bi bi-chevron-right" style="color:#cbd5e1;font-size:.8rem"></i>
                </a>
                <a href="{{ route('surat.create') }}" style="padding:14px 16px;border-radius:10px;text-decoration:none;color:var(--text-dark);transition:all .15s;display:flex;align-items:center;gap:12px" onmouseover="this.style.background='var(--primary-light)'" onmouseout="this.style.background='transparent'">
                    <div style="width:40px;height:40px;border-radius:10px;background:var(--primary-light);color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0">
                        <i class="bi bi-file-earmark-plus"></i>
                    </div>
                    <div style="flex:1">
                        <div style="font-size:.88rem;font-weight:600">Buat Surat</div>
                        <div style="font-size:.74rem;color:var(--text-muted)">Ajukan surat keterangan aktif</div>
                    </div>
                    <i class="bi bi-chevron-right" style="color:#cbd5e1;font-size:.8rem"></i>
                </a>
            </div>
        </div>

        <!-- User Stats -->
        <div class="card-custom">
            <div class="card-header-custom"><span><i class="bi bi-bar-chart me-2" style="color:#3b82f6"></i>Distribusi User</span></div>
            <div style="padding:20px 22px">
                @php
                    $staffN = \App\Models\User::where('role','staff')->count();
                    $kepalaN = \App\Models\User::where('role','kepala')->count();
                    $nonActiveN = \App\Models\User::where('is_active',false)->count();
                    $uTotal = max($totalUsers, 1);
                @endphp
                <!-- Staff -->
                <div style="margin-bottom:16px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-size:.8rem;font-weight:600;color:var(--text-dark);display:flex;align-items:center;gap:6px">
                            <span style="width:8px;height:8px;border-radius:3px;background:#3b82f6;display:inline-block"></span>Staff
                        </span>
                        <span style="font-size:.8rem;font-weight:700;color:#3b82f6">{{ $staffN }} &middot; {{ round($staffN/$uTotal*100) }}%</span>
                    </div>
                    <div style="height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:{{ round($staffN/$uTotal*100) }}%;background:linear-gradient(90deg,#3b82f6,#60a5fa);border-radius:4px;transition:width .6s"></div>
                    </div>
                </div>
                <!-- Kepala -->
                <div style="margin-bottom:16px">
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-size:.8rem;font-weight:600;color:var(--text-dark);display:flex;align-items:center;gap:6px">
                            <span style="width:8px;height:8px;border-radius:3px;background:#059669;display:inline-block"></span>Kepala Staff
                        </span>
                        <span style="font-size:.8rem;font-weight:700;color:#059669">{{ $kepalaN }} &middot; {{ round($kepalaN/$uTotal*100) }}%</span>
                    </div>
                    <div style="height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:{{ round($kepalaN/$uTotal*100) }}%;background:linear-gradient(90deg,#059669,#34d399);border-radius:4px;transition:width .6s"></div>
                    </div>
                </div>
                <!-- Nonaktif -->
                <div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:6px">
                        <span style="font-size:.8rem;font-weight:600;color:var(--text-dark);display:flex;align-items:center;gap:6px">
                            <span style="width:8px;height:8px;border-radius:3px;background:#94a3b8;display:inline-block"></span>Nonaktif
                        </span>
                        <span style="font-size:.8rem;font-weight:700;color:#94a3b8">{{ $nonActiveN }} &middot; {{ round($nonActiveN/$uTotal*100) }}%</span>
                    </div>
                    <div style="height:8px;background:#f1f5f9;border-radius:4px;overflow:hidden">
                        <div style="height:100%;width:{{ max(round($nonActiveN/$uTotal*100), 2) }}%;background:linear-gradient(90deg,#94a3b8,#cbd5e1);border-radius:4px;transition:width .6s"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection