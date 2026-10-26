<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use App\Models\User;
use App\Mail\SertifikatDiajukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SertifikatController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Sertifikat::query();

        if ($user->isStaff() && !$user->isSuperadmin()) {
            $query->where('uploaded_by', $user->id);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_sertifikat', 'like', "%{$search}%")
                    ->orWhere('jenis_sertifikat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $query->latest();
        $sertifikats = $query->with(['uploader', 'validator'])->paginate($request->get('per_page', 15));

        return response()->json($sertifikats);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_sertifikat' => 'required|string|max:255',
            'nomor_sertifikat' => 'required|string|max:100|unique:sertifikats,nomor_sertifikat',
            'file_sertifikat' => 'required|file|mimes:png,jpg,jpeg|max:10240',
        ]);

        $file = $request->file('file_sertifikat');
        $extension = strtolower($file->getClientOriginalExtension());
        $fileName = time() . '_' . Str::random(6) . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;

        $file->storeAs('sertifikat', $fileName, 'public');

        try {
            $manager = new ImageManager(new Driver());
            $fullPath = storage_path('app/public/sertifikat/' . $fileName);
            $image = $manager->read($fullPath);
            if ($image->width() > 2048 || $image->height() > 2048) {
                $image->scale(width: 2048);
                $image->toPng()->save($fullPath);
            }
        } catch (\Throwable $e) {
            Log::error('Gagal optimasi gambar: ' . $e->getMessage());
        }

        $sertifikat = Sertifikat::create([
            'uploaded_by' => $request->user()->id,
            'jenis_sertifikat' => $validated['jenis_sertifikat'],
            'nomor_sertifikat' => $validated['nomor_sertifikat'],
            'file_asli' => $fileName,
            'file_original_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'qr_token' => Str::random(64),
            'submitted_at' => now(),
        ]);

        try {
            $kepala = User::where('role', 'kepala')->where('is_active', true)->first();
            if ($kepala) {
                $sertifikatFresh = Sertifikat::with(['uploader', 'validator'])->find($sertifikat->id);
                Mail::to($kepala->email)->send(new SertifikatDiajukan($sertifikatFresh));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Sertifikat berhasil diajukan untuk divalidasi.',
            'sertifikat' => $sertifikat->load(['uploader', 'validator']),
        ], 201);
    }

    public function show(Request $request, Sertifikat $sertifikat)
    {
        $user = $request->user();

        if ($user->isStaff() && !$user->isSuperadmin() && $sertifikat->uploaded_by !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json($sertifikat->load(['uploader', 'validator']));
    }

    public function update(Request $request, Sertifikat $sertifikat)
    {
        $user = $request->user();

        if ($sertifikat->uploaded_by !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }
        if ($sertifikat->status !== 'direvisi') {
            return response()->json(['message' => 'Sertifikat hanya bisa diedit saat status direvisi.'], 403);
        }

        $validated = $request->validate([
            'jenis_sertifikat' => 'required|string|max:255',
            'nomor_sertifikat' => 'required|string|max:100|unique:sertifikats,nomor_sertifikat,' . $sertifikat->id,
            'file_sertifikat' => 'nullable|file|mimes:png,jpg,jpeg|max:10240',
        ]);

        $fileName = $sertifikat->file_asli;

        if ($request->hasFile('file_sertifikat')) {
            if ($sertifikat->file_asli && Storage::disk('public')->exists('sertifikat/' . $sertifikat->file_asli)) {
                Storage::disk('public')->delete('sertifikat/' . $sertifikat->file_asli);
            }

            $file = $request->file('file_sertifikat');
            $extension = strtolower($file->getClientOriginalExtension());
            $fileName = time() . '_' . Str::random(6) . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
            $file->storeAs('sertifikat', $fileName, 'public');

            try {
                $manager = new ImageManager(new Driver());
                $fullPath = storage_path('app/public/sertifikat/' . $fileName);
                $image = $manager->read($fullPath);
                if ($image->width() > 2048 || $image->height() > 2048) {
                    $image->scale(width: 2048);
                    $image->toPng()->save($fullPath);
                }
            } catch (\Throwable $e) {
                Log::error('Gagal optimasi gambar: ' . $e->getMessage());
            }
        }

        $sertifikat->update([
            'jenis_sertifikat' => $validated['jenis_sertifikat'],
            'nomor_sertifikat' => $validated['nomor_sertifikat'],
            'file_asli' => $fileName,
            'file_original_name' => $request->hasFile('file_sertifikat') ? $request->file('file_sertifikat')->getClientOriginalName() : $sertifikat->file_original_name,
            'file_type' => $request->hasFile('file_sertifikat') ? $request->file('file_sertifikat')->getClientMimeType() : $sertifikat->file_type,
            'file_size' => $request->hasFile('file_sertifikat') ? $request->file('file_sertifikat')->getSize() : $sertifikat->file_size,
            'status' => 'diajukan',
            'file_disahkan' => null,
            'validated_by' => null,
            'validated_at' => null,
            'submitted_at' => now(),
        ]);

        try {
            $kepala = User::where('role', 'kepala')->where('is_active', true)->first();
            if ($kepala) {
                $sertifikatFresh = Sertifikat::with(['uploader', 'validator'])->find($sertifikat->id);
                Mail::to($kepala->email)->send(new SertifikatDiajukan($sertifikatFresh));
            }
        } catch (\Throwable $e) {
            Log::error('Gagal kirim email: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Sertifikat berhasil direvisi dan diajukan ulang.',
            'sertifikat' => $sertifikat->load(['uploader', 'validator']),
        ]);
    }

    public function destroy(Request $request, Sertifikat $sertifikat)
    {
        $user = $request->user();

        if ($sertifikat->uploaded_by !== $user->id && !$user->isSuperadmin()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($sertifikat->file_asli) {
            Storage::disk('public')->delete('sertifikat/' . $sertifikat->file_asli);
        }
        if ($sertifikat->file_disahkan) {
            Storage::disk('public')->delete('sertifikat_disahkan/' . $sertifikat->file_disahkan);
        }

        $sertifikat->delete();

        return response()->json(['message' => 'Sertifikat berhasil dihapus.']);
    }

    public function preview(Request $request, Sertifikat $sertifikat)
    {
        $user = $request->user();

        if ($user->isStaff() && !$user->isSuperadmin() && $sertifikat->uploaded_by !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $relativePath = $this->getFilePath($sertifikat);
        if (!$relativePath) {
            return response()->json(['message' => 'File tidak ditemukan.'], 404);
        }

        $url = Storage::disk('public')->url($relativePath);
        return response()->json(['url' => $url]);
    }

    public function download(Request $request, Sertifikat $sertifikat)
    {
        $user = $request->user();

        if ($user->isStaff() && !$user->isSuperadmin() && $sertifikat->uploaded_by !== $user->id) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $relativePath = $this->getFilePath($sertifikat);
        if (!$relativePath) {
            return response()->json(['message' => 'File tidak ditemukan.'], 404);
        }

        $url = Storage::disk('public')->url($relativePath);
        $filename = basename($relativePath);

        return response()->json(['url' => $url, 'filename' => $filename]);
    }

    private function getFilePath(Sertifikat $sertifikat): ?string
    {
        if ($sertifikat->status === 'disetujui' && $sertifikat->file_disahkan) {
            $path = 'sertifikat_disahkan/' . $sertifikat->file_disahkan;
            if (Storage::disk('public')->exists($path)) return $path;
        }

        if ($sertifikat->file_asli) {
            $path = 'sertifikat/' . $sertifikat->file_asli;
            if (Storage::disk('public')->exists($path)) return $path;
        }

        return null;
    }
}
