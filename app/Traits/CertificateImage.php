<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait RendersCertificateImage
{
    /**
     * Konversi hex color ke array RGB
     */
    protected function hexToRgb(string $hex): array
    {
        $hex = str_replace('#', '', $hex);
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        return [
            'r' => hexdec(substr($hex, 0, 2)),
            'g' => hexdec(substr($hex, 2, 2)),
            'b' => hexdec(substr($hex, 4, 2)),
        ];
    }

    /**
     * Render teks elemen ke gambar GD
     */
    protected function renderElementsToImage(
        \GdImage $img,
        \Illuminate\Database\Eloquent\Collection $elements,
        array $rowData,
        array $mapping,
        string $fontPath
    ): void {
        $elementsByKey = $elements->keyBy('element_key');
        $boldFontPath = public_path('fonts/arialbd.ttf');

        foreach ($mapping as $elementKey => $columnName) {
            if (!$elementsByKey->has($elementKey)) {
                continue;
            }

            $el = $elementsByKey[$elementKey];
            $text = $rowData[$columnName] ?? '';

            if (empty($text)) {
                continue;
            }

            $rgb = $this->hexToRgb($el->color);
            $color = imagecolorallocate($img, $rgb['r'], $rgb['g'], $rgb['b']);

            $currentFont = $el->is_bold && file_exists($boldFontPath)
                ? $boldFontPath
                : $fontPath;

            if (file_exists($currentFont)) {
                imagettftext($img, $el->font_size, 0, $el->x, $el->y, $color, $currentFont, $text);
            } else {
                imagestring($img, 5, $el->x, $el->y, $text, $color);
            }
        }
    }

    /**
     * Tempel QR Code ke gambar
     */
    protected function stampQrCode(
        \GdImage $img,
        string $qrTempPath,
        int $qrX,
        int $qrY,
        int $qrSize
    ): void {
        if (!file_exists($qrTempPath)) {
            return;
        }

        $qrImg = imagecreatefrompng($qrTempPath);
        imagecopyresampled(
            $img, $qrImg,
            $qrX, $qrY, 0, 0,
            $qrSize, $qrSize,
            imagesx($qrImg), imagesy($qrImg)
        );
        imagedestroy($qrImg);
    }

    /**
     * Konversi gambar GD ke PDF menggunakan FPDF
     */
    protected function imageToPdf(
        string $imagePath,
        string $pdfPath,
        int $widthPx,
        int $heightPx
    ): void {
        $wMm = ($widthPx / 96) * 25.4;
        $hMm = ($heightPx / 96) * 25.4;
        $orientation = ($wMm > $hMm) ? 'L' : 'P';

        $pdf = new \FPDF($orientation, 'mm', [$wMm, $hMm]);
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage();
        $pdf->Image($imagePath, 0, 0, $wMm, $hMm);
        $pdf->Output($pdfPath, 'F');
    }

    /**
     * Pastikan direktori ada
     */
    protected function ensureDirectory(string $path): void
    {
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }
}