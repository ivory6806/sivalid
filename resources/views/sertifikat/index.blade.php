@extends('layouts.app')

@section('title', 'Riwayat Sertifikat')
@section('pageTitle', 'Riwayat Sertifikat')

@section('content')
<style>
    .history-header {
        background: linear-gradient(135deg, #312e81 0%, #4f46e5 50%, #7c3aed 100%);
        border-radius: 20px;
        padding: 2rem 2.25rem;
        color: #fff;
        position: relative;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .history-header::before {
        content: '';
        position: absolute;
        top: -40%; right: -10%;
        width: 300px; height: 300px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .history-header::after {
        content: '';
        position: absolute;
        bottom: -60%; left: 20%;
        width: 200px; height: 200px;
        background: rgba(255,255,255,0.04);
        border-radius: 50%;
    }
    .history-header h2 {
        font-weight: 700; font-size: 1.5rem;
        margin-bottom: .25rem;
        position: relative; z-index: 1;
    }
    .history-header p {
        font-size: .875rem; opacity: .8;
        position: relative; z-index: 1;
    }
    .history-header .header-icon {
        position: absolute; right: 2rem; top: 50%;
        transform: translateY(-50%);
        font-size: 4rem; opacity: .12; z-index: 0;
    }

    .stat-pill {
        display: inline-flex; align-items: center; gap: .5rem;
        background: rgba(255,255,255,0.12);
        backdrop-filter: blur(8px);
        padding: .45rem 1rem; border-radius: 50px;
        font-size: .8rem; font-weight: 500;
        position: relative; z-index: 1;
    }
    .stat-pill .dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: #a5b4fc;
        animation: pulse-dot 2s ease-in-out infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .5; transform: scale(1.3); }
    }

    .btn-create-modern {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(8px);
        color: #fff;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 12px;
        padding: .6rem 1.25rem;
        font-size: .85rem; font-weight: 500;
        transition: all .25s ease;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: .5rem;
        position: relative; z-index: 1;
    }
    .btn-create-modern:hover {
        background: rgba(255,255,255,0.25);
        color: #fff; transform: translateY(-1px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    }

    .filter-bar {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        padding: 1rem 1.25rem;
        margin-bottom: 1rem;
    }
    .filter-row {
        display: flex;
        align-items: center;
        gap: .75rem;
        flex-wrap: wrap;
    }
    .filter-search {
        position: relative;
        flex: 1 1 260px;
        min-width: 200px;
    }
    .filter-search form {
        display: flex;
        align-items: center;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        transition: all .2s;
    }
    .filter-search form:focus-within {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79,70,229,0.1);
        background: #fff;
    }
    .filter-search .search-icon {
        padding: 0 0 0 12px;
        color: #94a3b8;
        font-size: .85rem;
        flex-shrink: 0;
    }
    .filter-search input {
        border: none; background: transparent;
        padding: .55rem .6rem;
        font-size: .82rem;
        color: #1e293b;
        outline: none;
        flex: 1;
        min-width: 0;
        font-family: inherit;
    }
    .filter-search input::placeholder { color: #94a3b8; }
    .filter-search .search-clear {
        padding: 0 8px;
        color: #cbd5e1;
        font-size: .85rem;
        text-decoration: none;
        transition: color .15s;
        flex-shrink: 0;
        display: flex; align-items: center;
    }
    .filter-search .search-clear:hover { color: #64748b; }
    .filter-search .search-btn {
        background: #4f46e5;
        border: none;
        color: #fff;
        padding: .55rem 14px;
        font-size: .8rem;
        cursor: pointer;
        transition: background .15s;
        flex-shrink: 0;
        display: flex; align-items: center;
    }
    .filter-search .search-btn:hover { background: #4338ca; }

    .filter-divider {
        width: 1px; height: 28px;
        background: #e2e8f0;
        flex-shrink: 0;
    }

    .filter-pills {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
        align-items: center;
    }
    .filter-label {
        font-size: .72rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-right: 2px;
        flex-shrink: 0;
    }
    .filter-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        font-size: .75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .15s;
        white-space: nowrap;
        cursor: pointer;
        line-height: 1;
    }
    .filter-pill:hover {
        border-color: #cbd5e1;
        color: #334155;
        background: #f8fafc;
    }
    .filter-pill.active {
        background: #0f172a;
        color: #fff;
        border-color: #0f172a;
    }
    .filter-pill.active .pill-dot { opacity: 1; }
    .pill-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
        opacity: .5;
    }
    .pill-dot-amber { background: #d97706; }
    .pill-dot-green { background: #059669; }
    .pill-dot-red { background: #dc2626; }
    .filter-pill.active .pill-dot-amber { background: #fbbf24; }
    .filter-pill.active .pill-dot-green { background: #6ee7b7; }
    .filter-pill.active .pill-dot-red { background: #fca5a5; }

    .filter-reset {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 14px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        font-size: .75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .15s;
        cursor: pointer;
        white-space: nowrap;
        line-height: 1;
    }
    .filter-reset:hover {
        border-color: #fca5a5;
        color: #dc2626;
        background: #fef2f2;
    }

    .active-filters {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        padding: 10px 1.25rem 0;
    }
    .active-filters-label {
        font-size: .72rem;
        font-weight: 600;
        color: #94a3b8;
        flex-shrink: 0;
    }
    .active-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 6px;
        font-size: .7rem;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
    }
    .active-chip i { font-size: .65rem; opacity: .6; }
    .active-chip a {
        color: #94a3b8;
        text-decoration: none;
        display: flex; align-items: center;
        transition: color .15s;
        margin-left: 2px;
    }
    .active-chip a:hover { color: #dc2626; }
    .active-chip.chip-diajukan { background: #fffbeb; color: #92400e; }
    .active-chip.chip-disetujui { background: #ecfdf5; color: #065f46; }
    .active-chip.chip-direvisi { background: #fef2f2; color: #991b1b; }
    .clear-all {
        font-size: .7rem;
        font-weight: 600;
        color: #94a3b8;
        text-decoration: none;
        transition: color .15s;
    }
    .clear-all:hover { color: #dc2626; }

    .table-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.02);
        overflow: hidden;
    }
    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 0;
    }
    .table-modern thead th {
        background: #f8fafc;
        padding: .85rem 1.1rem;
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .table-modern thead th:first-child { padding-left: 1.5rem; }
    .sort-link {
        color: #64748b;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 2px;
        transition: color .15s;
    }
    .sort-link:hover { color: #4f46e5; }
    .sort-link i { font-size: .6rem; }

    .table-modern tbody tr { transition: all .2s ease; }
    .table-modern tbody tr:hover { background: #eef2ff; }
    .table-modern tbody td {
        padding: 1rem 1.1rem;
        font-size: .85rem;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .table-modern tbody td:first-child { padding-left: 1.5rem; }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    .nomor-sertifikat {
        font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace;
        font-size: .78rem; font-weight: 600;
        color: #334155;
        background: #f1f5f9;
        padding: .3rem .7rem;
        border-radius: 8px;
        letter-spacing: .02em;
    }
    .jenis-text {
        font-weight: 600;
        color: #0f172a;
        font-size: .85rem;
        margin-bottom: .25rem;
    }
    .badge-modern {
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        padding: .35rem .85rem;
        border-radius: 50px;
        font-size: .72rem;
        font-weight: 600;
        letter-spacing: .02em;
        text-transform: capitalize;
    }
    .badge-modern .badge-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .badge-disetujui { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .badge-disetujui .badge-dot { background: #059669; }
    .badge-diajukan { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .badge-diajukan .badge-dot { background: #d97706; animation: pulse-dot 1.5s ease-in-out infinite; }
    .badge-direvisi { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .badge-direvisi .badge-dot { background: #dc2626; }

    .uploader-text {
        font-size: .82rem;
        color: #475569;
        display: flex;
        align-items: center;
        gap: .4rem;
    }
    .uploader-text i { font-size: .75rem; color: #94a3b8; }
    .date-text {
        font-size: .78rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: .35rem;
    }
    .date-text i { font-size: .7rem; opacity: .6; }

    .action-group {
        display: flex;
        gap: .4rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    .btn-action {
        width: 34px; height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        font-size: .85rem;
        transition: all .2s ease;
        text-decoration: none;
        cursor: pointer;
    }
    .btn-action:hover {
        border-color: #4f46e5;
        color: #4f46e5;
        background: #eef2ff;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(79,70,229,0.15);
    }
    .btn-action.btn-preview:hover {
        border-color: #4338ca; color: #4338ca;
        background: #eef2ff;
        box-shadow: 0 2px 8px rgba(67,56,202,0.15);
    }
    .btn-action.btn-edit:hover {
        border-color: #d97706; color: #d97706;
        background: #fffbeb;
        box-shadow: 0 2px 8px rgba(217,119,6,0.15);
    }
    .btn-action.btn-download:hover {
        border-color: #2563eb; color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 2px 8px rgba(37,99,235,0.15);
    }
    .btn-action.btn-delete:hover {
        border-color: #dc2626; color: #dc2626;
        background: #fef2f2;
        box-shadow: 0 2px 8px rgba(220,38,38,0.15);
    }

    .pagination-wrapper {
        padding: 1rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .75rem;
    }
    .pagination-info {
        font-size: .78rem;
        color: #94a3b8;
    }
    .pagination-info strong {
        color: #475569;
        font-weight: 600;
    }
    .pagination {
        display: flex;
        gap: 4px;
        margin: 0;
    }
    .pagination .page-link {
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        padding: .4rem .75rem;
        border-radius: 8px;
        font-size: .8rem;
        font-weight: 500;
        transition: all .15s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 34px;
        height: 34px;
    }
    .pagination .page-link:hover {
        background: #eef2ff;
        border-color: #c7d2fe;
        color: #4f46e5;
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 2px 8px rgba(79,70,229,0.3);
    }
    .pagination .page-item.disabled .page-link {
        background: #f8fafc;
        color: #cbd5e1;
        border-color: #f1f5f9;
        cursor: not-allowed;
        pointer-events: none;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
    }
    .empty-state .empty-icon-wrapper {
        width: 80px; height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eef2ff, #e0e7ff);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .empty-state .empty-icon-wrapper i {
        font-size: 2rem;
        color: #4f46e5;
        opacity: .5;
    }
    .empty-state h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: .35rem;
    }
    .empty-state p {
        font-size: .82rem;
        color: #94a3b8;
    }

    .modal-preview .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0,0,0,0.2);
    }
    .modal-preview .modal-dialog { max-width: 850px; }
    .preview-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #312e81, #4f46e5);
        color: #fff;
    }
    .preview-modal-header .preview-title {
        display: flex;
        align-items: center;
        gap: .6rem;
        font-size: .88rem;
        font-weight: 600;
    }
    .preview-modal-header .preview-title i { font-size: 1rem; opacity: .7; }
    .preview-modal-header .preview-badge {
        background: rgba(255,255,255,0.12);
        padding: .25rem .7rem;
        border-radius: 6px;
        font-size: .7rem;
        font-weight: 600;
        font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace;
        letter-spacing: .02em;
    }
    .preview-modal-close {
        width: 32px; height: 32px;
        border-radius: 8px;
        border: 1px solid rgba(255,255,255,0.15);
        background: rgba(255,255,255,0.08);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all .2s ease;
        font-size: .9rem;
    }
    .preview-modal-close:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.3);
    }
    .preview-frame-wrapper {
        background: #f1f5f9;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        position: relative;
    }
    .preview-frame-wrapper img {
        max-width: 100%;
        max-height: 75vh;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    .preview-error-box {
        text-align: center;
        padding: 40px 20px;
    }
    .preview-error-box i {
        font-size: 2.5rem;
        color: #dc2626;
    }
    .preview-error-box p {
        margin-top: 10px;
        color: #64748b;
        font-size: .9rem;
    }
    .preview-modal-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .75rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }
    .preview-modal-footer .footer-info {
        font-size: .75rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: .4rem;
    }
    .btn-preview-download {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .45rem 1rem;
        border-radius: 10px;
        font-size: .78rem;
        font-weight: 600;
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: #fff;
        text-decoration: none;
        transition: all .2s ease;
        box-shadow: 0 2px 8px rgba(79,70,229,0.25);
    }
    .btn-preview-download:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(79,70,229,0.35);
        color: #fff;
    }

    .modal-content-modern {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 60px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    .modal-body-modern {
        padding: 2.5rem 2rem 2rem;
        text-align: center;
    }
    .modal-icon {
        width: 64px; height: 64px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 1.25rem;
    }
    .modal-icon-delete {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        color: #dc2626;
        box-shadow: 0 4px 14px rgba(220,38,38,0.2);
    }
    .modal-title-modern {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: .5rem;
    }
    .modal-desc-modern {
        font-size: .85rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }
    .modal-desc-modern strong { color: #334155; }
    .modal-actions {
        display: flex;
        gap: .6rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    .btn-modal {
        padding: .6rem 1.5rem;
        border-radius: 12px;
        font-size: .85rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all .2s ease;
    }
    .btn-modal-delete {
        background: linear-gradient(135deg, #dc2626, #ef4444);
        color: #fff;
        box-shadow: 0 4px 14px rgba(220,38,38,0.3);
    }
    .btn-modal-delete:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(220,38,38,0.4);
        color: #fff;
    }
    .btn-modal-cancel {
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
    }
    .btn-modal-cancel:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    @media (max-width: 768px) {
        .history-header { padding: 1.5rem; }
        .history-header h2 { font-size: 1.2rem; }
        .history-header .header-icon { display: none; }
        .filter-divider { display: none; }
        .filter-search { flex: 1 1 100%; }
        .table-modern thead th,
        .table-modern tbody td { padding: .7rem .75rem; font-size: .78rem; }
        .table-modern tbody td:first-child,
        .table-modern thead th:first-child { padding-left: .75rem; }
        .modal-preview .modal-dialog { margin: .5rem; }
        .pagination-wrapper {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }
</style>

@php
    $user = auth()->user();
    $canManage = $user->isStaff() || $user->isSuperadmin();
    $canDelete = $canManage;

    $q = request()->query();
    $statusLabels = [
        'diajukan' => 'Menunggu',
        'disetujui' => 'Disetujui',
        'direvisi' => 'Direvisi',
    ];

    $filterUrl = function($key, $value) use ($q) {
        $params = $q;
        if ($value === '' || $value === null) {
            unset($params[$key]);
        } else {
            $params[$key] = $value;
        }
        unset($params['page']);
        return request()->fullUrlWithQuery($params);
    };

    $sortUrl = function($col) use ($q) {
        $params = $q;
        $params['sort'] = $col;
        $params['dir'] = (isset($q['sort']) && $q['sort'] === $col && isset($q['dir']) && $q['dir'] === 'asc') ? 'desc' : 'asc';
        unset($params['page']);
        return request()->fullUrlWithQuery($params);
    };

    $sortIcon = function($col) {
        $currentSort = request('sort');
        $currentDir = request('dir', 'desc');
        if ($currentSort !== $col) {
            return '<i class="bi bi-chevron-expand" style="opacity:.2"></i>';
        }
        if ($currentDir === 'asc') {
            return '<i class="bi bi-chevron-up" style="color:#4f46e5"></i>';
        }
        return '<i class="bi bi-chevron-down" style="color:#4f46e5"></i>';
    };
@endphp

{{-- Header --}}
<div class="history-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2><i class="bi bi-award me-2" style="opacity:.7"></i>Riwayat Sertifikat</h2>
            <p class="mb-2">Daftar sertifikat yang telah diajukan & status validasinya</p>
            <div class="stat-pill">
                <span class="dot"></span>
                {{ $sertifikats->total() }} sertifikat tercatat
            </div>
        </div>
        @if($canManage)
            <a href="{{ route('sertifikat.create') }}" class="btn-create-modern">
                <i class="bi bi-plus-lg"></i> Ajukan Sertifikat
            </a>
        @endif
    </div>
    <i class="bi bi-patch-check header-icon"></i>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    <div class="filter-row">
        <div class="filter-search">
            <form onsubmit="return applySearch(this)">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor atau jenis sertifikat..." autocomplete="off">
                @if(request('search'))
                <a href="{{ $filterUrl('search', '') }}" class="search-clear" title="Hapus pencarian"><i class="bi bi-x-circle-fill"></i></a>
                @endif
                <button type="submit" class="search-btn"><i class="bi bi-arrow-right"></i></button>
            </form>
        </div>

        <div class="filter-divider d-none d-lg-block"></div>

        <div class="filter-pills">
            <span class="filter-label">Status</span>
            <a href="{{ $filterUrl('status', '') }}" class="filter-pill {{ !request('status') ? 'active' : '' }}">Semua</a>
            <a href="{{ $filterUrl('status', 'diajukan') }}" class="filter-pill {{ request('status') === 'diajukan' ? 'active' : '' }}">
                <span class="pill-dot pill-dot-amber"></span>Menunggu
            </a>
            <a href="{{ $filterUrl('status', 'disetujui') }}" class="filter-pill {{ request('status') === 'disetujui' ? 'active' : '' }}">
                <span class="pill-dot pill-dot-green"></span>Disetujui
            </a>
            <a href="{{ $filterUrl('status', 'direvisi') }}" class="filter-pill {{ request('status') === 'direvisi' ? 'active' : '' }}">
                <span class="pill-dot pill-dot-red"></span>Direvisi
            </a>
        </div>

        @if(request('search') || request('status') || request('sort'))
        <a href="{{ url()->current() }}" class="filter-reset" title="Reset semua filter & sort">
            <i class="bi bi-arrow-counterclockwise"></i> Reset
        </a>
        @endif
    </div>

    @if(request('search') || request('status'))
    <div class="active-filters">
        <span class="active-filters-label">Filter aktif:</span>
        @if(request('search'))
        <span class="active-chip">
            <i class="bi bi-search"></i> "{{ request('search') }}"
            <a href="{{ $filterUrl('search', '') }}" title="Hapus"><i class="bi bi-x"></i></a>
        </span>
        @endif
        @if(request('status'))
        <span class="active-chip chip-{{ request('status') }}">
            {{ $statusLabels[request('status')] ?? request('status') }}
            <a href="{{ $filterUrl('status', '') }}" title="Hapus"><i class="bi bi-x"></i></a>
        </span>
        @endif
        <a href="{{ url()->current() }}" class="clear-all">Hapus semua</a>
    </div>
    @endif
</div>

{{-- Table --}}
<div class="table-card">
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th><a href="{{ $sortUrl('nomor_sertifikat') }}" class="sort-link">Nomor Sertifikat {!! $sortIcon('nomor_sertifikat') !!}</a></th>
                    <th><a href="{{ $sortUrl('jenis_sertifikat') }}" class="sort-link">Jenis {!! $sortIcon('jenis_sertifikat') !!}</a></th>
                    <th>Diajukan Oleh</th>
                    <th><a href="{{ $sortUrl('status') }}" class="sort-link">Status {!! $sortIcon('status') !!}</a></th>
                    <th><a href="{{ $sortUrl('created_at') }}" class="sort-link">Tanggal {!! $sortIcon('created_at') !!}</a></th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sertifikats as $s)
                <tr>
                    <td><span class="nomor-sertifikat">{{ $s->nomor_sertifikat }}</span></td>
                    <td><span class="jenis-text">{{ $s->jenis_sertifikat }}</span></td>
                    <td>
                        <span class="uploader-text">
                            <i class="bi bi-person-circle"></i>
                            {{ $s->uploader->name ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="badge-modern badge-{{ $s->status }}">
                            <span class="badge-dot"></span>
                            {{ $s->status_label }}
                        </span>
                    </td>
                    <td>
                        <span class="date-text">
                            <i class="bi bi-calendar3"></i>
                            {{ $s->created_at->format('d M Y') }}
                        </span>
                    </td>
                    <td>
                        <div class="action-group">
                            @if($s->status === 'direvisi' && $s->uploaded_by === auth()->id())
                            <a href="{{ route('sertifikat.edit', $s) }}" class="btn-action btn-edit" title="Edit & Ajukan Ulang">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            @endif

                            <button type="button" class="btn-action btn-preview" data-bs-toggle="modal" data-bs-target="#previewModal{{ $s->id }}" title="Preview">
                                <i class="bi bi-eye"></i>
                            </button>

                            <a href="{{ route('sertifikat.show', $s) }}" class="btn-action" title="Detail">
                                <i class="bi bi-info-circle"></i>
                            </a>

                            <a href="{{ route('sertifikat.download', $s) }}" class="btn-action btn-download" title="Download">
                                <i class="bi bi-download"></i>
                            </a>

                            @if($canDelete && ($s->uploaded_by === auth()->id() || auth()->user()->isSuperadmin()))
                            <button type="button" class="btn-action btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $s->id }}" title="Hapus">
                                <i class="bi bi-trash3"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-icon-wrapper">
                                <i class="bi bi-inbox"></i>
                            </div>
                            <h5>Tidak Ada Data Ditemukan</h5>
                            <p>Coba ubah filter atau kata kunci pencarian Anda.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($sertifikats->hasPages())
    <div class="pagination-wrapper">
        <span class="pagination-info">
            Menampilkan <strong>{{ $sertifikats->firstItem() }}–{{ $sertifikats->lastItem() }}</strong> dari <strong>{{ $sertifikats->total() }}</strong> data
        </span>
        {{ $sertifikats->appends(request()->query())->links() }}
    </div>
    @elseif($sertifikats->isNotEmpty())
    <div style="padding:1rem 1.5rem;background:#f8fafc;border-top:1px solid #f1f5f9;font-size:.78rem;color:#94a3b8">
        {{ $sertifikats->count() }} data ditampilkan
    </div>
    @endif
</div>

{{-- ====================================================== --}}
{{-- MODALS — Preview & Delete --}}
{{-- ====================================================== --}}
@foreach($sertifikats as $s)
{{-- Modal Preview --}}
<div class="modal fade modal-preview" id="previewModal{{ $s->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="preview-modal-header">
                <div class="preview-title">
                    <i class="bi bi-image"></i>
                    Preview Sertifikat
                    <span class="preview-badge">{{ $s->nomor_sertifikat }}</span>
                </div>
                <button type="button" class="preview-modal-close" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="preview-frame-wrapper" id="previewWrapper{{ $s->id }}">
                @php
                    // Cek ketersediaan file
                    $hasPreviewFile = false;
                    if ($s->status === 'disetujui' && $s->file_disahkan) {
                        $hasPreviewFile = Storage::disk('public')->exists('sertifikat_disahkan/' . $s->file_disahkan);
                    }
                    if (!$hasPreviewFile && $s->file_asli) {
                        $hasPreviewFile = Storage::disk('public')->exists('sertifikat/' . $s->file_asli);
                    }
                    
                    // Gunakan route preview - lebih reliable
                    $modalPreviewUrl = route('sertifikat.preview', $s);
                @endphp
                
                @if($hasPreviewFile)
                    <img 
                        src="{{ $modalPreviewUrl }}" 
                        alt="{{ $s->nomor_sertifikat }}" 
                        style="max-width:100%;max-height:75vh;object-fit:contain;border-radius:8px;box-shadow:0 4px 20px rgba(0,0,0,0.1);"
                        onload="this.style.opacity=0;this.style.transition='opacity 0.3s';setTimeout(()=>this.style.opacity=1,50);"
                        onerror="handleModalPreviewError({{ $s->id }})"
                    >
                    <div id="previewError{{ $s->id }}" style="display:none;text-align:center;padding:40px 20px;">
                        <i class="bi bi-exclamation-triangle" style="font-size:2.5rem;color:#dc2626;"></i>
                        <p style="margin-top:10px;color:#64748b;font-size:.9rem;">Gagal memuat preview gambar.</p>
                        <p style="color:#94a3b8;font-size:.8rem;">Coba refresh halaman atau download file langsung.</p>
                        <a href="{{ route('sertifikat.download', $s) }}" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="bi bi-download me-1"></i> Download File
                        </a>
                    </div>
                @else
                    <div style="text-align:center;padding:40px 20px;">
                        <i class="bi bi-file-earmark-image" style="font-size:3rem;color:#94a3b8;"></i>
                        <p style="margin-top:15px;color:#64748b;font-size:.9rem;">File sertifikat tidak ditemukan di server.</p>
                        <p style="color:#94a3b8;font-size:.8rem;margin-top:5px;">Hubungi administrator untuk informasi lebih lanjut.</p>
                    </div>
                @endif
            </div>
            <div class="preview-modal-footer">
                <span class="footer-info">
                    <i class="bi bi-award"></i>
                    {{ $s->jenis_sertifikat }} — {{ $s->uploader->name ?? '-' }}
                </span>
                <a href="{{ route('sertifikat.download', $s) }}" class="btn-preview-download" target="_blank">
                    <i class="bi bi-download"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="deleteModal{{ $s->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content modal-content-modern">
            <div class="modal-body modal-body-modern" style="padding:2rem 1.75rem 1.75rem">
                <div class="modal-icon modal-icon-delete">
                    <i class="bi bi-trash3-fill"></i>
                </div>
                <div class="modal-title-modern">Hapus Sertifikat?</div>
                <div class="modal-desc-modern">
                    Sertifikat <strong>{{ $s->nomor_sertifikat }}</strong> (<strong>{{ $s->jenis_sertifikat }}</strong>) akan dihapus secara permanen beserta file-nya.
                </div>
                <div class="modal-actions">
                    <form method="POST" action="{{ route('sertifikat.destroy', $s) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-modal btn-modal-delete">
                            <i class="bi bi-trash3 me-1"></i> Hapus
                        </button>
                    </form>
                    <button type="button" class="btn-modal btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
function applySearch(form) {
    var val = form.querySelector('input[name="search"]').value.trim();
    var url = new URL(window.location.href);
    if (val) {
        url.searchParams.set('search', val);
    } else {
        url.searchParams.delete('search');
    }
    url.searchParams.delete('page');
    window.location.href = url.toString();
    return false;
}

function handleModalPreviewError(id) {
    const wrapper = document.getElementById('previewWrapper' + id);
    if (wrapper) {
        const img = wrapper.querySelector('img');
        if (img) img.style.display = 'none';
        
        const errorDiv = document.getElementById('previewError' + id);
        if (errorDiv) errorDiv.style.display = 'block';
    }
}

// Reset modal saat ditutup untuk reload gambar saat dibuka lagi
document.querySelectorAll('.modal-preview').forEach(function(modal) {
    modal.addEventListener('hidden.bs.modal', function() {
        const img = this.querySelector('img');
        const errorDiv = this.querySelector('[id^="previewError"]');
        if (img) {
            img.style.display = 'block';
            // Tambah timestamp untuk force reload
            const src = img.src.split('?')[0];
            img.src = src + '?t=' + new Date().getTime();
        }
        if (errorDiv) errorDiv.style.display = 'none';
    });
});
</script>

@endsection