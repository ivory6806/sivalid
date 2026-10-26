<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\Unit;
use App\Models\Setting;
use App\Models\User;
use App\Mail\SuratDiajukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use setasign\Fpdi\Fpdi;

class SuratController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $unitId = $request->header('X-Unit-Id');

        $query = Surat::query();

        if (!$user->isSuperadmin() || $unitId) {
            if ($unitId) $query->where('unit_id', $unitId);
        }

        if ($user->isStaff() && !$user->isSuperadmin()) {
            $query->where('user_id', $user->id);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhere('nama_surat', 'like', "%{$search}%")
                    ->orWhere('nama_mahasiswa', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->latest();
        $surats = $query->with(['user', 'unit'])->paginate($request->get('per_page', 15));

        return response()->json($surats);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $unitId = $request->header('X-Unit-Id');

        if (!$unitId) {
            return response()->json(['message' => 'Unit wajib dipilih. Kirim header X-Unit-Id.'], 422);
        }

        $unit = Unit::find($unitId);
        if (!$unit) {
            return response()->json(['message' => 'Unit tidak ditemukan.'], 404);
        }

        $isAkademik = $unit->code === 'akademik';

        if ($isAkademik) {
            $validated = $request->validate([
                'nomor_surat' => 'required|string|max:50',
                'nama_mahasiswa' => 'required|string|max:255',
                'nim' => 'required|string|max:20',
                'prodi' => 'required|string|max:255',
                'semester' => 'required|string|max:20',
                'tahun_ajaran' => 'required|string|max:20',
                'file_surat' => 'required|file|mimes:pdf|max:5120',
            ]);

            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::random(6) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('surat', $fileName, 'public');

            $surat = Surat::create([
                'user_id' => $user->id,
                'unit_id' => $unitId,
                'nama_surat' => 'Surat Keterangan Aktif Kuliah',
                'nomor_surat' => $validated['nomor_surat'],
                'file_surat' => $fileName,
                'qr_token' => Str::random(64),
                'nama_mahasiswa' => $validated['nama_mahasiswa'],
                'nim' => $validated['nim'],
                'prodi' => $validated['prodi'],
                'semester' => $validated['semester'],
                'tahun_ajaran' => $validated['tahun_ajaran'],
            ]);
        } else {
            $validated = $request->validate([
                'nama_surat' => 'required|string|max:255',
                'nomor_surat' => 'required|string|max:50|unique:surats,nomor_surat,NULL,id,unit_id,' . $unitId,
                'tanggal_surat' => 'required|date',
                'file_surat' => 'required|file|mimes:pdf|max:10240',
            ]);

            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::random(6) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('surat', $fileName, 'public');

            $totalHalaman = null;
            try {
                $pdf = new Fpdi();
                $totalHalaman = $pdf->setSourceFile(storage_path('app/public/surat/' . $fileName));
            } catch (\Throwable $e) {
                $totalHalaman = null;
            }

            $surat = Surat::create([
                'user_id' => $user->id,
                'unit_id' => $unitId,
                'nama_surat' => $validated['nama_surat'],
                'nomor_surat' => $validated['nomor_surat'],
                'tanggal_surat' => $validated['tanggal_surat'],
                'file_surat' => $fileName,
                'total_halaman' => $totalHalaman,
                'qr_token' => Str::random(64),
            ]);
        }

        $surat->load(['user', 'validator', 'unit']);

        try {
            $kepala = $unit->kepalas()->first();
            if ($kepala) {
                Mail::to($kepala->email)->send(new SuratDiajukan($surat));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email pengajuan surat: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Surat berhasil diajukan.',
            'surat' => $surat,
        ], 201);
    }

    public function show(Request $request, Surat $surat)
    {
        $user = $request->user();
        $unitId = $request->header('X-Unit-Id');

        if ($user->isStaff() && !$user->isSuperadmin() && $surat->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if (!$user->isSuperadmin() && $unitId && $surat->unit_id != $unitId) {
            return response()->json(['message' => 'Surat ini bukan milik unit yang aktif.'], 403);
        }

        $surat->load(['user', 'validator', 'unit']);

        return response()->json($surat);
    }

    public function update(Request $request, Surat $surat)
    {
        $user = $request->user();

        if ($surat->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        if ($surat->status !== 'direvisi') {
            return response()->json(['message' => 'Surat hanya bisa diedit saat status direvisi.'], 403);
        }

        $isGeneric = $surat->is_generic;

        if ($isGeneric) {
            $validated = $request->validate([
                'nama_surat' => 'required|string|max:255',
                'nomor_surat' => 'required|string|max:50|unique:surats,nomor_surat,' . $surat->id . ',id,unit_id,' . $surat->unit_id,
                'tanggal_surat' => 'required|date',
                'file_surat' => 'nullable|file|mimes:pdf|max:10240',
            ]);
        } else {
            $validated = $request->validate([
                'nomor_surat' => 'required|string|max:50',
                'nama_mahasiswa' => 'required|string|max:255',
                'nim' => 'required|string|max:20',
                'prodi' => 'required|string|max:255',
                'semester' => 'required|string|max:20',
                'tahun_ajaran' => 'required|string|max:20',
                'file_surat' => 'nullable|file|mimes:pdf|max:5120',
            ]);
        }

        if ($request->hasFile('file_surat')) {
            if ($surat->file_surat && file_exists(storage_path('app/public/surat/' . $surat->file_surat))) {
                unlink(storage_path('app/public/surat/' . $surat->file_surat));
            }
            $file = $request->file('file_surat');
            $fileName = time() . '_' . Str::random(6) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('surat', $fileName, 'public');

            $totalHalaman = $surat->total_halaman;
            if ($isGeneric) {
                try {
                    $pdf = new Fpdi();
                    $totalHalaman = $pdf->setSourceFile(storage_path('app/public/surat/' . $fileName));
                } catch (\Throwable $e) {}
            }
        } else {
            $fileName = $surat->file_surat;
            $totalHalaman = $surat->total_halaman;
        }

        $updateData = [
            'file_surat' => $fileName,
            'total_halaman' => $totalHalaman,
            'status' => 'diajukan',
            'file_disahkan' => null,
            'validated_by' => null,
            'validated_at' => null,
        ];

        if ($isGeneric) {
            $updateData['nama_surat'] = $validated['nama_surat'];
            $updateData['nomor_surat'] = $validated['nomor_surat'];
            $updateData['tanggal_surat'] = $validated['tanggal_surat'];
        } else {
            $updateData['nomor_surat'] = $validated['nomor_surat'];
            $updateData['nama_mahasiswa'] = $validated['nama_mahasiswa'];
            $updateData['nim'] = $validated['nim'];
            $updateData['prodi'] = $validated['prodi'];
            $updateData['semester'] = $validated['semester'];
            $updateData['tahun_ajaran'] = $validated['tahun_ajaran'];
        }

        $surat->update($updateData);
        $surat->load(['user', 'validator', 'unit']);

        try {
            $unit = Unit::find($surat->unit_id);
            $kepala = $unit?->kepalas()->first();
            if ($kepala) {
                Mail::to($kepala->email)->send(new SuratDiajukan($surat));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Surat berhasil direvisi dan diajukan ulang.',
            'surat' => $surat,
        ]);
    }

    public function destroy(Request $request, Surat $surat)
    {
        $user = $request->user();

        if ($surat->user_id !== $user->id && !$user->isSuperadmin()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($surat->file_surat && file_exists(storage_path('app/public/surat/' . $surat->file_surat))) {
            unlink(storage_path('app/public/surat/' . $surat->file_surat));
        }
        if ($surat->file_disahkan && file_exists(storage_path('app/public/surat_disahkan/' . $surat->file_disahkan))) {
            unlink(storage_path('app/public/surat_disahkan/' . $surat->file_disahkan));
        }

        $surat->delete();

        return response()->json(['message' => 'Surat berhasil dihapus.']);
    }

    public function download(Request $request, Surat $surat)
    {
        if ($surat->status === 'disetujui' && $surat->file_disahkan) {
            $relativePath = 'surat_disahkan/' . $surat->file_disahkan;
        } else {
            $relativePath = 'surat/' . $surat->file_surat;
        }

        $fullPath = storage_path('app/public/' . $relativePath);
        if (!file_exists($fullPath)) {
            return response()->json(['message' => 'File tidak ditemukan.'], 404);
        }

        $url = Storage::disk('public')->url($relativePath);
        return response()->json(['url' => $url, 'filename' => basename($fullPath)]);
    }

    public function settings()
    {
        return response()->json([
            'prodi' => Setting::getOptions('master_prodi'),
            'semester' => Setting::getOptions('master_semester'),
            'tahun_ajaran' => Setting::getOptions('master_tahun_ajaran'),
        ]);
    }
}
