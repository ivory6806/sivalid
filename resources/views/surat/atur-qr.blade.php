@extends('layouts.app')
@section('title', 'Atur Posisi QR')
@section('pageTitle', 'Atur Posisi QR — ' . $surat->nomor_surat)
@section('content')

<div style="margin-bottom:16px">
    <a href="{{ route('validasi.index') }}" style="font-size:.82rem;color:var(--text-muted);text-decoration:none;display:inline-flex;align-items:center;gap:4px">
        <i class="bi bi-arrow-left"></i> Kembali ke daftar validasi
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-custom">
            <div style="padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px">
                <div style="font-size:.88rem;font-weight:700;color:var(--text-dark)">
                    <i class="bi bi-file-earmark-pdf-fill" style="color:#ef4444"></i> {{ $surat->nama_surat }}
                </div>
                <div style="display:flex;align-items:center;gap:8px">
                    <button type="button" id="btnPrevPage" class="btn-outline-custom" style="padding:6px 12px"><i class="bi bi-chevron-left"></i></button>
                    <span style="font-size:.82rem;font-weight:600;color:var(--text-dark)">Halaman <span id="pageIndicator">1</span> / <span id="pageTotal">-</span></span>
                    <button type="button" id="btnNextPage" class="btn-outline-custom" style="padding:6px 12px"><i class="bi bi-chevron-right"></i></button>
                </div>
            </div>
            <div style="padding:20px;text-align:center;background:#f1f5f9;border-radius:0 0 14px 14px;overflow:auto">
                <div id="pdfLoading" style="padding:60px 0;color:var(--text-muted);font-size:.85rem">
                    <i class="bi bi-arrow-repeat" style="font-size:1.4rem"></i><br>Memuat PDF...
                </div>
                <div id="canvasWrap" style="position:relative;display:inline-block;box-shadow:0 4px 20px rgba(0,0,0,.15);display:none">
                    <canvas id="pdfCanvas"></canvas>
                    <div id="qrMarker" style="position:absolute;border:2px solid #059669;background:rgba(5,150,105,.25);cursor:move;display:flex;align-items:center;justify-content:center">
                        <i class="bi bi-qr-code" style="color:#059669;font-size:1.1rem"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-custom">
            <div style="padding:22px">
                <div style="font-size:.92rem;font-weight:700;color:var(--text-dark);margin-bottom:6px">Atur & Setujui</div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-bottom:18px">Geser kotak hijau ke posisi QR, pilih halaman, lalu klik Setujui.</div>

                <form method="POST" action="{{ route('validasi.approve-position', $surat) }}" id="approveForm">
                    @csrf
                    <input type="hidden" name="qr_page" id="inputQrPage" value="1">
                    <input type="hidden" name="qr_pos_x" id="inputQrPosX" value="77.14">
                    <input type="hidden" name="qr_pos_y" id="inputQrPosY" value="80.81">

                    <div style="margin-bottom:16px">
                        <label style="font-size:.8rem;font-weight:600;color:var(--text-dark);margin-bottom:6px;display:block">Ukuran QR (mm)</label>
                        <input type="number" name="qr_size_mm" id="inputQrSize" value="25" min="10" max="60" step="1" class="form-control"
                            style="border-radius:10px;padding:10px 14px;border:1.5px solid var(--border);font-size:.85rem">
                    </div>

                    <div style="padding:12px 14px;background:#f8fafc;border-radius:10px;border:1px solid var(--border);margin-bottom:18px;font-size:.76rem;color:var(--text-muted)">
                        <i class="bi bi-info-circle"></i> QR akan ditempel di halaman <strong id="infoPage">1</strong>, posisi tersimpan otomatis mengikuti kotak hijau di atas.
                    </div>

                    <button type="submit" class="btn-primary-custom" style="width:100%;padding:12px;font-size:.88rem">
                        <i class="bi bi-check-lg"></i> Setujui & Generate QR
                    </button>
                </form>

                <a href="{{ route('surat.download', $surat) }}" target="_blank" class="btn-outline-custom" style="width:100%;padding:11px;font-size:.85rem;margin-top:8px;display:block;text-align:center">
                    <i class="bi bi-download"></i> Download PDF Asli
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

const pdfUrl = @json(route('surat.preview', $surat));
const totalHalamanKnown = {{ $surat->total_halaman ?? 'null' }};
let pdfDoc = null;
let currentPage = 1;
let qrSizePercentW = 12; // lebar kotak QR relatif thd lebar canvas (%), disesuaikan tiap render
let markerXPercent = 77.14, markerYPercent = 80.81;

const canvas = document.getElementById('pdfCanvas');
const ctx = canvas.getContext('2d');
const canvasWrap = document.getElementById('canvasWrap');
const marker = document.getElementById('qrMarker');

function renderPage(num) {
    document.getElementById('pdfLoading').style.display = 'block';
    canvasWrap.style.display = 'none';
    pdfDoc.getPage(num).then(function(page) {
        const viewport = page.getViewport({ scale: 1.3 });
        canvas.width = viewport.width;
        canvas.height = viewport.height;

        const qrSizeMm = parseFloat(document.getElementById('inputQrSize').value) || 25;
        // Perkiraan lebar halaman dalam mm (asumsi A4 potret 210mm jika tidak diketahui)
        const pageWidthMm = 210;
        qrSizePercentW = (qrSizeMm / pageWidthMm) * 100;

        page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function() {
            document.getElementById('pdfLoading').style.display = 'none';
            canvasWrap.style.display = 'inline-block';
            positionMarker();
        });
    });
    document.getElementById('pageIndicator').textContent = num;
    document.getElementById('infoPage').textContent = num;
    document.getElementById('inputQrPage').value = num;
}

function positionMarker() {
    const markerSizePx = (qrSizePercentW / 100) * canvas.width;
    marker.style.width = markerSizePx + 'px';
    marker.style.height = markerSizePx + 'px';
    marker.style.left = ((markerXPercent / 100) * canvas.width) + 'px';
    marker.style.top = ((markerYPercent / 100) * canvas.height) + 'px';
}

document.getElementById('inputQrSize').addEventListener('input', function() {
    const qrSizeMm = parseFloat(this.value) || 25;
    qrSizePercentW = (qrSizeMm / 210) * 100;
    positionMarker();
});

pdfjsLib.getDocument(pdfUrl).promise.then(function(doc) {
    pdfDoc = doc;
    document.getElementById('pageTotal').textContent = doc.numPages;
    renderPage(currentPage);
});

document.getElementById('btnPrevPage').addEventListener('click', function() {
    if (currentPage > 1) { currentPage--; renderPage(currentPage); }
});
document.getElementById('btnNextPage').addEventListener('click', function() {
    if (pdfDoc && currentPage < pdfDoc.numPages) { currentPage++; renderPage(currentPage); }
});

// Drag logic (mouse + touch)
let dragging = false;
function startDrag(e) { dragging = true; e.preventDefault(); }
function endDrag() { dragging = false; }
function moveDrag(e) {
    if (!dragging) return;
    const rect = canvas.getBoundingClientRect();
    const clientX = e.touches ? e.touches[0].clientX : e.clientX;
    const clientY = e.touches ? e.touches[0].clientY : e.clientY;
    let x = ((clientX - rect.left) / rect.width) * 100;
    let y = ((clientY - rect.top) / rect.height) * 100;
    x = Math.max(0, Math.min(100 - qrSizePercentW, x));
    y = Math.max(0, Math.min(100 - qrSizePercentW, y));
    markerXPercent = x;
    markerYPercent = y;
    positionMarker();
    document.getElementById('inputQrPosX').value = x.toFixed(2);
    document.getElementById('inputQrPosY').value = y.toFixed(2);
}

marker.addEventListener('mousedown', startDrag);
marker.addEventListener('touchstart', startDrag);
window.addEventListener('mousemove', moveDrag);
window.addEventListener('touchmove', moveDrag);
window.addEventListener('mouseup', endDrag);
window.addEventListener('touchend', endDrag);

document.getElementById('approveForm').addEventListener('submit', function() {
    document.getElementById('inputQrPosX').value = markerXPercent.toFixed(2);
    document.getElementById('inputQrPosY').value = markerYPercent.toFixed(2);
});
</script>

@endsection
