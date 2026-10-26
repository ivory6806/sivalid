<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\Sertifikat;

class VerifyController extends Controller
{
    public function surat($token)
    {
        $surat = Surat::with(['user', 'unit'])->where('qr_token', $token)->first();

        if (!$surat) {
            return response()->json([
                'valid' => false,
                'message' => 'Dokumen tidak ditemukan.',
            ]);
        }

        if ($surat->status !== 'disetujui') {
            return response()->json([
                'valid' => false,
                'message' => 'Dokumen belum disetujui.',
                'surat' => [
                    'nomor_surat' => $surat->nomor_surat,
                    'status' => $surat->status,
                ],
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Dokumen valid dan sudah disetujui.',
            'surat' => [
                'id' => $surat->id,
                'nomor_surat' => $surat->nomor_surat,
                'nama_surat' => $surat->nama_surat,
                'nama_mahasiswa' => $surat->nama_mahasiswa,
                'nim' => $surat->nim,
                'prodi' => $surat->prodi,
                'semester' => $surat->semester,
                'tahun_ajaran' => $surat->tahun_ajaran,
                'tanggal_surat' => $surat->tanggal_surat,
                'status' => $surat->status,
                'validated_at' => $surat->validated_at,
                'unit' => $surat->unit?->name,
            ],
        ]);
    }

    public function sertifikat($token)
    {
        $sertifikat = Sertifikat::with('validator')->where('qr_token', $token)->first();

        if (!$sertifikat) {
            return response()->json([
                'valid' => false,
                'message' => 'Sertifikat tidak ditemukan.',
            ]);
        }

        if ($sertifikat->status !== 'disetujui') {
            return response()->json([
                'valid' => false,
                'message' => 'Sertifikat belum disetujui.',
                'sertifikat' => [
                    'nomor_sertifikat' => $sertifikat->nomor_sertifikat,
                    'status' => $sertifikat->status,
                ],
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'Sertifikat valid dan sudah disetujui.',
            'sertifikat' => [
                'id' => $sertifikat->id,
                'nomor_sertifikat' => $sertifikat->nomor_sertifikat,
                'jenis_sertifikat' => $sertifikat->jenis_sertifikat,
                'status' => $sertifikat->status,
                'validated_at' => $sertifikat->validated_at,
                'validated_by' => $sertifikat->validator?->name,
            ],
        ]);
    }
}
