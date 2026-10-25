<?php

namespace App\Http\Controllers;

use App\Models\Sertifikat;
use App\Models\User;
use App\Mail\SertifikatDiajukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class SertifikatController extends Controller
{
    private function getImageManager(): ImageManager
    {
        return new ImageManager(new Driver());
    }

    public function index(Request $request)
    {
        $query = Sertifikat::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_sertifikat', 'like', "%{$search}%")
                  ->orWhere('jenis_sertifikat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $allowedSorts = ['nomor_sertifikat', 'jenis_sertifikat', 'status', 'created_at'];
        $sort = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'created_at';
        $dir  = $request->get('dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sort, $dir);

        $sertifikats = $query->with(['uploader', 'validator'])->paginate(10)->withQueryString();

        return view('sertifikat.index', compact('sertifikats'));
    }

    public function create()
    {
        return view('sertifikat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_sertifikat'   => 'required|string|max:255',
            // Tambahkan whereNull('deleted_at') agar data yang dihapus tidak dihitung
            'nomor_sertifikat'   => 'nullable|string|max:100|unique:sertifikats,nomor_sertifikat,NULL,id,deleted_at,NULL',
            'file_sertifikat'    => 'required|file|mimes:png,jpg,jpeg|max:10240',
        ], [
            'file_sertifikat.max'   => 'Ukuran file maksimal 10 MB.',
            'file_sertifikat.mimes' => 'File harus berformat PNG, JPG, atau JPEG.',
        ]);

        $file = $request->file('file_sertifikat');
        $extension = strtolower($file->getClientOriginalExtension());
        $fileName = time() . '_' . Str::random(6) . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
        
        $filePath = 'sertifikat/' . $fileName;
        $file->storeAs('sertifikat', $fileName, 'public');
        
        try {
            $manager = $this->getImageManager();
            $fullPath = storage_path('app/public/' . $filePath);
            $image = $manager->read($fullPath);
            
            if ($image->width() > 2048 || $image->height() > 2048) {
                $image->scale(width: 2048);
                $image->toPng()->save($fullPath);
            }
        } catch (\Throwable $e) {
            \Log::error('Gagal optimasi gambar: ' . $e->getMessage());
        }

        $sertifikat = Sertifikat::create([
            'uploaded_by'         => Auth::id(),
            'jenis_sertifikat'    => $validated['jenis_sertifikat'],
            'nomor_sertifikat'    => $validated['nomor_sertifikat'],
            'file_asli'           => $fileName,
            'file_original_name'  => $file->getClientOriginalName(),
            'file_type'           => $file->getClientMimeType(),
            'file_size'           => $file->getSize(),
            'jumlah_halaman'      => 1,
            'qr_token'            => Str::random(64),
            'submitted_at'        => now(),
        ]);

        $sertifikatFresh = Sertifikat::with(['uploader', 'validator'])->find($sertifikat->id);
        $kepala = User::where('role', 'kepala')->where('is_active', true)->first();
        if ($kepala) {
            try {
                Mail::to($kepala->email)->send(new SertifikatDiajukan($sertifikatFresh));
            } catch (\Throwable $e) {
                \Log::error('Gagal kirim email pengajuan sertifikat: ' . $e->getMessage());
            }
        }

        return redirect()->route('sertifikat.index')
            ->with('success', 'Sertifikat berhasil diajukan untuk divalidasi.');
    }

    public function edit(Sertifikat $sertifikat)
    {
        if ($sertifikat->uploaded_by !== Auth::id()) {
            abort(403);
        }
        if ($sertifikat->status !== 'direvisi') {
            abort(403, 'Sertifikat hanya bisa diedit saat status direvisi.');
        }

        return view('sertifikat.edit', compact('sertifikat'));
    }

    public function update(Request $request, Sertifikat $sertifikat)
    {
        if ($sertifikat->uploaded_by !== Auth::id()) {
            abort(403);
        }
        if ($sertifikat->status !== 'direvisi') {
            abort(403, 'Sertifikat hanya bisa diedit saat status direvisi.');
        }

    $validated = $request->validate([
        'jenis_sertifikat' => 'required|string|max:255',
        // Abaikan ID saat ini dan abaikan data yang di soft-delete
        'nomor_sertifikat' => 'nullable|string|max:100|unique:sertifikats,nomor_sertifikat,' . $sertifikat->id . ',id,deleted_at,NULL',
        'file_sertifikat'  => 'nullable|file|mimes:png,jpg,jpeg|max:10240',
    ]);

        $fileName = $sertifikat->file_asli;

        if ($request->hasFile('file_sertifikat')) {
            if ($sertifikat->file_asli && Storage::disk('public')->exists('sertifikat/' . $sertifikat->file_asli)) {
                Storage::disk('public')->delete('sertifikat/' . $sertifikat->file_asli);
            }
            
            $file = $request->file('file_sertifikat');
            $extension = strtolower($file->getClientOriginalExtension());
            $fileName = time() . '_' . Str::random(6) . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $extension;
            
            $filePath = 'sertifikat/' . $fileName;
            $file->storeAs('sertifikat', $fileName, 'public');
            
            try {
                $manager = $this->getImageManager();
                $fullPath = storage_path('app/public/' . $filePath);
                $image = $manager->read($fullPath);
                if ($image->width() > 2048 || $image->height() > 2048) {
                    $image->scale(width: 2048);
                    $image->toPng()->save($fullPath);
                }
            } catch (\Throwable $e) {
                \Log::error('Gagal optimasi gambar: ' . $e->getMessage());
            }
        }

        $sertifikat->update([
            'jenis_sertifikat'   => $validated['jenis_sertifikat'],
            'nomor_sertifikat'   => $validated['nomor_sertifikat'],
            'file_asli'          => $fileName,
            'file_original_name' => $request->hasFile('file_sertifikat') ? $request->file('file_sertifikat')->getClientOriginalName() : $sertifikat->file_original_name,
            'file_type'          => $request->hasFile('file_sertifikat') ? $request->file('file_sertifikat')->getClientMimeType() : $sertifikat->file_type,
            'file_size'          => $request->hasFile('file_sertifikat') ? $request->file('file_sertifikat')->getSize() : $sertifikat->file_size,
            'status'             => 'diajukan',
            'file_disahkan'      => null,
            'validated_by'       => null,
            'validated_at'       => null,
            'submitted_at'       => now(),
        ]);

        $sertifikatFresh = Sertifikat::with(['uploader', 'validator'])->find($sertifikat->id);
        $kepala = User::where('role', 'kepala')->where('is_active', true)->first();
        if ($kepala) {
            try {
                Mail::to($kepala->email)->send(new SertifikatDiajukan($sertifikatFresh));
            } catch (\Throwable $e) {
                \Log::error('Gagal kirim email pengajuan ulang sertifikat: ' . $e->getMessage());
            }
        }

        return redirect()->route('sertifikat.index')
            ->with('success', 'Sertifikat berhasil direvisi dan diajukan ulang.');
    }

    public function show(Sertifikat $sertifikat)
    {
        $sertifikat->load(['uploader', 'validator']);
        if (Auth::user()->isStaff() && $sertifikat->uploaded_by !== Auth::id()) {
            abort(403);
        }
        return view('sertifikat.detail', compact('sertifikat'));
    }

    private function getFilePath(Sertifikat $sertifikat): ?string
    {
        if ($sertifikat->status === 'disetujui' && $sertifikat->file_disahkan) {
            $path = 'sertifikat_disahkan/' . $sertifikat->file_disahkan;
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }
        
        if ($sertifikat->file_asli) {
            $path = 'sertifikat/' . $sertifikat->file_asli;
            if (Storage::disk('public')->exists($path)) {
                return $path;
            }
        }
        
        return null;
    }

    public function preview(Sertifikat $sertifikat)
    {
        if (Auth::user()->isStaff() && $sertifikat->uploaded_by !== Auth::id()) {
            abort(403);
        }

        $relativePath = $this->getFilePath($sertifikat);
        
        if (!$relativePath) {
            abort(404, 'File tidak ditemukan.');
        }

        $fullPath = storage_path('app/public/' . $relativePath);
        
        if (!file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan di disk.');
        }

        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $contentType = match($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            default => mime_content_type($fullPath) ?: 'application/octet-stream',
        };

        return response()->file($fullPath, [
            'Content-Type' => $contentType,
            'Content-Disposition' => 'inline; filename="' . basename($fullPath) . '"',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function download(Sertifikat $sertifikat)
    {
        if (Auth::user()->isStaff() && $sertifikat->uploaded_by !== Auth::id()) {
            abort(403);
        }

        $relativePath = $this->getFilePath($sertifikat);
        
        if (!$relativePath) {
            abort(404, 'File tidak ditemukan.');
        }

        $fullPath = storage_path('app/public/' . $relativePath);
        
        if (!file_exists($fullPath)) {
            abort(404, 'File tidak ditemukan di disk.');
        }

        $actualExtension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));
        $nomorBersih = preg_replace('/[^A-Za-z0-9\-]+/', '-', $sertifikat->nomor_sertifikat);
        $originalBaseName = $sertifikat->file_original_name 
            ? pathinfo($sertifikat->file_original_name, PATHINFO_FILENAME) 
            : $nomorBersih;
        
        $downloadName = $originalBaseName;
        if ($sertifikat->status === 'disetujui' && $sertifikat->file_disahkan) {
            $downloadName .= '_disahkan';
        }
        $downloadName .= '.' . $actualExtension;
        
        $contentType = match($actualExtension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            default => mime_content_type($fullPath) ?: 'application/octet-stream',
        };

        return response()->download($fullPath, $downloadName, [
            'Content-Type' => $contentType,
        ]);
    }

    public function destroy(Sertifikat $sertifikat)
    {
        if ($sertifikat->uploaded_by !== Auth::id() && !Auth::user()->isSuperadmin()) {
            abort(403);
        }

        if ($sertifikat->file_asli) {
            Storage::disk('public')->delete('sertifikat/' . $sertifikat->file_asli);
        }
        if ($sertifikat->file_disahkan) {
            Storage::disk('public')->delete('sertifikat_disahkan/' . $sertifikat->file_disahkan);
        }
        $sertifikat->delete();

        return redirect()->route('sertifikat.index')
            ->with('success', 'Sertifikat berhasil dihapus.');
    }
}