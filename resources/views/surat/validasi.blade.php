@extends('layouts.app')

@section('title', 'Validasi Surat')
@section('pageTitle', 'Validasi Surat Keterangan Aktif')

@section('content')
<style>
    :root { --tbb-h: 52px; }

    .val-header {
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
        border-radius: 20px; padding: 1.75rem 2rem; color: #fff;
        position: relative; overflow: hidden; margin-bottom: 1.5rem;
    }
    .val-header::before {
        content: ''; position: absolute; top: -50%; right: -5%;
        width: 320px; height: 320px; background: rgba(255,255,255,0.04); border-radius: 50%;
    }
    .val-header h2 { font-weight: 700; font-size: 1.35rem; margin-bottom: .25rem; position: relative; z-index: 1; }
    .val-header p { font-size: .85rem; opacity: .7; position: relative; z-index: 1; }
    .val-header .queue-pill {
        display: inline-flex; align-items: center; gap: .5rem;
        background: rgba(255,255,255,0.1); backdrop-filter: blur(8px);
        padding: .4rem .9rem; border-radius: 50px;
        font-size: .78rem; font-weight: 500; margin-top: .65rem;
        position: relative; z-index: 1;
    }
    .val-header .queue-pill .dot {
        width: 8px; height: 8px; border-radius: 50%; background: #a5b4fc;
        animation: pulse-dot 2s ease-in-out infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: .4; transform: scale(1.4); }
    }

    .val-toolbar { display: flex; align-items: center; gap: .75rem; margin-bottom: 1rem; flex-wrap: wrap; }
    .val-search { flex: 1; min-width: 250px; position: relative; }
    .val-search i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: .95rem; color: #94a3b8; pointer-events: none; }
    .val-search input {
        width: 100%; padding: .65rem 1rem .65rem 2.5rem; border-radius: 12px;
        border: 1.5px solid #e2e8f0; font-size: .88rem;
        font-family: 'Plus Jakarta Sans', sans-serif; color: #0f172a;
        background: #fff; transition: all .2s;
    }
    .val-search input:focus { outline: none; border-color: #4338ca; box-shadow: 0 0 0 3px rgba(67,56,202,.1); }
    .val-search input::placeholder { color: #94a3b8; }

    /* ===== TABLE WRAPPER — SCROLL CONTAINER ===== */
    .val-table-wrap {
        background: #fff; border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 1px 3px rgba(0,0,0,.03), 0 4px 12px rgba(0,0,0,.02);
        max-height: calc(100vh - 200px);
        overflow-y: auto; overflow-x: hidden;
    }
    /* Scrollbar tipis menyatu */
    .val-table-wrap::-webkit-scrollbar { width: 5px; }
    .val-table-wrap::-webkit-scrollbar-track { background: transparent; margin: 4px 0; }
    .val-table-wrap::-webkit-scrollbar-thumb { background: #d4d4d8; border-radius: 10px; }
    .val-table-wrap::-webkit-scrollbar-thumb:hover { background: #a1a1aa; }

    /* ===== BATCH BAR ===== */
    .table-batch-bar {
        display: flex; align-items: center; justify-content: space-between;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
        color: #fff; padding: .65rem 1.25rem;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        position: sticky; top: 0; z-index: 50;
        border-radius: 16px 16px 0 0;
    }
    .tbb-left { display: flex; align-items: center; gap: .6rem; }

    /* Checkbox di batch bar — putih custom */
    .tbb-cb { display: none; } /* hidden desktop, shown mobile */
    .tbb-cb input[type="checkbox"] {
        -webkit-appearance: none; -moz-appearance: none; appearance: none;
        width: 18px; height: 18px; border: 2px solid rgba(255,255,255,0.35);
        border-radius: 5px; background: rgba(255,255,255,0.08);
        cursor: pointer; transition: all .15s ease;
        position: relative; vertical-align: middle;
    }
    .tbb-cb input[type="checkbox"]:hover { border-color: rgba(255,255,255,0.6); background: rgba(255,255,255,0.15); }
    .tbb-cb input[type="checkbox"]:checked {
        background: #fff; border-color: transparent;
        box-shadow: 0 2px 6px rgba(0,0,0,.15);
    }
    .tbb-cb input[type="checkbox"]:checked::after {
        content: ''; position: absolute; left: 4.5px; top: 1px;
        width: 5.5px; height: 9px; border: solid #4338ca;
        border-width: 0 2.5px 2.5px 0; transform: rotate(45deg);
    }
    .tbb-cb input[type="checkbox"].cb-indeterminate {
        background: #fff; border-color: transparent;
        box-shadow: 0 2px 6px rgba(0,0,0,.15);
    }
    .tbb-cb input[type="checkbox"].cb-indeterminate::after {
        content: ''; position: absolute; left: 3.5px; top: 6px;
        width: 7px; height: 2.5px; background: #4338ca;
        border-radius: 2px; border: none; transform: none;
    }

    .tbb-icon {
        width: 30px; height: 30px; border-radius: 8px;
        background: rgba(255,255,255,0.12);
        display: inline-flex; align-items: center; justify-content: center; font-size: .88rem;
    }
    .tbb-count { font-size: .82rem; font-weight: 500; opacity: .7; transition: opacity .2s; }
    .tbb-count.active { opacity: 1; font-weight: 600; }
    .tbb-num {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 24px; height: 24px; padding: 0 .45rem;
        background: rgba(255,255,255,0.12); border-radius: 6px;
        font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace;
        font-size: .78rem; font-weight: 700; margin: 0 .1rem; transition: all .25s ease;
    }
    .tbb-num.has-selection { background: rgba(255,255,255,0.28); box-shadow: 0 0 0 2px rgba(255,255,255,0.2); }
    .tbb-right { display: flex; align-items: center; gap: .45rem; }
    .btn-tbb {
        display: inline-flex; align-items: center; gap: .35rem;
        padding: .4rem .8rem; border-radius: 8px;
        font-size: .76rem; font-weight: 600; border: none;
        cursor: pointer; transition: all .2s ease;
        text-decoration: none; color: #fff; white-space: nowrap;
    }
    .btn-tbb[disabled] { opacity: .35; cursor: not-allowed; transform: none !important; box-shadow: none !important; }
    .btn-tbb-approve { background: linear-gradient(135deg, #059669, #10b981); box-shadow: 0 2px 10px rgba(5,150,105,.3); }
    .btn-tbb-approve:not([disabled]):hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(5,150,105,.45); color: #fff; }
    .btn-tbb-reject { background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12); }
    .btn-tbb-reject:not([disabled]):hover { background: rgba(220,38,38,0.75); border-color: rgba(220,38,38,0.5); color: #fff; }

    /* ===== TABLE ===== */
    .val-table { width: 100%; border-collapse: collapse; }
    .val-table thead { position: sticky; top: var(--tbb-h); z-index: 40; }
    .val-table thead th {
        background: #f8fafc; padding: .85rem 1.25rem;
        font-size: .7rem; font-weight: 700; text-transform: uppercase;
        letter-spacing: .6px; color: #64748b;
        border-bottom: 1px solid #e2e8f0;
        text-align: center; white-space: nowrap;
    }
    .val-table thead th.th-cb { width: 48px; padding-left: 1.25rem; }
    .val-table thead th.th-left { text-align: left; }
    .val-table tbody tr { transition: background .15s; }
    .val-table tbody tr:hover { background: #f8fafc; }
    .val-table tbody tr.row-checked { background: #eef2ff !important; }
    .val-table tbody tr.row-checked:hover { background: #e0e7ff !important; }
    .val-table tbody td {
        padding: 1rem 1.25rem; font-size: .87rem; color: #1e293b;
        border-bottom: 1px solid #f1f5f9; vertical-align: middle;
    }
    .val-table tbody tr:last-child td { border-bottom: none; }
    .val-table tbody td.td-cb { width: 48px; padding-left: 1.25rem; }
    .val-table tbody td.td-center { text-align: center; }

    /* ===== CHECKBOX ===== */
    .cb-wrap { display: inline-flex; align-items: center; justify-content: center; }
    .cb-wrap input[type="checkbox"] {
        -webkit-appearance: none; -moz-appearance: none; appearance: none;
        width: 18px; height: 18px; border: 2px solid #cbd5e1; border-radius: 5px;
        background: #fff; cursor: pointer; transition: all .15s ease;
        position: relative; vertical-align: middle; z-index: 1;
    }
    .cb-wrap input[type="checkbox"]:hover { border-color: #818cf8; background: #f5f3ff; }
    .cb-wrap input[type="checkbox"]:checked {
        background: linear-gradient(135deg, #4338ca, #6366f1);
        border-color: transparent; box-shadow: 0 2px 6px rgba(67,56,202,.3);
    }
    .cb-wrap input[type="checkbox"]:checked::after {
        content: ''; position: absolute; left: 4.5px; top: 1px;
        width: 5.5px; height: 9px; border: solid #fff;
        border-width: 0 2.5px 2.5px 0; transform: rotate(45deg); z-index: 2;
    }
    .cb-wrap input[type="checkbox"].cb-indeterminate {
        background: linear-gradient(135deg, #4338ca, #6366f1);
        border-color: transparent; box-shadow: 0 2px 6px rgba(67,56,202,.3);
    }
    .cb-wrap input[type="checkbox"].cb-indeterminate::after {
        content: ''; position: absolute; left: 3.5px; top: 6px;
        width: 7px; height: 2.5px; background: #fff;
        border-radius: 2px; border: none; transform: none; z-index: 2;
    }

    .vt-nomor {
        font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace;
        font-size: .78rem; font-weight: 600; background: #f1f5f9;
        padding: .3rem .65rem; border-radius: 6px; color: #334155;
        display: inline-block; letter-spacing: .02em;
    }
    .vt-nama { font-weight: 700; color: #0f172a; margin-bottom: 2px; }
    .vt-nim { font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace; font-size: .76rem; color: #94a3b8; letter-spacing: .03em; }
    .vt-prodi { font-size: .84rem; color: #475569; }
    .vt-meta { font-size: .75rem; color: #94a3b8; margin-top: 2px; }

    .badge-val { display: inline-flex; align-items: center; gap: .3rem; padding: .3rem .7rem; border-radius: 50px; font-size: .68rem; font-weight: 700; letter-spacing: .03em; text-transform: capitalize; white-space: nowrap; }
    .badge-val .b-dot { width: 5px; height: 5px; border-radius: 50%; flex-shrink: 0; }
    .badge-diajukan { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .badge-diajukan .b-dot { background: #d97706; animation: pulse-dot 1.5s ease-in-out infinite; }
    .badge-direvisi { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
    .badge-direvisi .b-dot { background: #dc2626; }

    .vt-actions { display: flex; gap: .4rem; flex-wrap: nowrap; }
    .btn-vt {
        display: inline-flex; align-items: center; justify-content: center;
        width: 34px; height: 34px; border-radius: 9px;
        border: 1px solid #e2e8f0; background: #fff; color: #475569;
        font-size: .9rem; cursor: pointer; transition: all .2s ease;
        text-decoration: none; flex-shrink: 0;
    }
    .btn-vt:hover { transform: translateY(-1px); }
    .btn-vt-view:hover { border-color: #4338ca; color: #4338ca; background: #eef2ff; box-shadow: 0 2px 8px rgba(67,56,202,.15); }
    .btn-vt-approve { background: linear-gradient(135deg, #059669, #10b981); color: #fff; border: none; box-shadow: 0 2px 8px rgba(5,150,105,.2); }
    .btn-vt-approve:hover { box-shadow: 0 4px 14px rgba(5,150,105,.35); }
    .btn-vt-reject:hover { border-color: #f87171; color: #dc2626; background: #fef2f2; box-shadow: 0 2px 8px rgba(220,38,38,.12); }

    .vt-revisi-row { background: #fef7f7 !important; }
    .vt-revisi-note {
        margin-top: .5rem; background: #fee2e2; border: 1px solid #fecaca;
        border-radius: 8px; padding: .5rem .75rem; font-size: .78rem;
        color: #991b1b; line-height: 1.5;
        display: flex; align-items: flex-start; gap: .4rem;
    }
    .vt-revisi-note i { margin-top: 2px; flex-shrink: 0; color: #dc2626; }

    .empty-val { text-align: center; padding: 4rem 2rem; margin-top: 1.5rem; background: #fff; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 1px 3px rgba(0,0,0,.03); }
    .empty-val .empty-icon-wrap { width: 90px; height: 90px; border-radius: 50%; background: linear-gradient(135deg, #eef2ff, #e0e7ff); display: inline-flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; }
    .empty-val .empty-icon-wrap i { font-size: 2.2rem; color: #4338ca; opacity: .5; }
    .empty-val h5 { font-size: 1.05rem; font-weight: 700; color: #1e293b; margin-bottom: .4rem; }
    .empty-val p { font-size: .85rem; color: #94a3b8; }

    .val-pagination {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.25rem; border-top: 1px solid #f1f5f9;
        background: #fafafa; flex-wrap: wrap; gap: .75rem;
        position: sticky; bottom: 0; z-index: 40;
    }
    .val-pagination .pag-info { font-size: .8rem; color: #64748b; font-weight: 500; }
    .val-pagination .pag-links { display: flex; gap: .35rem; }
    .val-pagination .pag-links a,
    .val-pagination .pag-links span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 36px; height: 36px; padding: 0 .5rem; border-radius: 9px;
        font-size: .82rem; font-weight: 600; border: 1px solid #e2e8f0;
        background: #fff; color: #475569; text-decoration: none; transition: all .2s;
    }
    .val-pagination .pag-links a:hover { border-color: #4338ca; color: #4338ca; background: #eef2ff; }
    .val-pagination .pag-links .active { background: linear-gradient(135deg, #4338ca, #6366f1); color: #fff; border-color: transparent; box-shadow: 0 2px 8px rgba(67,56,202,.3); }
    .val-pagination .pag-links .disabled { opacity: .4; pointer-events: none; }

    /* ===== MODALS ===== */
    .modal-content-modern { border: none; border-radius: 20px; box-shadow: 0 25px 60px rgba(0,0,0,.15); overflow: hidden; }
    .modal-body-modern { padding: 2.5rem 2rem 2rem; text-align: center; }
    .modal-icon { width: 64px; height: 64px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 1.6rem; margin-bottom: 1.25rem; }
    .modal-icon-approve { background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #059669; box-shadow: 0 4px 14px rgba(5,150,105,.2); }
    .modal-icon-reject { background: linear-gradient(135deg, #fef2f2, #fee2e2); color: #dc2626; box-shadow: 0 4px 14px rgba(220,38,38,.2); }
    .modal-title-modern { font-size: 1.15rem; font-weight: 700; color: #0f172a; margin-bottom: .5rem; }
    .modal-desc-modern { font-size: .85rem; color: #64748b; line-height: 1.6; margin-bottom: 1.5rem; }
    .modal-desc-modern strong { color: #334155; }
    .modal-actions { display: flex; gap: .6rem; justify-content: center; flex-wrap: wrap; }
    .btn-modal { padding: .6rem 1.5rem; border-radius: 12px; font-size: .85rem; font-weight: 600; border: none; cursor: pointer; transition: all .2s ease; }
    .btn-modal-approve { background: linear-gradient(135deg, #059669, #10b981); color: #fff; box-shadow: 0 4px 14px rgba(5,150,105,.3); }
    .btn-modal-approve:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(5,150,105,.4); color: #fff; }
    .btn-modal-reject { background: linear-gradient(135deg, #dc2626, #ef4444); color: #fff; box-shadow: 0 4px 14px rgba(220,38,38,.3); }
    .btn-modal-reject:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(220,38,38,.4); color: #fff; }
    .btn-modal-cancel { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
    .btn-modal-cancel:hover { background: #f1f5f9; color: #0f172a; }
    .modal-body-form { padding: 2rem; text-align: left; }
    .modal-body-form .form-control { border-radius: 12px; border: 1.5px solid #e2e8f0; padding: .75rem 1rem; font-size: .88rem; transition: all .2s ease; }
    .modal-body-form .form-control:focus { border-color: #4338ca; box-shadow: 0 0 0 3px rgba(67,56,202,.1); }

    .batch-list { max-height: 180px; overflow-y: auto; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: .4rem; margin-bottom: 1rem; }
    .batch-list::-webkit-scrollbar { width: 4px; }
    .batch-list::-webkit-scrollbar-track { background: transparent; }
    .batch-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .batch-list-item { display: flex; align-items: center; gap: .6rem; padding: .4rem .6rem; border-radius: 8px; font-size: .8rem; transition: background .15s; }
    .batch-list-item:hover { background: #eef2ff; }
    .batch-list-item .bli-nomor { font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace; font-size: .72rem; font-weight: 600; color: #6366f1; background: #eef2ff; padding: .12rem .45rem; border-radius: 4px; white-space: nowrap; }
    .batch-list-item .bli-nama { font-weight: 600; color: #0f172a; }
    .batch-list-item .bli-nim { font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace; font-size: .72rem; color: #94a3b8; }
    .batch-list-more { text-align: center; padding: .3rem; font-size: .72rem; color: #94a3b8; font-weight: 500; border-top: 1px solid #e2e8f0; margin-top: .2rem; }

    .modal-pdf .modal-content { border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 60px rgba(0,0,0,.2); }
    .modal-pdf .modal-dialog { max-width: 850px; }
    .pdf-modal-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1.5rem; background: linear-gradient(135deg, #1e1b4b, #312e81); color: #fff; }
    .pdf-modal-header .pdf-title { display: flex; align-items: center; gap: .6rem; font-size: .88rem; font-weight: 600; }
    .pdf-modal-header .pdf-title i { font-size: 1rem; opacity: .7; }
    .pdf-modal-header .pdf-badge { background: rgba(255,255,255,.12); padding: .25rem .7rem; border-radius: 6px; font-size: .7rem; font-weight: 600; font-family: 'SF Mono', 'Fira Code', 'Consolas', monospace; letter-spacing: .02em; }
    .pdf-modal-close { width: 32px; height: 32px; border-radius: 8px; border: 1px solid rgba(255,255,255,.15); background: rgba(255,255,255,.08); color: #fff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all .2s ease; font-size: .9rem; }
    .pdf-modal-close:hover { background: rgba(255,255,255,.2); border-color: rgba(255,255,255,.3); }
    .pdf-frame-wrapper { background: #e2e8f0; height: 75vh; position: relative; }
    .pdf-frame-wrapper iframe { width: 100%; height: 100%; border: none; display: block; }
    .pdf-frame-wrapper .pdf-loading { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: .75rem; background: #f8fafc; z-index: 1; transition: opacity .3s ease; }
    .pdf-frame-wrapper .pdf-loading.hide { opacity: 0; pointer-events: none; }
    .pdf-frame-wrapper .pdf-loading .spinner-ring { width: 40px; height: 40px; border: 3px solid #e2e8f0; border-top-color: #4338ca; border-radius: 50%; animation: spin .8s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .pdf-frame-wrapper .pdf-loading span { font-size: .82rem; color: #94a3b8; font-weight: 500; }
    .pdf-modal-footer { display: flex; align-items: center; justify-content: space-between; padding: .75rem 1.5rem; background: #f8fafc; border-top: 1px solid #e2e8f0; }
    .pdf-modal-footer .footer-info { font-size: .75rem; color: #94a3b8; display: flex; align-items: center; gap: .4rem; }
    .btn-pdf-download { display: inline-flex; align-items: center; gap: .4rem; padding: .45rem 1rem; border-radius: 10px; font-size: .78rem; font-weight: 600; background: linear-gradient(135deg, #059669, #10b981); color: #fff; text-decoration: none; transition: all .2s ease; box-shadow: 0 2px 8px rgba(5,150,105,.25); }
    .btn-pdf-download:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(5,150,105,.35); color: #fff; }

    /* ================================================================
       MOBILE
       ================================================================ */
    @media (max-width: 991px) {
        .val-table-wrap {
            max-height: calc(100vh - 140px);
            min-height: 250px;
        }

        /* Tampilkan checkbox di batch bar, sembunyikan icon */
        .tbb-cb { display: inline-flex; align-items: center; }
        .tbb-icon { display: none; }

        /* Sembunyikan thead total */
        .val-table thead { display: none; position: static; }

        /* Baris jadi kartu */
        .val-table, .val-table tbody, .val-table tr, .val-table td {
            display: block; width: 100%;
        }
        .val-table tbody tr {
            padding: .85rem;
            border-bottom: 1px solid #f1f5f9;
            position: relative;
        }

        /* SEMUA teks rata kiri */
        .val-table tbody td {
            padding: 0; border-bottom: none;
            text-align: left !important;
        }
        .val-table tbody td::before { display: none !important; }

        /* Checkbox kanan atas */
        .val-table tbody td.td-cb {
            position: absolute; top: .85rem; right: .85rem;
            width: auto; padding: 0;
        }
        .cb-wrap input[type="checkbox"] { width: 22px; height: 22px; }
        .cb-wrap input[type="checkbox"]:checked::after { left: 5.5px; top: 1.5px; width: 6.5px; height: 11px; }
        .cb-wrap input[type="checkbox"].cb-indeterminate::after { left: 4px; top: 8px; width: 8px; height: 3px; }

        /* Pemohon */
        .val-table tbody td[data-label="Pemohon"] { margin-bottom: .15rem; padding-right: 2.8rem; }
        .val-table tbody td[data-label="Pemohon"] .vt-nama { font-size: .88rem; margin-bottom: 0; }
        .val-table tbody td[data-label="Pemohon"] .vt-nim { font-size: .7rem; }

        /* Prodi */
        .val-table tbody td[data-label="Prodi"] { margin-bottom: .2rem; }
        .val-table tbody td[data-label="Prodi"] .vt-prodi { font-size: .76rem; }
        .val-table tbody td[data-label="Prodi"] .vt-meta { display: none; }

        /* Nomor + Semester satu baris */
        .val-table tbody td[data-label="No. Surat"],
        .val-table tbody td[data-label="Semester"] {
            display: inline-block; vertical-align: middle; margin-bottom: .3rem;
        }
        .val-table tbody td[data-label="No. Surat"] .vt-nomor { font-size: .68rem; padding: .18rem .45rem; }
        .val-table tbody td[data-label="Semester"] { font-size: .74rem; color: #64748b; font-weight: 500; margin-left: .25rem; }
        .val-table tbody td[data-label="No. Surat"]::after { content: '·'; margin: 0 .15rem; color: #cbd5e1; font-weight: 400; }

        /* Status */
        .val-table tbody td[data-label="Status"] { margin-bottom: .55rem; }

        /* Aksi — tombol lebar + teks */
        .val-table tbody td[data-label="Aksi"] { padding-top: .5rem; margin-top: .15rem; border-top: 1px solid #f1f5f9; }
        .vt-actions { width: 100%; justify-content: stretch; gap: .35rem; }
        .vt-actions .btn-vt {
            flex: 1; width: auto; height: auto;
            padding: .55rem .4rem; border-radius: 8px;
            font-size: .72rem; justify-content: center; gap: .3rem;
        }
        .vt-actions .btn-vt-view::after { content: 'Lihat'; font-size: .72rem; margin-left: .2rem; font-weight: 600; }
        .vt-actions .btn-vt-approve::after { content: 'Setujui'; font-size: .72rem; margin-left: .2rem; font-weight: 600; }
        .vt-actions .btn-vt-reject::after { content: 'Tolak'; font-size: .72rem; margin-left: .2rem; font-weight: 600; }

        .vt-revisi-note { margin-top: .3rem; font-size: .7rem; padding: .35rem .55rem; }
        .val-table tbody tr:last-child { border-bottom: none; }
    }

    @media (max-width: 576px) {
        .val-toolbar { flex-direction: column; }
        .val-search { min-width: 100%; }
        .val-pagination { flex-direction: column; text-align: center; align-items: center; }
        .table-batch-bar { flex-direction: column; gap: .45rem; padding: .55rem .85rem; }
        .tbb-left { width: 100%; justify-content: center; }
        .tbb-right { width: 100%; justify-content: center; flex-wrap: wrap; }
        .val-header { padding: 1.25rem; }
        .val-header h2 { font-size: 1.15rem; }
    }
</style>

@if($surats->isEmpty())
<div class="empty-val">
    <div class="empty-icon-wrap"><i class="bi bi-check-all"></i></div>
    <h5>Semua Surat Sudah Divalidasi</h5>
    <p>Tidak ada surat yang menunggu validasi saat ini.</p>
</div>
@else
<div class="val-header">
    <h2><i class="bi bi-shield-check me-2" style="opacity:.7"></i>Validasi Surat</h2>
    <p>Periksa dan verifikasi surat keterangan aktif yang diajukan</p>
    <div class="queue-pill">
        <span class="dot"></span>
        {{ $surats->total() }} surat menunggu validasi
    </div>
</div>

<form method="GET" action="{{ route('validasi.index') }}" class="val-toolbar">
    <div class="val-search">
        <i class="bi bi-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIM, atau nomor surat..." autofocus>
    </div>
    @if(request('search'))
    <a href="{{ route('validasi.index') }}" class="btn-vt" style="width:auto;height:auto;padding:.55rem 1rem;font-size:.82rem;font-weight:600;">
        <i class="bi bi-x-lg me-1"></i> Reset
    </a>
    @endif
</form>

<div class="val-table-wrap">
    <!-- BATCH BAR — checkbox "Pilih Semua" ada di kiri -->
    <div class="table-batch-bar">
        <div class="tbb-left">
            <div class="tbb-cb">
                <input type="checkbox" id="selectAllMobile" title="Pilih semua">
            </div>
            <div class="tbb-icon"><i class="bi bi-check2-square"></i></div>
            <span class="tbb-count" id="tbbCountLabel"><span class="tbb-num" id="batchCount">0</span> surat dipilih</span>
        </div>
        <div class="tbb-right">
            <button type="button" class="btn-tbb btn-tbb-approve" id="btnBatchApprove" disabled>
                <i class="bi bi-check-lg"></i> Setujui Terpilih
            </button>
            <button type="button" class="btn-tbb btn-tbb-reject" id="btnBatchReject" disabled>
                <i class="bi bi-arrow-return-left"></i> Tolak Terpilih
            </button>
        </div>
    </div>

    <table class="val-table">
        <thead>
            <tr>
                <th class="th-cb">
                    <div class="cb-wrap">
                        <input type="checkbox" id="selectAll" title="Pilih semua">
                    </div>
                </th>
                <th class="th-left">Pemohon</th>
                <th>No. Surat</th>
                <th class="th-left">Prodi</th>
                <th>Semester</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($surats as $s)
            <tr id="row-{{ $s->id }}" class="{{ $s->status === 'direvisi' ? 'vt-revisi-row' : '' }}">
                <td class="td-cb" data-label="">
                    <div class="cb-wrap">
                        <input type="checkbox" class="row-check" data-id="{{ $s->id }}" data-nama="{{ $s->nama_mahasiswa }}" data-nim="{{ $s->nim }}" data-nomor="{{ $s->nomor_surat }}" value="{{ $s->id }}">
                    </div>
                </td>
                <td data-label="Pemohon">
                    <div class="vt-nama">{{ $s->nama_mahasiswa }}</div>
                    <div class="vt-nim">{{ $s->nim }}</div>
                    @if($s->status === 'direvisi' && $s->catatan_revisi)
                    <div class="vt-revisi-note">
                        <i class="bi bi-chat-left-text-fill"></i>
                        <span>{{ $s->catatan_revisi }}</span>
                    </div>
                    @endif
                </td>
                <td class="td-center" data-label="No. Surat">
                    <span class="vt-nomor">{{ $s->nomor_surat }}</span>
                </td>
                <td data-label="Prodi">
                    <div class="vt-prodi">{{ $s->prodi }}</div>
                    <div class="vt-meta">{{ $s->tahun_ajaran }}</div>
                </td>
                <td class="td-center" data-label="Semester">
                    <span style="font-weight:600">{{ $s->semester }}</span>
                </td>
                <td class="td-center" data-label="Status">
                    <span class="badge-val badge-{{ $s->status }}">
                        <span class="b-dot"></span>
                        {{ $s->status_label }}
                    </span>
                </td>
                <td class="td-center" data-label="Aksi">
                    <div class="vt-actions" style="justify-content:center">
                        <button type="button" class="btn-vt btn-vt-view" data-bs-toggle="modal" data-bs-target="#pdfPreviewModal{{ $s->id }}" title="Lihat PDF">
                            <i class="bi bi-eye"></i>
                        </button>
                        <button type="button" class="btn-vt btn-vt-approve" data-bs-toggle="modal" data-bs-target="#approveModal{{ $s->id }}" title="Setujui">
                            <i class="bi bi-check-lg"></i>
                        </button>
                        <button type="button" class="btn-vt btn-vt-reject" data-bs-toggle="modal" data-bs-target="#reviseModal{{ $s->id }}" title="Tolak">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($surats->hasPages())
    <div class="val-pagination">
        <div class="pag-info">
            Menampilkan {{ $surats->firstItem() }}–{{ $surats->lastItem() }} dari {{ $surats->total() }}
        </div>
        <div class="pag-links">
            {{ $surats->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>
@endif

<!-- ===== MODAL PER-SURAT ===== -->
@foreach($surats as $s)
<div class="modal fade modal-pdf" id="pdfPreviewModal{{ $s->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="pdf-modal-header">
                <div class="pdf-title"><i class="bi bi-file-earmark-pdf"></i> Preview Surat <span class="pdf-badge">{{ $s->nomor_surat }}</span></div>
                <button type="button" class="pdf-modal-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="pdf-frame-wrapper">
                <div class="pdf-loading" id="pdfLoading{{ $s->id }}"><div class="spinner-ring"></div><span>Memuat dokumen...</span></div>
                <iframe src="{{ route('surat.preview', $s) }}" onload="document.getElementById('pdfLoading{{ $s->id }}').classList.add('hide')"></iframe>
            </div>
            <div class="pdf-modal-footer">
                <span class="footer-info"><i class="bi bi-info-circle"></i> {{ $s->nama_mahasiswa }} — {{ $s->nim }}</span>
                <a href="{{ route('surat.download', $s) }}" class="btn-pdf-download" target="_blank"><i class="bi bi-download"></i> Download</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="approveModal{{ $s->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <div class="modal-body modal-body-modern">
                <div class="modal-icon modal-icon-approve"><i class="bi bi-check-circle-fill"></i></div>
                <div class="modal-title-modern">Setujui Surat?</div>
                <div class="modal-desc-modern">Surat <strong>{{ $s->nomor_surat }}</strong> atas nama <strong>{{ $s->nama_mahasiswa }}</strong> akan disetujui dan QR Code verifikasi akan ditambahkan.</div>
                <div class="modal-actions">
                    <form method="POST" action="{{ route('validasi.approve', $s) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="approved" value="1">
                        <button type="submit" class="btn-modal btn-modal-approve"><i class="bi bi-check-lg me-1"></i> Ya, Setujui</button>
                    </form>
                    <button type="button" class="btn-modal btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reviseModal{{ $s->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <form method="POST" action="{{ route('validasi.revise', $s) }}">
                @csrf
                <div class="modal-body modal-body-form">
                    <div style="text-align:center;margin-bottom:1.25rem">
                        <div class="modal-icon modal-icon-reject"><i class="bi bi-x-circle-fill"></i></div>
                        <div class="modal-title-modern">Tolak Surat</div>
                        <div class="modal-desc-modern" style="margin-bottom:1rem">Berikan catatan mengapa surat <strong>{{ $s->nomor_surat }}</strong> ditolak:</div>
                    </div>
                    <textarea name="catatan_revisi" class="form-control mb-3" rows="3" required placeholder="Tulis catatan revisi...">{{ old('catatan_revisi') }}</textarea>
                    <div class="modal-actions">
                        <button type="submit" class="btn-modal btn-modal-reject"><i class="bi bi-x-lg me-1"></i> Tolak</button>
                        <button type="button" class="btn-modal btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- ===== MODAL BATCH ===== -->
<div class="modal fade" id="batchApproveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <form method="POST" action="{{ route('validasi.batch-approve') }}" id="batchApproveForm">
                @csrf
                <div class="modal-body modal-body-modern">
                    <div class="modal-icon modal-icon-approve"><i class="bi bi-check-circle-fill"></i></div>
                    <div class="modal-title-modern">Setujui Surat Terpilih?</div>
                    <div class="modal-desc-modern" style="margin-bottom:1rem"><strong id="batchApproveCountText">0</strong> surat akan disetujui dan QR Code verifikasi akan ditambahkan ke masing-masing surat.</div>
                    <div class="batch-list" id="batchApproveList"></div>
                    <div class="modal-actions">
                        <button type="submit" class="btn-modal btn-modal-approve"><i class="bi bi-check-lg me-1"></i> Ya, Setujui Semua</button>
                        <button type="button" class="btn-modal btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="batchRejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-modern">
            <form method="POST" action="{{ route('validasi.batch-reject') }}" id="batchRejectForm">
                @csrf
                <div class="modal-body modal-body-form">
                    <div style="text-align:center;margin-bottom:1.25rem">
                        <div class="modal-icon modal-icon-reject"><i class="bi bi-x-circle-fill"></i></div>
                        <div class="modal-title-modern">Tolak Surat Terpilih</div>
                        <div class="modal-desc-modern" style="margin-bottom:1rem"><strong id="batchRejectCountText">0</strong> surat akan ditolak. Catatan yang sama diterapkan ke semua surat terpilih.</div>
                    </div>
                    <div class="batch-list" id="batchRejectList"></div>
                    <textarea name="catatan_revisi" class="form-control mb-3" rows="3" required placeholder="Tulis catatan revisi untuk semua surat terpilih..." id="batchRejectNote">{{ old('catatan_revisi') }}</textarea>
                    <div class="modal-actions">
                        <button type="submit" class="btn-modal btn-modal-reject"><i class="bi bi-x-lg me-1"></i> Tolak Semua</button>
                        <button type="button" class="btn-modal btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    var selectAllDesktop = document.getElementById('selectAll');
    var selectAllMobile = document.getElementById('selectAllMobile');
    var rowChecks = document.querySelectorAll('.row-check');
    var batchCountEl = document.getElementById('batchCount');
    var tbbCountLabel = document.getElementById('tbbCountLabel');
    var btnBatchApprove = document.getElementById('btnBatchApprove');
    var btnBatchReject = document.getElementById('btnBatchReject');

    if (!batchCountEl) return;

    function getSelected() {
        var items = [];
        rowChecks.forEach(function(cb) {
            if (cb.checked) {
                items.push({ id: cb.getAttribute('data-id'), nama: cb.getAttribute('data-nama'), nim: cb.getAttribute('data-nim'), nomor: cb.getAttribute('data-nomor') });
            }
        });
        return items;
    }

    function syncSelectAllCbs(checked, indeterminate) {
        // Sync kedua checkbox (desktop & mobile)
        [selectAllDesktop, selectAllMobile].forEach(function(cb) {
            if (!cb) return;
            cb.classList.remove('cb-indeterminate');
            if (indeterminate) {
                cb.checked = false;
                cb.classList.add('cb-indeterminate');
            } else {
                cb.checked = checked;
            }
        });
    }

    function updateAll() {
        var selected = getSelected();
        var total = rowChecks.length;
        var checked = selected.length;

        batchCountEl.textContent = checked;
        if (checked > 0) {
            batchCountEl.classList.add('has-selection');
            tbbCountLabel.classList.add('active');
            btnBatchApprove.removeAttribute('disabled');
            btnBatchReject.removeAttribute('disabled');
        } else {
            batchCountEl.classList.remove('has-selection');
            tbbCountLabel.classList.remove('active');
            btnBatchApprove.setAttribute('disabled', 'disabled');
            btnBatchReject.setAttribute('disabled', 'disabled');
        }

        // Sync select all state
        if (checked === 0) { syncSelectAllCbs(false, false); }
        else if (checked === total) { syncSelectAllCbs(true, false); }
        else { syncSelectAllCbs(false, true); }

        // Highlight baris
        rowChecks.forEach(function(cb) {
            var row = document.getElementById('row-' + cb.getAttribute('data-id'));
            if (row) { if (cb.checked) { row.classList.add('row-checked'); } else { row.classList.remove('row-checked'); } }
        });

        fillList('batchApproveList', 'batchApproveCountText', selected);
        fillList('batchRejectList', 'batchRejectCountText', selected);
        syncInputs('batchApproveForm', selected);
        syncInputs('batchRejectForm', selected);
    }

    function fillList(listId, countId, items) {
        var listEl = document.getElementById(listId);
        var countEl = document.getElementById(countId);
        if (!listEl || !countEl) return;
        countEl.textContent = items.length;
        var max = 5, html = '';
        for (var i = 0; i < Math.min(items.length, max); i++) {
            var it = items[i];
            html += '<div class="batch-list-item"><span class="bli-nomor">' + it.nomor + '</span><span class="bli-nama">' + it.nama + '</span><span class="bli-nim">' + it.nim + '</span></div>';
        }
        if (items.length > max) { html += '<div class="batch-list-more">+' + (items.length - max) + ' surat lainnya</div>'; }
        listEl.innerHTML = html;
    }

    function syncInputs(formId, items) {
        var form = document.getElementById(formId);
        if (!form) return;
        var old = form.querySelectorAll('input[name="ids[]"]');
        for (var i = 0; i < old.length; i++) { old[i].remove(); }
        for (var j = 0; j < items.length; j++) {
            var inp = document.createElement('input');
            inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = items[j].id;
            form.appendChild(inp);
        }
    }

    // Event: select all desktop
    if (selectAllDesktop) {
        selectAllDesktop.addEventListener('change', function() {
            var v = this.checked;
            rowChecks.forEach(function(cb) { cb.checked = v; });
            updateAll();
        });
    }

    // Event: select all mobile (di batch bar)
    if (selectAllMobile) {
        selectAllMobile.addEventListener('change', function() {
            var v = this.checked;
            rowChecks.forEach(function(cb) { cb.checked = v; });
            updateAll();
        });
    }

    // Event: checkbox per baris
    rowChecks.forEach(function(cb) {
        cb.addEventListener('change', function() { updateAll(); });
    });

    btnBatchApprove.addEventListener('click', function() { if (getSelected().length === 0) return; new bootstrap.Modal(document.getElementById('batchApproveModal')).show(); });
    btnBatchReject.addEventListener('click', function() { if (getSelected().length === 0) return; new bootstrap.Modal(document.getElementById('batchRejectModal')).show(); });

    var brm = document.getElementById('batchRejectModal');
    if (brm) { brm.addEventListener('hidden.bs.modal', function() { var ta = document.getElementById('batchRejectNote'); if (ta) ta.value = ''; }); }

    updateAll();
})();
</script>
@endsection