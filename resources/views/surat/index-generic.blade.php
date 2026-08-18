@extends('layouts.app')

@section('title', 'Daftar Surat')
@section('pageTitle', 'Daftar Surat')

@section('content')
<style>
    .history-header {
        background: linear-gradient(135deg, #064e3b 0%, #059669 50%, #34d399 100%);
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
        width: 8px; height: 8px; border-radius: 50%;
        background: #6ee7b7;
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

    /* ===== Filter Bar ===== */
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
        border-color: #475569;
        box-shadow: 0 0 0 3px rgba(71,85,105,0.1);
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
        background: #475569;
        border: none;
        color: #fff;
        padding: .55rem 14px;
        font-size: .8rem;
        cursor: pointer;
        transition: background .15s;
        flex-shrink: 0;
        display: flex; align-items: center;
    }
    .filter-search .search-btn:hover { background: #334155; }

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
    .pill-dot-gray { background: #94a3b8; }
    .filter-pill.active .pill-dot-amber { background: #fbbf24; }
    .filter-pill.active .pill-dot-green { background: #6ee7b7; }
    .filter-pill.active .pill-dot-red { background: #fca5a5; }
    .filter-pill.active .pill-dot-gray { background: #cbd5e1; }

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

    /* Active Filter Chips */
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

    /* ===== Table ===== */
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
    .sort-link:hover { color: #475569; }
    .sort-link i { font-size: .6rem; }

    .table-modern tbody tr { transition: all .2s ease; }
    .table-modern tbody tr:hover { background: #f8fafc; }
    .table-modern tbody td {
        padding: 1rem 1.1rem;
        font-size: .85rem;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    .table-modern tbody td:first-child { padding-left: 1.5rem; }
    .table-modern tbody tr:last-child td { border-bottom: none; }

    .nomor-surat {
        font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace;
        font-size: .78rem; font-weight: 600;
        color: #334155;
        background: #f1f5f9;
        padding: .3rem .7rem;
        border-radius: 8px;
        letter-spacing: .02em;
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
        border-color: #475569;
        color: #475569;
        background: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(71,85,105,0.15);
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
    .btn-action.btn-cancel-val:hover {
        border-color: #64748b; color: #475569;
        background: #f1f5f9;
        box-shadow: 0 2px 8px rgba(100,116,139,0.15);
    }
    .btn-action.btn-delete:hover {
        border-color: #dc2626; color: #dc2626;
        background: #fef2f2;
        box-shadow: 0 2px 8px rgba(220,38,38,0.15);
    }

    .unit-tag {
        display: inline-block;
        font-size: .78rem;
        color: #475569;
        background: #f8fafc;
        padding: .2rem .65rem;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }
    .nama-surat-text {
        font-weight: 600;
        color: #0f172a;
        font-size: .85rem;
    }

    /* ===== Pagination ===== */
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
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #334155;
    }
    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #334155, #475569);
        border-color: transparent;
        color: #fff;
        box-shadow: 0 2px 8px rgba(71,85,105,0.3);
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
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }
    .empty-state .empty-icon-wrapper i {
        font-size: 2rem;
        color: #475569;
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

    /* ===== PDF Preview Modal ===== */
    .modal-pdf .modal-content {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 25px 60px rgba(0,0,0,0.2);
    }
    .modal-pdf .modal-dialog { max-width: 850px; }
    .pdf-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #1e293b, #334155);
        color: #fff;
    }
    .pdf-modal-header .pdf-title {
        display: flex;
        align-items: center;
        gap: .6rem;
        font-size: .88rem;
        font-weight: 600;
    }
    .pdf-modal-header .pdf-title i { font-size: 1rem; opacity: .7; }
    .pdf-modal-header .pdf-badge {
        background: rgba(255,255,255,0.12);
        padding: .25rem .7rem;
        border-radius: 6px;
        font-size: .7rem;
        font-weight: 600;
        font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace;
        letter-spacing: .02em;
    }
    .pdf-modal-close {
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
    .pdf-modal-close:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.3);
    }
    .pdf-frame-wrapper {
        background: #e2e8f0;
        height: 75vh;
        position: relative;
    }
    .pdf-frame-wrapper iframe {
        width: 100%; height: 100%;
        border: none; display: block;
    }
    .pdf-frame-wrapper .pdf-loading {
        position: absolute; inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: .75rem;
        background: #f8fafc;
        z-index: 1;
        transition: opacity .3s ease;
    }
    .pdf-frame-wrapper .pdf-loading.hide {
        opacity: 0;
        pointer-events: none;
    }
    .pdf-frame-wrapper .pdf-loading .spinner-ring {
        width: 40px; height: 40px;
        border: 3px solid #e2e8f0;
        border-top-color: #475569;
        border-radius: 50%;
        animation: spin .8s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .pdf-frame-wrapper .pdf-loading span {
        font-size: .82rem;
        color: #94a3b8;
        font-weight: 500;
    }
    .pdf-modal-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .75rem 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }
    .pdf-modal-footer .footer-info {
        font-size: .75rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: .4rem;
    }
    .btn-pdf-download {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .45rem 1rem;
        border-radius: 10px;
        font-size: .78rem;
        font-weight: 600;
        background: linear-gradient(135deg, #334155, #475569);
        color: #fff;
        text-decoration: none;
        transition: all .2s ease;
        box-shadow: 0 2px 8px rgba(71,85,105,0.25);
    }
    .btn-pdf-download:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(71,85,105,0.35);
        color: #fff;
    }

    /* ===== Modals ===== */
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
    .modal-icon-cancel {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        color: #64748b;
        box-shadow: 0 4px 14px rgba(100,116,139,0.2);
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
    .btn-modal-cancel-action {
        background: linear-gradient(135deg, #64748b, #475569);
        color: #fff;
        box-shadow: 0 4px 14px rgba(100,116,139,0.3);
    }
    .btn-modal-cancel-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(100,116,139,0.4);
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
        .modal-pdf .modal-dialog { margin: .5rem; }
        .pdf-frame-wrapper { height: 65vh; }
        .pagination-wrapper {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }
</style>

@php
    $canManage = session('active_unit_role') === 'staff' || auth()->user()->isSuperadmin();
    $canCancel = session('active_unit_role') === 'kepala' || auth()->user()->isSuperadmin();

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
            return '<i class="bi bi-chevron-up" style="color:#475569"></i>';
        }
        return '<i class="bi bi-chevron-down" style="color:#475569"></i>';
    };
@endphp

{{-- Header --}}
<div class="history-header">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h2><i class="bi bi-file-earmark-text me-2" style="opacity:.7"></i>Daftar Surat</h2>
            <p class="mb-2">Riwayat pengajuan surat unit ini</p>
            <div class="stat-pill">
                <span class="dot"></span>
                {{ $surats->total() }} surat tercatat
            </div>
        </div>
        @if($canManage)
            <a href="{{ route('surat.create') }}" class="btn-create-modern">
                <i class="bi bi-plus-lg"></i> Ajukan Surat Baru
            </a>
        @endif
    </div>
    <i class="bi bi-file-earmark-ruled header-icon"></i>
</div>

{{-- Filter Bar --}}
<div class="filter-bar">
    <div class="filter-row">
        {{-- Search --}}
        <div class="filter-search">
            <form onsubmit="return applySearch(this)">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama surat atau nomor surat..." autocomplete="off">
                @if(request('search'))
                <a href="{{ $filterUrl('search', '') }}" class="search-clear" title="Hapus pencarian"><i class="bi bi-x-circle-fill"></i></a>
                @endif
                <button type="submit" class="search-btn"><i class="bi bi-arrow-right"></i></button>
            </form>
        </div>

        <div class="filter-divider d-none d-lg-block"></div>

        {{-- Status Filter --}}
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

        {{-- Reset --}}
        @if(request('search') || request('status') || request('sort'))
        <a href="{{ url()->current() }}" class="filter-reset" title="Reset semua filter & sort">
            <i class="bi bi-arrow-counterclockwise"></i> Reset
        </a>
        @endif
    </div>

    {{-- Active Filter Chips --}}
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
                    <th><a href="{{ $sortUrl('nomor_surat') }}" class="sort-link">Nomor Surat {!! $sortIcon('nomor_surat') !!}</a></th>
                    <th><a href="{{ $sortUrl('nama_surat') }}" class="sort-link">Nama Surat {!! $sortIcon('nama_surat') !!}</a></th>
                    <th>Tanggal Surat</th>
                    @if(auth()->user()->isSuperadmin())
                    <th>Unit</th>
                    @endif
                    <th><a href="{{ $sortUrl('status') }}" class="sort-link">Status {!! $sortIcon('status') !!}</a></th>
                    <th><a href="{{ $sortUrl('created_at') }}" class="sort-link">Diajukan {!! $sortIcon('created_at') !!}</a></th>
                    <th style="text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surats as $item)
                <tr>
                    <td><span class="nomor-surat">{{ $item->nomor_surat }}</span></td>
                    <td><span class="nama-surat-text">{{ $item->nama_surat }}</span></td>
                    <td>
                        @if($item->tanggal_surat)
                            <span class="date-text">
                                <i class="bi bi-calendar3"></i>
                                {{ $item->tanggal_surat->translatedFormat('d M Y') }}
                            </span>
                        @else
                            <span class="date-text" style="opacity:.5">-</span>
                        @endif
                    </td>
                    @if(auth()->user()->isSuperadmin())
                    <td><span class="unit-tag">{{ $item->unit ? $item->unit->name : '-' }}</span></td>
                    @endif
                    <td>
                        <span class="badge-modern badge-{{ $item->status }}">
                            <span class="badge-dot"></span>
                            {{ $item->status_label }}
                        </span>
                    </td>
                    <td>
                        <span class="date-text">
                            <i class="bi bi-calendar3"></i>
                            {{ $item->created_at->format('d M Y') }}
                        </span>
                    </td>
                    <td>
                        <div class="action-group">
                            @if($item->status === 'direvisi' && $canManage)
                            <a href="{{ route('surat.edit', $item) }}" class="btn-action btn-edit" title="Edit & Ajukan Ulang">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            @endif

                            <button type="button" class="btn-action btn-preview" data-bs-toggle="modal" data-bs-target="#pdfPreviewModal{{ $item->id }}" title="Preview PDF">
                                <i class="bi bi-eye"></i>
                            </button>

                            <a href="{{ route('surat.show', $item) }}" class="btn-action" title="Detail">
                                <i class="bi bi-info-circle"></i>
                            </a>

                            <a href="{{ route('surat.download', $item) }}" class="btn-action btn-download" title="Download">
                                <i class="bi bi-download"></i>
                            </a>

                            @if(($item->status === 'disetujui' || $item->status === 'direvisi') && $canCancel)
                            <button type="button" class="btn-action btn-cancel-val" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $item->id }}" title="Batalkan Validasi">
                                <i class="bi bi-slash-circle"></i>
                            </button>
                            @endif

                            @if($canManage)
                            <button type="button" class="btn-action btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" title="Hapus">
                                <i class="bi bi-trash3"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>

                {{-- Modal Preview PDF --}}
                <div class="modal fade modal-pdf" id="pdfPreviewModal{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="pdf-modal-header">
                                <div class="pdf-title">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                    Preview Surat
                                    <span class="pdf-badge">{{ $item->nomor_surat }}</span>
                                </div>
                                <button type="button" class="pdf-modal-close" data-bs-dismiss="modal">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                            <div class="pdf-frame-wrapper">
                                <div class="pdf-loading" id="pdfLoading{{ $item->id }}">
                                    <div class="spinner-ring"></div>
                                    <span>Memuat dokumen...</span>
                                </div>
                                <iframe src="{{ route('surat.preview', $item) }}" onload="document.getElementById('pdfLoading{{ $item->id }}').classList.add('hide')"></iframe>
                            </div>
                            <div class="pdf-modal-footer">
                                <span class="footer-info">
                                    <i class="bi bi-file-text"></i>
                                    {{ $item->nama_surat }}
                                </span>
                                <a href="{{ route('surat.download', $item) }}" class="btn-pdf-download" target="_blank">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Batalkan Validasi --}}
                @if($canCancel)
                <div class="modal fade" id="cancelModal{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content modal-content-modern">
                            <div class="modal-body modal-body-modern" style="padding:2rem 1.75rem 1.75rem">
                                <div class="modal-icon modal-icon-cancel">
                                    <i class="bi bi-slash-circle"></i>
                                </div>
                                <div class="modal-title-modern">Batalkan Validasi?</div>
                                <div class="modal-desc-modern">
                                    Surat <strong>{{ $item->nomor_surat }}</strong> akan dikeluarkan dari antrian validasi.
                                </div>
                                <div class="modal-actions">
                                    <form method="POST" action="{{ route('validasi.cancel', $item) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-modal btn-modal-cancel-action">
                                            <i class="bi bi-slash-circle me-1"></i> Ya, Batalkan
                                        </button>
                                    </form>
                                    <button type="button" class="btn-modal btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Modal Hapus --}}
                @if($canManage)
                <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content modal-content-modern">
                            <div class="modal-body modal-body-modern" style="padding:2rem 1.75rem 1.75rem">
                                <div class="modal-icon modal-icon-delete">
                                    <i class="bi bi-trash3-fill"></i>
                                </div>
                                <div class="modal-title-modern">Hapus Surat?</div>
                                <div class="modal-desc-modern">
                                    Surat <strong>{{ $item->nomor_surat }}</strong> (<em>{{ $item->nama_surat }}</em>) akan dihapus secara permanen.
                                </div>
                                <div class="modal-actions">
                                    <form method="POST" action="{{ route('surat.destroy', $item) }}" class="d-inline">
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
                @endif

                @empty
                <tr>
                    <td colspan="{{ auth()->user()->isSuperadmin() ? 7 : 6 }}">
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

    @if($surats->hasPages())
    <div class="pagination-wrapper">
        <span class="pagination-info">
            Menampilkan <strong>{{ $surats->firstItem() }}–{{ $surats->lastItem() }}</strong> dari <strong>{{ $surats->total() }}</strong> data
        </span>
        {{ $surats->appends(request()->query())->links() }}
    </div>
    @elseif($surats->isNotEmpty())
    <div style="padding:1rem 1.5rem;background:#f8fafc;border-top:1px solid #f1f5f9;font-size:.78rem;color:#94a3b8">
        {{ $surats->count() }} data ditampilkan
    </div>
    @endif
</div>

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
</script>

@endsection