<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Mail\SuratDisetujui;
use App\Mail\SuratDirevisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use setasign\Fpdi\Fpdi;

class ValidasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Surat::whereIn('status', ['diajukan', 'direvisi']);

        if (!Auth::user()->isSuperadmin()) {
            $query->where('unit_id', session('active_unit_id'));
        }

        $surats = $query
            ->when($request->search, function ($q, $search) {
                $q->where('nama_mahasiswa', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                    ->orWhere('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('nama_surat', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        // Non-superadmin: tampilan mengikuti jenis unit aktif.
        // Superadmin: selalu tampilan generik karena bisa lintas unit.
        $unit = \App\Models\Unit::find(session('active_unit_id'));
        $useGenericView = Auth::user()->isSuperadmin() || !$unit || $unit->code !== 'akademik';

        return view($useGenericView ? 'surat.validasi-generic' : 'surat.validasi', compact('surats'));
    }

    /**
     * Halaman Kepala untuk memilih halaman & posisi QR sebelum generate (khusus dokumen generik).
     */
    public function aturQr(Surat $surat)
    {
        $this->authorizeUnitAccess($surat);

        if (!in_array($surat->status, ['diajukan', 'direvisi'])) {
            return redirect()->route('validasi.index')->with('error', 'Surat ini sudah tidak dalam status menunggu validasi.');
        }

        // Diperbaiki: mengarah ke file atur-qr.blade.php (bukan create-generic)
        return view('surat.atur-qr', compact('surat'));
    }

    /**
     * Setujui dokumen generik dengan halaman & posisi QR pilihan Kepala.
     */
    public function approveWithPosition(Request $request, Surat $surat)
    {
        $this->authorizeUnitAccess($surat);

        $validated = $request->validate([
            'qr_page'    => 'required|integer|min:1',
            'qr_pos_x'   => 'required|numeric|min:0|max:100',
            'qr_pos_y'   => 'required|numeric|min:0|max:100',
            'qr_size_mm' => 'nullable|numeric|min:10|max:60',
        ]);

        $surat->update([
            'qr_page'    => $validated['qr_page'],
            'qr_pos_x'   => $validated['qr_pos_x'],
            'qr_pos_y'   => $validated['qr_pos_y'],
            'qr_size_mm' => $validated['qr_size_mm'] ?? 25,
        ]);

        $result = $this->processApprove($surat);

        if (!$result['success']) {
            return redirect()->route('validasi.index')->with('error', $result['message']);
        }

        $this->sendApprovalEmail($surat);

        return redirect()->route('validasi.index')
            ->with('success', 'Surat disetujui. QR Code ditambahkan sesuai posisi yang dipilih.');
    }

    /**
     * Pastikan kepala hanya bisa memvalidasi surat di unit yang sedang aktif.
     */
    private function authorizeUnitAccess(Surat $surat): void
    {
        if (!Auth::user()->isSuperadmin() && $surat->unit_id !== session('active_unit_id')) {
            abort(403, 'Surat ini bukan milik unit yang sedang aktif.');
        }
    }

    public function approve(Request $request, Surat $surat)
    {
        $this->authorizeUnitAccess($surat);
        $request->validate(['approved' => 'required|accepted']);

        $result = $this->processApprove($surat);

        if (!$result['success']) {
            return redirect()->route('validasi.index')->with('error', $result['message']);
        }

        $this->sendApprovalEmail($surat);

        return redirect()->route('validasi.index')
            ->with('success', 'Surat disetujui. QR Code pengesahan ditambahkan ke PDF.');
    }

    public function revise(Request $request, Surat $surat)
    {
        $this->authorizeUnitAccess($surat);
        $validated = $request->validate([
            'catatan_revisi' => 'required|string|max:1000',
        ]);

        $surat->update([
            'status'         => 'direvisi',
            'catatan_revisi' => $validated['catatan_revisi'],
            'file_disahkan'  => null,
            'validated_by'   => null,
            'validated_at'   => null,
        ]);

        $this->sendRevisionEmail($surat);

        return redirect()->route('validasi.index')
            ->with('success', 'Surat dikembalikan untuk direvisi.');
    }

    // =============================================
    //  BATCH VALIDATION
    // =============================================

    public function batchApprove(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'exists:surats,id',
        ]);

        $ids = $request->ids;
        $approved = 0;
        $failed   = [];
        $errors   = [];

        // Pastikan folder ada
        $pdfDir = storage_path('app/public/surat_disahkan');
        if (!File::isDirectory($pdfDir)) {
            File::makeDirectory($pdfDir, 0755, true);
        }

        foreach ($ids as $id) {
            $surat = Surat::find($id);

            // Keamanan: kepala non-superadmin hanya boleh approve surat di unit aktifnya
            if ($surat && !Auth::user()->isSuperadmin() && $surat->unit_id !== session('active_unit_id')) {
                $failed[] = $surat->nomor_surat ?? "ID:{$id}";
                $errors[] = "Surat {$surat->nomor_surat} bukan milik unit yang sedang aktif.";
                continue;
            }

            // Skip jika status bukan diajukan/direvisi
            if (!$surat || !in_array($surat->status, ['diajukan', 'direvisi'])) {
                $failed[] = $surat->nomor_surat ?? "ID:{$id}";
                $errors[] = "Surat {$surat->nomor_surat} bukan dalam status yang bisa disetujui.";
                continue;
            }

            // Cek file asli ada
            $originalPdfPath = storage_path('app/public/surat/' . $surat->file_surat);
            if (!file_exists($originalPdfPath)) {
                $failed[] = $surat->nomor_surat;
                $errors[] = "File PDF asli untuk {$surat->nomor_surat} tidak ditemukan.";
                continue;
            }

            // Generate QR
            $url = url('/verify/' . $surat->qr_token);
            $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/'
                . '?size=200x200&margin=1&ecc=H&data=' . urlencode($url);

            $qrContent = @file_get_contents($qrApiUrl);

            if ($qrContent === false) {
                $failed[] = $surat->nomor_surat;
                $errors[] = "Gagal generate QR Code untuk {$surat->nomor_surat}.";
                continue;
            }

            // Simpan QR sementara
            $qrTempPath = storage_path('app/public/qr/temp_qr_' . $surat->id . '.png');
            file_put_contents($qrTempPath, $qrContent);

            // Stamp QR ke PDF
            $nomorBersih = str_replace(['/', ' '], '-', $surat->nomor_surat);
            $pdfFileName = 'disahkan_' . $nomorBersih . '.pdf';
            $outputPath  = $pdfDir . '/' . $pdfFileName;

            try {
                $this->stampQrOnPdf($originalPdfPath, $qrTempPath, $outputPath, $surat);
            } catch (\Throwable $e) {
                @unlink($qrTempPath);
                $failed[] = $surat->nomor_surat;
                $errors[] = "Gagal stamp QR untuk {$surat->nomor_surat}: " . $e->getMessage();
                continue;
            }

            // Hapus QR sementara
            @unlink($qrTempPath);

            if (!file_exists($outputPath)) {
                $failed[] = $surat->nomor_surat;
                $errors[] = "File PDF hasil untuk {$surat->nomor_surat} tidak tersimpan.";
                continue;
            }

            // Update database
            $surat->update([
                'status'        => 'disetujui',
                'file_disahkan' => $pdfFileName,
                'validated_by'  => Auth::id(),
                'validated_at'  => now(),
                'catatan_revisi'=> null,
            ]);

            // Kirim email (gagal email tidak menghentikan proses)
            $this->sendApprovalEmail($surat);

            $approved++;
        }

        // Bangun pesan hasil
        return $this->buildBatchResponse($approved, $failed, $errors, 'disetujui');
    }

    public function batchReject(Request $request)
    {
        $request->validate([
            'ids'            => 'required|array|min:1',
            'ids.*'          => 'exists:surats,id',
            'catatan_revisi' => 'required|string|max:1000',
        ]);

        $ids      = $request->ids;
        $catatan  = $request->catatan_revisi;
        $rejected = 0;
        $failed   = [];
        $errors   = [];

        foreach ($ids as $id) {
            $surat = Surat::find($id);

            if ($surat && !Auth::user()->isSuperadmin() && $surat->unit_id !== session('active_unit_id')) {
                $failed[] = $surat->nomor_surat ?? "ID:{$id}";
                $errors[] = "Surat {$surat->nomor_surat} bukan milik unit yang sedang aktif.";
                continue;
            }

            if (!$surat || !in_array($surat->status, ['diajukan', 'direvisi'])) {
                $failed[] = $surat->nomor_surat ?? "ID:{$id}";
                $errors[] = "Surat {$surat->nomor_surat} bukan dalam status yang bisa ditolak.";
                continue;
            }

            $surat->update([
                'status'         => 'direvisi',
                'catatan_revisi' => $catatan,
                'file_disahkan'  => null,
                'validated_by'   => null,
                'validated_at'   => null,
            ]);

            $this->sendRevisionEmail($surat);

            $rejected++;
        }

        return $this->buildBatchResponse($rejected, $failed, $errors, 'ditolak');
    }

    // =============================================
    //  PRIVATE HELPERS
    // =============================================

    /**
     * Proses approve untuk satu surat (digunakan ulang oleh approve & batchApprove)
     */
    private function processApprove(Surat $surat): array
    {
        $pdfDir = storage_path('app/public/surat_disahkan');
        if (!File::isDirectory($pdfDir)) {
            File::makeDirectory($pdfDir, 0755, true);
        }

        $url = url('/verify/' . $surat->qr_token);
        $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/'
            . '?size=200x200&margin=1&ecc=H&data=' . urlencode($url);

        $qrContent = @file_get_contents($qrApiUrl);

        if ($qrContent === false) {
            return ['success' => false, 'message' => 'Gagal generate QR Code. Pastikan server terhubung internet.'];
        }

        $qrTempPath = storage_path('app/public/qr/temp_qr_' . $surat->id . '.png');
        file_put_contents($qrTempPath, $qrContent);

        $originalPdfPath = storage_path('app/public/surat/' . $surat->file_surat);

        if (!file_exists($originalPdfPath)) {
            @unlink($qrTempPath);
            return ['success' => false, 'message' => 'File PDF asli tidak ditemukan.'];
        }

        $nomorBersih = str_replace(['/', ' '], '-', $surat->nomor_surat);
        $pdfFileName = 'disahkan_' . $nomorBersih . '.pdf';
        $outputPath  = $pdfDir . '/' . $pdfFileName;

        try {
            $this->stampQrOnPdf($originalPdfPath, $qrTempPath, $outputPath, $surat);
        } catch (\Throwable $e) {
            @unlink($qrTempPath);
            return ['success' => false, 'message' => 'Gagal stamp QR ke PDF: ' . $e->getMessage()];
        }

        @unlink($qrTempPath);

        if (!file_exists($outputPath)) {
            return ['success' => false, 'message' => 'File PDF hasil tidak tersimpan.'];
        }

        $surat->update([
            'status'        => 'disetujui',
            'file_disahkan' => $pdfFileName,
            'validated_by'  => Auth::id(),
            'validated_at'  => now(),
            'catatan_revisi'=> null,
        ]);

        return ['success' => true];
    }

    /**
     * Kirim email persetujuan (toleran gagal)
     */
    private function sendApprovalEmail(Surat $surat): void
    {
        try {
            $suratFresh = Surat::with(['user', 'validator'])->find($surat->id);
            if ($suratFresh->user && $suratFresh->user->email) {
                Mail::to($suratFresh->user->email)->send(new SuratDisetujui($suratFresh));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email persetujuan (Surat ' . $surat->nomor_surat . '): ' . $e->getMessage());
        }
    }

    /**
     * Kirim email revisi (toleran gagal)
     */
    private function sendRevisionEmail(Surat $surat): void
    {
        try {
            $suratFresh = Surat::with(['user', 'validator'])->find($surat->id);
            if ($suratFresh->user && $suratFresh->user->email) {
                Mail::to($suratFresh->user->email)->send(new SuratDirevisi($suratFresh));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email revisi (Surat ' . $surat->nomor_surat . '): ' . $e->getMessage());
        }
    }

    /**
     * Bangun pesan redirect setelah batch process
     */
    private function buildBatchResponse(int $successCount, array $failed, array $errors, string $actionLabel): \Illuminate\Http\RedirectResponse
    {
        $total = $successCount + count($failed);

        // Semua berhasil
        if (empty($failed)) {
            return redirect()->route('validasi.index')
                ->with('success', "{$successCount} surat berhasil {$actionLabel}.");
        }

        // Semua gagal
        if ($successCount === 0) {
            $message = "Tidak ada surat yang berhasil {$actionLabel}.\n";
            $message .= implode("\n", $errors);
            return redirect()->route('validasi.index')
                ->with('error', $message);
        }

        // Sebagian berhasil
        $message = "{$successCount} dari {$total} surat berhasil {$actionLabel}.";
        if (!empty($failed)) {
            $message .= "\nGagal (" . count($failed) . "): " . implode(', ', $failed);
        }

        return redirect()->route('validasi.index')
            ->with('warning', $message);
    }

    /**
     * Stamp QR Code ke PDF, di halaman & posisi sesuai pengaturan surat
     * (surat->qr_page / qr_pos_x / qr_pos_y, dalam % relatif terhadap halaman).
     * Kalau belum diatur (surat lama), pakai posisi default lama di halaman 1.
     */
    private function stampQrOnPdf(string $originalPdf, string $qrImage, string $outputPdf, $surat): void
    {
        $pdf = new Fpdi();
        $pdf->SetAutoPageBreak(false);

        $pageCount = $pdf->setSourceFile($originalPdf);
        $targetPage = min(max($surat->effective_qr_page, 1), $pageCount);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
            $pdf->AddPage($orientation, [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            if ($pageNo == $targetPage) {
                $qrSize = $surat->effective_qr_size_mm;
                $qrX    = ($surat->effective_qr_pos_x / 100) * $size['width'];
                $qrY    = ($surat->effective_qr_pos_y / 100) * $size['height'];

                // Gambar QR
                $pdf->Image($qrImage, $qrX, $qrY, $qrSize, $qrSize);

                // Kotak putih di tengah QR
                $boxSize = 4;
                $xBox = $qrX + ($qrSize / 2) - ($boxSize / 2);
                $yBox = $qrY + ($qrSize / 2) - ($boxSize / 2);
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Rect($xBox, $yBox, $boxSize, $boxSize, 'F');

                // Logo di tengah QR
                $logoPath = public_path('images/logo.png');
                $logoSize = 3;
                if (file_exists($logoPath)) {
                    $xLogo = $qrX + ($qrSize / 2) - ($logoSize / 2);
                    $yLogo = $qrY + ($qrSize / 2) - ($logoSize / 2);
                    $pdf->Image($logoPath, $xLogo, $yLogo, $logoSize, $logoSize);
                }
            }
        }

        $pdf->Output($outputPdf, 'F');
    }

    public function cancel(Surat $surat)
    {
        $this->authorizeUnitAccess($surat);
        $surat->update([
            'status'         => 'diajukan',
            'file_disahkan'  => null,
            'validated_by'   => null,
            'validated_at'   => null,
        ]);

        return redirect()->route('validasi.index')
            ->with('success', 'Surat berhasil dibatalkan dari antrian validasi.');
    }
}