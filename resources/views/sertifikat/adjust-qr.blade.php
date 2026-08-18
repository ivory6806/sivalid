@extends('layouts.app')
@section('title', 'Atur Posisi QR Code')
@section('pageTitle', 'Atur Posisi QR Code')
@section('content')

@php
    $verifyUrl = url('/verify/sertifikat/' . $sertifikat->qr_token);
    $qrPreviewSrc = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=1&ecc=H&data=' . urlencode($verifyUrl);
@endphp

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem;flex-wrap:wrap;gap:10px">
    <div>
        <h2 style="font-weight:700;font-size:1.2rem;margin-bottom:2px">{{ $sertifikat->jenis_sertifikat }}</h2>
        <div style="font-size:.85rem;color:#64748b">
            {{ $sertifikat->nomor_sertifikat }} &middot; 
            <span class="badge bg-info" style="font-size:.65rem;vertical-align:middle">PNG/JPG</span>
        </div>
    </div>
    <a href="{{ route('validasi-sertifikat.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

@if(session('error'))
<div class="alert alert-danger" style="white-space:pre-line">
    <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
</div>
@endif

<div class="row g-4">
    <!-- ===== PREVIEW + DRAG AREA ===== -->
    <div class="col-lg-8">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:16px">
            <div style="font-size:.8rem;color:#64748b;margin-bottom:10px">
                <i class="bi bi-info-circle"></i> 
                Seret kotak QR untuk menentukan posisi. QR akan ditempelkan pada gambar sertifikat.
            </div>

            <div id="pdfWrap" style="position:relative;margin:0 auto;max-width:100%;background:#f1f5f9;border-radius:8px;overflow:hidden;touch-action:none">
                
                <!-- Preview Gambar -->
                <img id="previewImage" src="{{ $previewUrl }}" 
                     style="display:block;width:100%;height:auto;min-height:300px;background:#e2e8f0" 
                     alt="Preview Sertifikat"
                     onerror="this.style.background='#fef2f2'; this.alt='Gagal memuat preview';">

                <!-- QR Box -->
                <div id="qrBox" style="position:absolute;border:2px solid #7c3aed;background:rgba(124,58,237,.12);cursor:grab;box-shadow:0 2px 10px rgba(0,0,0,.15);border-radius:4px;display:flex;align-items:center;justify-content:center;overflow:hidden;z-index:5">
                    <img src="{{ $qrPreviewSrc }}" style="width:100%;height:100%;object-fit:contain;pointer-events:none;user-select:none" draggable="false">
                </div>
            </div>
        </div>
    </div>

    <!-- ===== CONTROLS ===== -->
    <div class="col-lg-4">
        <div style="background:#fff;border:1px solid #e2e8f0;border-radius:16px;padding:20px;position:sticky;top:20px">
            <div style="font-weight:700;font-size:.95rem;margin-bottom:16px">
                <i class="bi bi-sliders"></i> Pengaturan QR Code
            </div>

            <div style="margin-bottom:16px">
                <label style="font-size:.8rem;font-weight:600;display:flex;justify-content:space-between;margin-bottom:6px">
                    <span>Ukuran QR</span>
                    <span id="sizeLabel">{{ $posisi['size'] }}%</span>
                </label>
                <input type="range" id="sizeSlider" min="6" max="30" step="0.5" value="{{ $posisi['size'] }}" class="form-range">
            </div>

            <div class="row g-2" style="margin-bottom:16px">
                <div class="col-6">
                    <label style="font-size:.76rem;color:#64748b">Posisi X (%)</label>
                    <input type="number" id="xInput" class="form-control form-control-sm" value="{{ $posisi['x'] }}" min="0" max="100" step="0.1">
                </div>
                <div class="col-6">
                    <label style="font-size:.76rem;color:#64748b">Posisi Y (%)</label>
                    <input type="number" id="yInput" class="form-control form-control-sm" value="{{ $posisi['y'] }}" min="0" max="100" step="0.1">
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px">
                <button type="button" class="btn btn-sm btn-outline-secondary preset-btn" data-x="78" data-y="84">Kanan Bawah</button>
                <button type="button" class="btn btn-sm btn-outline-secondary preset-btn" data-x="8" data-y="84">Kiri Bawah</button>
                <button type="button" class="btn btn-sm btn-outline-secondary preset-btn" data-x="78" data-y="5">Kanan Atas</button>
                <button type="button" class="btn btn-sm btn-outline-secondary preset-btn" data-x="43" data-y="84">Tengah Bawah</button>
            </div>

            <hr>

            <form method="POST" action="{{ route('validasi-sertifikat.generate', $sertifikat) }}" id="generateForm">
                @csrf
                <input type="hidden" name="x_percent" id="xPercentField" value="{{ $posisi['x'] }}">
                <input type="hidden" name="y_percent" id="yPercentField" value="{{ $posisi['y'] }}">
                <input type="hidden" name="size_percent" id="sizePercentField" value="{{ $posisi['size'] }}">
                <button type="submit" class="btn btn-primary w-100" id="btnGenerate">
                    <i class="bi bi-qr-code"></i> Generate & Setujui
                </button>
            </form>
            <div style="font-size:.72rem;color:#94a3b8;margin-top:10px;text-align:center">
                QR Code akan ditempelkan pada gambar sertifikat, lalu status berubah menjadi <strong>Disetujui</strong>.
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    var wrap   = document.getElementById('pdfWrap');
    var qrBox  = document.getElementById('qrBox');
    var previewImg = document.getElementById('previewImage');

    var state = {
        x: {{ $posisi['x'] }},
        y: {{ $posisi['y'] }},
        size: {{ $posisi['size'] }}
    };

    // ===== Apply QR Box Position & Size =====
    function applyBoxStyle() {
        var wrapW = wrap.clientWidth;
        var wrapH = wrap.clientHeight;
        if (wrapW === 0 || wrapH === 0) {
            requestAnimationFrame(applyBoxStyle);
            return;
        }

        var sizePx = (state.size / 100) * wrapW;
        var xPx = (state.x / 100) * wrapW;
        var yPx = (state.y / 100) * wrapH;

        // Clamp agar tidak keluar area
        xPx = Math.max(0, Math.min(xPx, wrapW - sizePx));
        yPx = Math.max(0, Math.min(yPx, wrapH - sizePx));

        qrBox.style.width  = sizePx + 'px';
        qrBox.style.height = sizePx + 'px';
        qrBox.style.left   = xPx + 'px';
        qrBox.style.top    = yPx + 'px';

        // Update input fields
        document.getElementById('xInput').value = state.x.toFixed(1);
        document.getElementById('yInput').value = state.y.toFixed(1);
        document.getElementById('sizeLabel').textContent = state.size + '%';
        document.getElementById('xPercentField').value = state.x.toFixed(2);
        document.getElementById('yPercentField').value = state.y.toFixed(2);
        document.getElementById('sizePercentField').value = state.size;
    }

    // ===== Initialize setelah gambar loaded =====
    function init() {
        applyBoxStyle();
    }

    if (previewImg.complete) {
        init();
    } else {
        previewImg.addEventListener('load', init);
        previewImg.addEventListener('error', init);
    }

    window.addEventListener('resize', applyBoxStyle);

    // ===== Drag Logic (Mouse) =====
    var dragging = false;
    var dragOffset = { x: 0, y: 0 };

    function onDragStart(cx, cy) {
        var rect = qrBox.getBoundingClientRect();
        dragOffset.x = cx - rect.left;
        dragOffset.y = cy - rect.top;
        dragging = true;
        qrBox.style.cursor = 'grabbing';
        qrBox.style.zIndex = '10';
    }

    function onDragMove(cx, cy) {
        if (!dragging) return;
        var rect = wrap.getBoundingClientRect();
        var wrapW = wrap.clientWidth;
        var wrapH = wrap.clientHeight;
        var sizePx = (state.size / 100) * wrapW;

        var xPx = cx - rect.left - dragOffset.x;
        var yPx = cy - rect.top  - dragOffset.y;
        
        xPx = Math.max(0, Math.min(xPx, wrapW - sizePx));
        yPx = Math.max(0, Math.min(yPx, wrapH - sizePx));

        state.x = (xPx / wrapW) * 100;
        state.y = (yPx / wrapH) * 100;
        applyBoxStyle();
    }

    function onDragEnd() {
        if (!dragging) return;
        dragging = false;
        qrBox.style.cursor = 'grab';
        qrBox.style.zIndex = '5';
    }

    // Mouse events
    qrBox.addEventListener('mousedown', function (e) { 
        onDragStart(e.clientX, e.clientY); 
        e.preventDefault(); 
    });
    document.addEventListener('mousemove', function (e) { 
        onDragMove(e.clientX, e.clientY); 
    });
    document.addEventListener('mouseup', onDragEnd);

    // Touch events
    qrBox.addEventListener('touchstart', function (e) { 
        onDragStart(e.touches[0].clientX, e.touches[0].clientY); 
        e.preventDefault(); 
    }, { passive: false });
    document.addEventListener('touchmove', function (e) { 
        if (!dragging) return; 
        onDragMove(e.touches[0].clientX, e.touches[0].clientY); 
        e.preventDefault(); 
    }, { passive: false });
    document.addEventListener('touchend', onDragEnd);

    // ===== Controls =====
    document.getElementById('sizeSlider').addEventListener('input', function (e) { 
        state.size = parseFloat(e.target.value); 
        applyBoxStyle(); 
    });
    
    document.getElementById('xInput').addEventListener('change', function (e) { 
        state.x = Math.max(0, Math.min(100, parseFloat(e.target.value) || 0)); 
        applyBoxStyle(); 
    });
    
    document.getElementById('yInput').addEventListener('change', function (e) { 
        state.y = Math.max(0, Math.min(100, parseFloat(e.target.value) || 0)); 
        applyBoxStyle(); 
    });

    // Preset buttons
    document.querySelectorAll('.preset-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            state.x = parseFloat(this.dataset.x);
            state.y = parseFloat(this.dataset.y);
            applyBoxStyle();
        });
    });

    // Form submit loading state
    document.getElementById('generateForm').addEventListener('submit', function () {
        var btn = document.getElementById('btnGenerate');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';
    });
})();
</script>
@endsection