<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use App\Mail\SertifikatDisetujui;
use App\Mail\SertifikatDirevisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ValidasiSertifikatController extends Controller
{
    const DEFAULT_X = 78.0;
    const DEFAULT_Y = 84.0;
    const DEFAULT_SIZE = 14.0;

    public function index(Request $request)
    {
        $query = Sertifikat::whereIn('status', ['diajukan', 'direvisi']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_sertifikat', 'like', "%{$search}%")
                    ->orWhere('jenis_sertifikat', 'like', "%{$search}%");
            });
        }

        $sertifikats = $query->with('uploader')->latest()->paginate($request->get('per_page', 15));

        return response()->json($sertifikats);
    }

    public function show(Sertifikat $sertifikat)
    {
        $sertifikat->load(['uploader', 'validator']);
        return response()->json($sertifikat);
    }

    public function adjust(Sertifikat $sertifikat)
    {
        if (!in_array($sertifikat->status, ['diajukan', 'direvisi'])) {
            return response()->json(['message' => 'Sertifikat tidak dalam antrian validasi.'], 422);
        }

        return response()->json([
            'sertifikat' => $sertifikat,
            'posisi' => [
                'x' => $sertifikat->qr_pos_x ?? self::DEFAULT_X,
                'y' => $sertifikat->qr_pos_y ?? self::DEFAULT_Y,
                'size' => $sertifikat->qr_size ?? self::DEFAULT_SIZE,
            ],
        ]);
    }

    public function generate(Request $request, Sertifikat $sertifikat)
    {
        if (!in_array($sertifikat->status, ['diajukan', 'direvisi'])) {
            return response()->json(['message' => 'Sertifikat tidak dalam antrian validasi.'], 422);
        }

        $validated = $request->validate([
            'x_percent' => 'required|numeric|min:0|max:100',
            'y_percent' => 'required|numeric|min:0|max:100',
            'size_percent' => 'required|numeric|min:3|max:40',
        ]);

        $outputDir = storage_path('app/public/sertifikat_disahkan');
        if (!File::isDirectory($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        $url = url('/verify/sertifikat/' . $sertifikat->qr_token);
        $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/'
            . '?size=300x300&margin=1&ecc=H&data=' . urlencode($url);

        $qrContent = @file_get_contents($qrApiUrl);
        if ($qrContent === false) {
            return response()->json(['message' => 'Gagal generate QR Code. Pastikan server terhubung internet.'], 500);
        }

        $qrTempPath = storage_path('app/public/qr/temp_qr_cert_' . $sertifikat->id . '.png');
        if (!File::isDirectory(dirname($qrTempPath))) {
            File::makeDirectory(dirname($qrTempPath), 0755, true);
        }
        file_put_contents($qrTempPath, $qrContent);

        $originalPath = storage_path('app/public/sertifikat/' . $sertifikat->file_asli);
        if (!file_exists($originalPath)) {
            @unlink($qrTempPath);
            return response()->json(['message' => 'File gambar asli tidak ditemukan.'], 404);
        }

        $nomorBersih = preg_replace('/[^A-Za-z0-9\-]+/', '-', $sertifikat->nomor_sertifikat);
        $outputFileName = 'disahkan_' . $nomorBersih . '_' . time() . '.png';
        $outputPath = $outputDir . '/' . $outputFileName;

        try {
            $this->stampQrOnImage(
                $originalPath,
                $qrTempPath,
                $outputPath,
                (float) $validated['x_percent'],
                (float) $validated['y_percent'],
                (float) $validated['size_percent']
            );
        } catch (\Throwable $e) {
            @unlink($qrTempPath);
            return response()->json(['message' => 'Gagal menempelkan QR: ' . $e->getMessage()], 500);
        }

        @unlink($qrTempPath);

        if (!file_exists($outputPath)) {
            return response()->json(['message' => 'File hasil tidak tersimpan.'], 500);
        }

        if ($sertifikat->file_disahkan && file_exists($outputDir . '/' . $sertifikat->file_disahkan)) {
            @unlink($outputDir . '/' . $sertifikat->file_disahkan);
        }

        $sertifikat->update([
            'status' => 'disetujui',
            'file_disahkan' => $outputFileName,
            'qr_pos_x' => $validated['x_percent'],
            'qr_pos_y' => $validated['y_percent'],
            'qr_size' => $validated['size_percent'],
            'validated_by' => request()->user()->id,
            'validated_at' => now(),
            'qr_generated_at' => now(),
            'catatan_revisi' => null,
            'file_type' => 'image/png',
            'file_size' => filesize($outputPath),
        ]);

        $this->sendApprovalEmail($sertifikat);

        return response()->json(['message' => 'Sertifikat disetujui. QR Code verifikasi berhasil ditambahkan.']);
    }

    public function revise(Request $request, Sertifikat $sertifikat)
    {
        $validated = $request->validate([
            'catatan_revisi' => 'required|string|max:1000',
        ]);

        $sertifikat->update([
            'status' => 'direvisi',
            'catatan_revisi' => $validated['catatan_revisi'],
            'file_disahkan' => null,
            'validated_by' => null,
            'validated_at' => null,
        ]);

        $this->sendRevisionEmail($sertifikat);

        return response()->json(['message' => 'Sertifikat dikembalikan untuk direvisi.']);
    }

    public function cancel(Sertifikat $sertifikat)
    {
        $sertifikat->update([
            'status' => 'diajukan',
            'file_disahkan' => null,
            'validated_by' => null,
            'validated_at' => null,
        ]);

        return response()->json(['message' => 'Sertifikat dibatalkan dari antrian validasi.']);
    }

    // ============ PRIVATE HELPERS ============

    private function stampQrOnImage(
        string $imagePath,
        string $qrImage,
        string $outputPath,
        float $xPercent,
        float $yPercent,
        float $sizePercent
    ): void {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($imagePath);

        $width = $image->width();
        $height = $image->height();

        $qrSize = ($sizePercent / 100) * $width;
        $qrX = ($xPercent / 100) * $width;
        $qrY = ($yPercent / 100) * $height;

        $qrSize = min($qrSize, $width, $height);
        $qrX = max(0, min($qrX, $width - $qrSize));
        $qrY = max(0, min($qrY, $height - $qrSize));

        $qrImageRes = $manager->read($qrImage);
        $qrImageRes->scale(width: (int) $qrSize, height: (int) $qrSize);

        $image->place($qrImageRes, 'top-left', (int) $qrX, (int) $qrY);

        $this->addLogoToQr($image, $qrX, $qrY, $qrSize);

        $image->toPng()->save($outputPath);
    }

    private function addLogoToQr($image, float $qrX, float $qrY, float $qrSize): void
    {
        $logoPath = public_path('images/logo.png');
        if (!file_exists($logoPath)) return;

        try {
            $manager = new ImageManager(new Driver());
            $logo = $manager->read($logoPath);

            $logoSize = (int) ($qrSize * 0.18);
            $logoX = (int) ($qrX + ($qrSize / 2) - ($logoSize / 2));
            $logoY = (int) ($qrY + ($qrSize / 2) - ($logoSize / 2));

            $logo->scale(width: $logoSize, height: $logoSize);
            $image->place($logo, 'top-left', $logoX, $logoY);
        } catch (\Throwable $e) {
            Log::warning('Gagal menambahkan logo: ' . $e->getMessage());
        }
    }

    private function sendApprovalEmail(Sertifikat $sertifikat): void
    {
        try {
            $fresh = Sertifikat::with(['uploader', 'validator'])->find($sertifikat->id);
            if ($fresh->uploader && $fresh->uploader->email) {
                Mail::to($fresh->uploader->email)->send(new SertifikatDisetujui($fresh));
            }
        } catch (\Throwable $e) {
            Log::error('Email approval error: ' . $e->getMessage());
        }
    }

    private function sendRevisionEmail(Sertifikat $sertifikat): void
    {
        try {
            $fresh = Sertifikat::with(['uploader', 'validator'])->find($sertifikat->id);
            if ($fresh->uploader && $fresh->uploader->email) {
                Mail::to($fresh->uploader->email)->send(new SertifikatDirevisi($fresh));
            }
        } catch (\Throwable $e) {
            Log::error('Email revision error: ' . $e->getMessage());
        }
    }
}
