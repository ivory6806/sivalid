<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\Unit;
use App\Mail\SuratDisetujui;
use App\Mail\SuratDirevisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use setasign\Fpdi\Fpdi;

class ValidasiSuratController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $unitId = $request->header('X-Unit-Id');

        $query = Surat::whereIn('status', ['diajukan', 'direvisi']);

        if (!$user->isSuperadmin() || $unitId) {
            if ($unitId) $query->where('unit_id', $unitId);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('nama_surat', 'like', "%{$search}%")
                    ->orWhere('nama_mahasiswa', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $surats = $query->with(['user', 'unit'])->latest()->paginate($request->get('per_page', 15));

        return response()->json($surats);
    }

    public function show(Surat $surat)
    {
        $surat->load(['user', 'validator', 'unit']);
        return response()->json($surat);
    }

    public function approve(Request $request, Surat $surat)
    {
        $user = $request->user();
        $unitId = $request->header('X-Unit-Id');

        if (!$user->isSuperadmin() && $unitId && $surat->unit_id != $unitId) {
            return response()->json(['message' => 'Surat ini bukan milik unit yang aktif.'], 403);
        }

        if (!in_array($surat->status, ['diajukan', 'direvisi'])) {
            return response()->json(['message' => 'Surat tidak dalam status yang bisa disetujui.'], 422);
        }

        $validated = $request->validate([
            'qr_page' => 'nullable|integer|min:1',
            'qr_pos_x' => 'nullable|numeric|min:0|max:100',
            'qr_pos_y' => 'nullable|numeric|min:0|max:100',
            'qr_size_mm' => 'nullable|numeric|min:10|max:60',
        ]);

        if (isset($validated['qr_page'])) {
            $surat->update([
                'qr_page' => $validated['qr_page'],
                'qr_pos_x' => $validated['qr_pos_x'] ?? $surat->effective_qr_pos_x,
                'qr_pos_y' => $validated['qr_pos_y'] ?? $surat->effective_qr_pos_y,
                'qr_size_mm' => $validated['qr_size_mm'] ?? $surat->effective_qr_size_mm,
            ]);
        }

        $result = $this->processApprove($surat);

        if (!$result['success']) {
            return response()->json(['message' => $result['message']], 500);
        }

        $this->sendApprovalEmail($surat);

        return response()->json(['message' => 'Surat disetujui. QR Code berhasil ditambahkan.']);
    }

    public function revise(Request $request, Surat $surat)
    {
        $user = $request->user();
        $unitId = $request->header('X-Unit-Id');

        if (!$user->isSuperadmin() && $unitId && $surat->unit_id != $unitId) {
            return response()->json(['message' => 'Surat ini bukan milik unit yang aktif.'], 403);
        }

        $validated = $request->validate([
            'catatan_revisi' => 'required|string|max:1000',
        ]);

        $surat->update([
            'status' => 'direvisi',
            'catatan_revisi' => $validated['catatan_revisi'],
            'file_disahkan' => null,
            'validated_by' => null,
            'validated_at' => null,
        ]);

        $this->sendRevisionEmail($surat);

        return response()->json(['message' => 'Surat dikembalikan untuk direvisi.']);
    }

    public function cancel(Request $request, Surat $surat)
    {
        $user = $request->user();
        $unitId = $request->header('X-Unit-Id');

        if (!$user->isSuperadmin() && $unitId && $surat->unit_id != $unitId) {
            return response()->json(['message' => 'Surat ini bukan milik unit yang aktif.'], 403);
        }

        $surat->update([
            'status' => 'diajukan',
            'file_disahkan' => null,
            'validated_by' => null,
            'validated_at' => null,
        ]);

        return response()->json(['message' => 'Surat dibatalkan dari antrian validasi.']);
    }

    public function batchApprove(Request $request)
    {
        $user = $request->user();
        $unitId = $request->header('X-Unit-Id');

        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:surats,id',
        ]);

        $pdfDir = storage_path('app/public/surat_disahkan');
        if (!File::isDirectory($pdfDir)) {
            File::makeDirectory($pdfDir, 0755, true);
        }

        $approved = 0;
        $failed = [];

        foreach ($validated['ids'] as $id) {
            $surat = Surat::find($id);

            if (!$surat || !in_array($surat->status, ['diajukan', 'direvisi'])) {
                $failed[] = ['id' => $id, 'reason' => 'Status tidak valid.'];
                continue;
            }

            if (!$user->isSuperadmin() && $unitId && $surat->unit_id != $unitId) {
                $failed[] = ['id' => $id, 'reason' => 'Bukan milik unit aktif.'];
                continue;
            }

            $result = $this->processApprove($surat);
            if ($result['success']) {
                $this->sendApprovalEmail($surat);
                $approved++;
            } else {
                $failed[] = ['id' => $id, 'reason' => $result['message']];
            }
        }

        return response()->json([
            'message' => "{$approved} surat berhasil disetujui.",
            'approved' => $approved,
            'failed' => $failed,
        ]);
    }

    public function batchReject(Request $request)
    {
        $user = $request->user();
        $unitId = $request->header('X-Unit-Id');

        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:surats,id',
            'catatan_revisi' => 'required|string|max:1000',
        ]);

        $rejected = 0;
        $failed = [];

        foreach ($validated['ids'] as $id) {
            $surat = Surat::find($id);

            if (!$surat || !in_array($surat->status, ['diajukan', 'direvisi'])) {
                $failed[] = ['id' => $id, 'reason' => 'Status tidak valid.'];
                continue;
            }

            if (!$user->isSuperadmin() && $unitId && $surat->unit_id != $unitId) {
                $failed[] = ['id' => $id, 'reason' => 'Bukan milik unit aktif.'];
                continue;
            }

            $surat->update([
                'status' => 'direvisi',
                'catatan_revisi' => $validated['catatan_revisi'],
                'file_disahkan' => null,
                'validated_by' => null,
                'validated_at' => null,
            ]);

            $this->sendRevisionEmail($surat);
            $rejected++;
        }

        return response()->json([
            'message' => "{$rejected} surat berhasil ditolak.",
            'rejected' => $rejected,
            'failed' => $failed,
        ]);
    }

    // ============ PRIVATE HELPERS ============

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
        $outputPath = $pdfDir . '/' . $pdfFileName;

        try {
            $this->stampQrOnPdf($originalPdfPath, $qrTempPath, $outputPath, $surat);
        } catch (\Throwable $e) {
            @unlink($qrTempPath);
            return ['success' => false, 'message' => 'Gagal stamp QR: ' . $e->getMessage()];
        }

        @unlink($qrTempPath);

        if (!file_exists($outputPath)) {
            return ['success' => false, 'message' => 'File PDF hasil tidak tersimpan.'];
        }

        $surat->update([
            'status' => 'disetujui',
            'file_disahkan' => $pdfFileName,
            'validated_by' => request()->user()?->id,
            'validated_at' => now(),
            'catatan_revisi' => null,
        ]);

        return ['success' => true];
    }

    private function stampQrOnPdf(string $originalPdf, string $qrImage, string $outputPdf, $surat): void
    {
        $normalizedPdf = $this->normalizePdfForFpdi($originalPdf);

        try {
            $pdf = new Fpdi();
            $pdf->SetAutoPageBreak(false);

            $pageCount = $pdf->setSourceFile($normalizedPdf);
            $targetPage = min(max($surat->effective_qr_page, 1), $pageCount);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';
                $pdf->AddPage($orientation, [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);

                if ($pageNo == $targetPage) {
                    $qrSize = $surat->effective_qr_size_mm;
                    $qrX = ($surat->effective_qr_pos_x / 100) * $size['width'];
                    $qrY = ($surat->effective_qr_pos_y / 100) * $size['height'];

                    $pdf->Image($qrImage, $qrX, $qrY, $qrSize, $qrSize);

                    $boxSize = 4;
                    $xBox = $qrX + ($qrSize / 2) - ($boxSize / 2);
                    $yBox = $qrY + ($qrSize / 2) - ($boxSize / 2);
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->Rect($xBox, $yBox, $boxSize, $boxSize, 'F');

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
        } finally {
            if ($normalizedPdf !== $originalPdf && file_exists($normalizedPdf)) {
                @unlink($normalizedPdf);
            }
        }
    }

    private function normalizePdfForFpdi(string $originalPdf): string
    {
        $disabled = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (!function_exists('exec') || in_array('exec', $disabled, true)) {
            return $originalPdf;
        }

        $qpdfBinary = $this->resolveQpdfBinary();
        if (!$qpdfBinary) return $originalPdf;

        $qrDir = storage_path('app/public/qr');
        if (!File::isDirectory($qrDir)) {
            File::makeDirectory($qrDir, 0755, true);
        }

        $normalizedPath = $qrDir . '/normalized_' . uniqid() . '.pdf';
        $cmd = escapeshellarg($qpdfBinary) . ' --stream-data=uncompress --object-streams=disable --decrypt '
            . escapeshellarg($originalPdf) . ' ' . escapeshellarg($normalizedPath) . ' 2>&1';

        @exec($cmd, $output, $exitCode);

        if (in_array($exitCode, [0, 3], true) && file_exists($normalizedPath)) {
            return $normalizedPath;
        }

        if (file_exists($normalizedPath)) @unlink($normalizedPath);
        return $originalPdf;
    }

    private function resolveQpdfBinary(): ?string
    {
        static $resolved = null;
        static $checked = false;
        if ($checked) return $resolved;
        $checked = true;

        $configuredPath = config('services.qpdf.path');
        if ($configuredPath && file_exists($configuredPath)) {
            $resolved = $configuredPath;
            return $resolved;
        }

        if (stripos(PHP_OS_FAMILY, 'Windows') !== false) {
            @exec('where qpdf 2>NUL', $whichOutput, $whichCode);
        } else {
            @exec('command -v qpdf 2>/dev/null', $whichOutput, $whichCode);
        }

        $resolved = ($whichCode === 0 && !empty($whichOutput)) ? 'qpdf' : null;
        return $resolved;
    }

    private function sendApprovalEmail(Surat $surat): void
    {
        try {
            $fresh = Surat::with(['user', 'validator'])->find($surat->id);
            if ($fresh->user && $fresh->user->email) {
                Mail::to($fresh->user->email)->send(new SuratDisetujui($fresh));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email persetujuan: ' . $e->getMessage());
        }
    }

    private function sendRevisionEmail(Surat $surat): void
    {
        try {
            $fresh = Surat::with(['user', 'validator'])->find($surat->id);
            if ($fresh->user && $fresh->user->email) {
                Mail::to($fresh->user->email)->send(new SuratDirevisi($fresh));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email revisi: ' . $e->getMessage());
        }
    }
}
