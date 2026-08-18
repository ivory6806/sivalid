<?php

namespace App\Http\Controllers;

use App\Models\SertifikatBatch;
use Illuminate\Http\Request;

class KepalaBatchController extends Controller
{
    public function index()
    {
        $batches = SertifikatBatch::with(['template', 'user'])
            ->where('status', 'submitted')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('sertifikat.kepala.batches.index', compact('batches'));
    }

    public function show(SertifikatBatch $batch)
    {
        $batch->load(['template', 'user', 'sertifikats']);
        return view('sertifikat.kepala.batches.show', compact('batch'));
    }

    public function approve(Request $request, SertifikatBatch $batch)
    {
        if ($batch->status !== 'submitted') abort(403);

        // Simpan koordinat QR jika diisi
        $qrPosition = null;
        if ($request->filled('qr_x') && $request->filled('qr_y')) {
            $qrPosition = [
                'x' => (int) $request->qr_x,
                'y' => (int) $request->qr_y,
                'size' => (int) ($request->qr_size ?? 80),
            ];
        }

        $batch->update([
            'status' => 'approved', 
            'catatan' => $request->catatan,
            'qr_position' => $qrPosition // Simpan ke database
        ]);

        return redirect()->route('kepala.batches.index')->with('success', 'Batch "' . $batch->kegiatan . '" berhasil disetujui.');
    }

    public function reject(Request $request, SertifikatBatch $batch)
    {
        if ($batch->status !== 'submitted') abort(403);
        $request->validate(['catatan' => 'required|string|max:1000'], ['catatan.required' => 'Catatan wajib diisi saat menolak.']);
        $batch->update(['status' => 'rejected', 'catatan' => $request->catatan]);
        return redirect()->route('kepala.batches.index')->with('success', 'Batch "' . $batch->kegiatan . '" ditolak.');
    }
}