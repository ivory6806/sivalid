<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\Sertifikat;
use Illuminate\Http\Request;

class VerifyController extends Controller
{
    public function show($token)
    {
        $surat = Surat::where('qr_token', $token)->first();

        if (! $surat) {
            return view('verify.show', ['surat' => null, 'valid' => false]);
        }

        if ($surat->status !== 'disetujui') {
            return view('verify.show', ['surat' => $surat, 'valid' => false]);
        }

        return view('verify.show', ['surat' => $surat, 'valid' => true]);
    }

    public function sertifikat($token)
    {
        $sertifikat = Sertifikat::with('validator')->where('qr_token', $token)->first();

        if (! $sertifikat) {
            return view('verify.sertifikat', ['sertifikat' => null, 'valid' => false]);
        }

        if ($sertifikat->status !== 'disetujui') {
            return view('verify.sertifikat', ['sertifikat' => $sertifikat, 'valid' => false]);
        }

        return view('verify.sertifikat', ['sertifikat' => $sertifikat, 'valid' => true]);
    }
}