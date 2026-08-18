<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\User;
use App\Models\Setting;
use App\Mail\SuratDiajukan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use setasign\Fpdi\Fpdi;

class SuratController extends Controller
{
public function index(Request $request)
{
    $query = \App\Models\Surat::query();

    // Scoping per unit: non-superadmin hanya lihat surat di unit aktif (session)
    if (!Auth::user()->isSuperadmin()) {
        $query->where('unit_id', session('active_unit_id'));
    }

    $activeUnit = \App\Models\Unit::find(session('active_unit_id'));
    $useGenericView = Auth::user()->isSuperadmin() || !$activeUnit || $activeUnit->code !== 'akademik';

    if ($useGenericView) {
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('nama_surat', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $query->latest();
        $surats = $query->with('unit')->paginate(10)->withQueryString();

        return view('surat.index-generic', compact('surats'));
    }

    // Search
    if ($search = $request->get('search')) {
        $query->where(function ($q) use ($search) {
            $q->where('nomor_surat', 'like', "%{$search}%")
              ->orWhere('nama_mahasiswa', 'like', "%{$search}%")
              ->orWhere('nim', 'like', "%{$search}%");
        });
    }

    // Filter status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Filter prodi
    if ($request->filled('prodi')) {
        $query->where('prodi', $request->prodi);
    }

    // Sort
    $allowedSorts = ['nomor_surat', 'nama_mahasiswa', 'nim', 'prodi', 'semester', 'status', 'created_at'];
    $sort = in_array($request->get('sort'), $allowedSorts) ? $request->get('sort') : 'created_at';
    $dir = $request->get('dir') === 'asc' ? 'asc' : 'desc';
    $query->orderBy($sort, $dir);

    // Daftar prodi untuk dropdown
    $prodis = \App\Models\Surat::distinct()->orderBy('prodi')->pluck('prodi');

    $surats = $query->paginate(10)->withQueryString();

    return view('surat.index', compact('surats', 'prodis'));
}

    public function create()
    {
        $unit = \App\Models\Unit::find(session('active_unit_id'));

        if ($unit && $unit->code !== 'akademik') {
            return view('surat.create-generic', compact('unit'));
        }

        return view('surat.create', [
            'prodiList'    => Setting::getOptions('master_prodi'),
            'semesterList' => Setting::getOptions('master_semester'),
            'taList'       => Setting::getOptions('master_tahun_ajaran'),
        ]);
    }

    public function store(Request $request)
    {
        $unit = \App\Models\Unit::find(session('active_unit_id'));

        if ($unit && $unit->code !== 'akademik') {
            return $this->storeGeneric($request, $unit);
        }

        $validated = $request->validate([
            'nomor_surat'       => 'required|string|max:50',
            'nama_mahasiswa'    => 'required|string|max:255',
            'nim'               => 'required|string|max:20',
            'prodi'             => 'required|string|max:255',
            'semester'          => 'required|string|max:20',
            'tahun_ajaran'      => 'required|string|max:20',
            // 'hubungan'          => 'required|in:Ayah,Ibu,Wali',
            // 'nama_orangtua'     => 'required|string|max:255',
            // 'pekerjaan'         => 'required|string|max:255',
            // 'alamat_orangtua'   => 'required|string',
            'file_surat'        => 'required|file|mimes:pdf|max:5120',
        ]);

        $file     = $request->file('file_surat');
        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $file->storeAs('surat', $fileName, 'public');

        $unitId = session('active_unit_id');
        $unit = \App\Models\Unit::find($unitId);

        $surat = Surat::create([
            'user_id'           => Auth::id(),
            'unit_id'           => $unitId,
            'nama_surat'        => $unit && $unit->code === 'akademik' ? 'Surat Keterangan Aktif Kuliah' : ($unit->jenis_dokumen_label ?? 'Pengajuan Surat'),
            'nomor_surat'       => $validated['nomor_surat'],
            'file_surat'        => $fileName,
            'qr_token'          => Str::random(64),
            'nama_mahasiswa'    => $validated['nama_mahasiswa'],
            'nim'               => $validated['nim'],
            'prodi'             => $validated['prodi'],
            'semester'          => $validated['semester'],
            'tahun_ajaran'      => $validated['tahun_ajaran'],
            // 'nama_ayah'         => $namaAyah,
            // 'nama_ibu'          => $namaIbu,
            // 'pekerjaan'         => $validated['pekerjaan'],
            // 'alamat_orangtua'   => $validated['alamat_orangtua'],
        ]);

        $suratFresh = Surat::with(['user', 'validator'])->find($surat->id);
        $kepala = $this->findKepalaForUnit($unitId);
        if ($kepala) {
            Mail::to($kepala->email)->send(new SuratDiajukan($suratFresh));
        }

        return redirect()->route('surat.index')
            ->with('success', 'Surat keterangan aktif berhasil diajukan.');
    }

    /**
     * Cari Kepala aktif untuk unit tertentu (lewat tabel unit_user).
     * Fallback ke Kepala legacy (kolom role) kalau unit belum punya kepala eksplisit.
     */
    private function findKepalaForUnit(?int $unitId): ?User
    {
        if ($unitId) {
            $unit = \App\Models\Unit::find($unitId);
            $kepala = $unit?->kepalas()->first();
            if ($kepala) {
                return $kepala;
            }
        }

        return User::where('role', 'kepala')->where('is_active', true)->first();
    }

    public function edit(Surat $surat)
    {
        if ($surat->user_id !== Auth::id()) {
            abort(403);
        }
        if ($surat->status !== 'direvisi') {
            abort(403, 'Surat hanya bisa diedit saat status direvisi.');
        }

        if ($surat->is_generic) {
            return view('surat.edit-generic', compact('surat'));
        }

        return view('surat.edit', [
            'surat'       => $surat,
            'prodiList'    => Setting::getOptions('master_prodi'),
            'semesterList' => Setting::getOptions('master_semester'),
            'taList'       => Setting::getOptions('master_tahun_ajaran'),
        ]);
    }

    public function update(Request $request, Surat $surat)
    {
        if ($surat->user_id !== Auth::id()) {
            abort(403);
        }
        if ($surat->status !== 'direvisi') {
            abort(403, 'Surat hanya bisa diedit saat status direvisi.');
        }

        if ($surat->is_generic) {
            return $this->updateGeneric($request, $surat);
        }

        $validated = $request->validate([
            'nomor_surat'       => 'required|string|max:50',
            'nama_mahasiswa'    => 'required|string|max:255',
            'nim'               => 'required|string|max:20',
            'prodi'             => 'required|string|max:255',
            'semester'          => 'required|string|max:20',
            'tahun_ajaran'      => 'required|string|max:20',
            // 'hubungan'          => 'required|in:Ayah,Ibu,Wali',
            // 'nama_orangtua'     => 'required|string|max:255',
            // 'pekerjaan'         => 'required|string|max:255',
            // 'alamat_orangtua'   => 'required|string',
            'file_surat'        => 'nullable|file|mimes:pdf|max:5120',
        ]);

        // if ($validated['hubungan'] === 'Ayah') {
        //     $namaAyah = $validated['nama_orangtua'];
        //     $namaIbu  = '';
        // } elseif ($validated['hubungan'] === 'Ibu') {
        //     $namaAyah = '';
        //     $namaIbu  = $validated['nama_orangtua'];
        // } else {
        //     $namaAyah = $validated['nama_orangtua'];
        //     $namaIbu  = '(Wali)';
        // }

        // Ganti file jika upload baru
        if ($request->hasFile('file_surat')) {
            if ($surat->file_surat && file_exists(storage_path('app/public/surat/' . $surat->file_surat))) {
                unlink(storage_path('app/public/surat/' . $surat->file_surat));
            }
            $file = $request->file('file_surat');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('surat', $fileName, 'public');
        } else {
            $fileName = $surat->file_surat;
        }

        $surat->update([
            'nomor_surat'       => $validated['nomor_surat'],
            'file_surat'        => $fileName,
            'nama_mahasiswa'    => $validated['nama_mahasiswa'],
            'nim'               => $validated['nim'],
            'prodi'             => $validated['prodi'],
            'semester'          => $validated['semester'],
            'tahun_ajaran'      => $validated['tahun_ajaran'],
            // 'nama_ayah'         => $namaAyah,
            // 'nama_ibu'          => $namaIbu,
            // 'pekerjaan'         => $validated['pekerjaan'],
            // 'alamat_orangtua'   => $validated['alamat_orangtua'],
            'status'            => 'diajukan',
            'file_disahkan'     => null,
            'validated_by'      => null,
            'validated_at'      => null,
        ]);

        $suratFresh = Surat::with(['user', 'validator'])->find($surat->id);
        $kepala = $this->findKepalaForUnit($surat->unit_id);
        if ($kepala) {
            Mail::to($kepala->email)->send(new SuratDiajukan($suratFresh));
        }

        return redirect()->route('surat.index')
            ->with('success', 'Surat berhasil direvisi dan diajukan ulang.');
    }

    /**
     * Pengajuan surat generik untuk unit selain Akademik.
     * Field bebas: nama surat, nomor surat, tanggal surat, upload PDF (bisa multi-halaman).
     */
    private function storeGeneric(Request $request, \App\Models\Unit $unit)
    {
        $validated = $request->validate([
            'nama_surat'    => 'required|string|max:255',
            'nomor_surat'   => 'required|string|max:50|unique:surats,nomor_surat,NULL,id,unit_id,' . $unit->id,
            'tanggal_surat' => 'required|date',
            'file_surat'    => 'required|file|mimes:pdf|max:10240',
        ], [
            'nomor_surat.unique' => 'Nomor surat ini sudah dipakai di unit yang sama.',
        ]);

        $file     = $request->file('file_surat');
        $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $file->storeAs('surat', $fileName, 'public');

        $totalHalaman = null;
        try {
            $pdf = new Fpdi();
            $totalHalaman = $pdf->setSourceFile(storage_path('app/public/surat/' . $fileName));
        } catch (\Throwable $e) {
            $totalHalaman = null;
        }

        $surat = Surat::create([
            'user_id'       => Auth::id(),
            'unit_id'       => $unit->id,
            'nama_surat'    => $validated['nama_surat'],
            'nomor_surat'   => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'file_surat'    => $fileName,
            'total_halaman' => $totalHalaman,
            'qr_token'      => Str::random(64),
        ]);

        $suratFresh = Surat::with(['user', 'validator'])->find($surat->id);
        $kepala = $this->findKepalaForUnit($unit->id);
        if ($kepala) {
            Mail::to($kepala->email)->send(new SuratDiajukan($suratFresh));
        }

        return redirect()->route('surat.index')
            ->with('success', 'Surat berhasil diajukan.');
    }

    private function cleanOldTempFiles()
    {
        try {
            $files = Storage::disk('public')->files('surat_temp');
            foreach ($files as $file) {
                if (Storage::disk('public')->lastModified($file) < now()->subHour()->timestamp) {
                    Storage::disk('public')->delete($file);
                }
            }
        } catch (\Exception $e) {}
    }

    private function updateGeneric(Request $request, Surat $surat)
    {
        $unit = $surat->unit;

        $validated = $request->validate([
            'nama_surat'    => 'required|string|max:255',
            'nomor_surat'   => 'required|string|max:50|unique:surats,nomor_surat,' . $surat->id . ',id,unit_id,' . $surat->unit_id,
            'tanggal_surat' => 'required|date',
            'file_surat'    => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'nomor_surat.unique' => 'Nomor surat ini sudah dipakai di unit yang sama.',
        ]);

        if ($request->hasFile('file_surat')) {
            if ($surat->file_surat && file_exists(storage_path('app/public/surat/' . $surat->file_surat))) {
                unlink(storage_path('app/public/surat/' . $surat->file_surat));
            }
            $file = $request->file('file_surat');
            $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $file->storeAs('surat', $fileName, 'public');

            $totalHalaman = null;
            try {
                $pdf = new Fpdi();
                $totalHalaman = $pdf->setSourceFile(storage_path('app/public/surat/' . $fileName));
            } catch (\Throwable $e) {
                $totalHalaman = $surat->total_halaman;
            }
        } else {
            $fileName = $surat->file_surat;
            $totalHalaman = $surat->total_halaman;
        }

        $surat->update([
            'nama_surat'    => $validated['nama_surat'],
            'nomor_surat'   => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'file_surat'    => $fileName,
            'total_halaman' => $totalHalaman,
            'status'        => 'diajukan',
            'file_disahkan' => null,
            'validated_by'  => null,
            'validated_at'  => null,
        ]);

        $suratFresh = Surat::with(['user', 'validator'])->find($surat->id);
        $kepala = $this->findKepalaForUnit($surat->unit_id);
        if ($kepala) {
            Mail::to($kepala->email)->send(new SuratDiajukan($suratFresh));
        }

        return redirect()->route('surat.index')
            ->with('success', 'Surat berhasil direvisi dan diajukan ulang.');
    }

    public function show(Surat $surat)
    {
        $surat->load(['user', 'validator', 'unit']);
        if (Auth::user()->isStaff() && $surat->user_id !== Auth::id()) {
            abort(403);
        }
        if (!Auth::user()->isSuperadmin() && $surat->unit_id !== session('active_unit_id')) {
            abort(403, 'Surat ini bukan milik unit yang sedang aktif.');
        }

        if ($surat->is_generic) {
            return view('surat.detail-generic', compact('surat'));
        }

        return view('surat.detail', ['surat' => $surat]);
    }

    public function download(Surat $surat)
    {
        if ($surat->status === 'disetujui' && $surat->file_disahkan) {
            $relativePath = 'surat_disahkan/' . $surat->file_disahkan;
        } else {
            $relativePath = 'surat/' . $surat->file_surat;
        }
        $diskPath = storage_path('app/public/' . $relativePath);
        if (!file_exists($diskPath)) {
            abort(404, 'File tidak ditemukan: ' . $relativePath);
        }
        return response()->download($diskPath);
    }

    public function destroy(Surat $surat)
    {
        if ($surat->file_surat && file_exists(storage_path('app/public/surat/' . $surat->file_surat))) {
            unlink(storage_path('app/public/surat/' . $surat->file_surat));
        }
        if ($surat->file_disahkan && file_exists(storage_path('app/public/surat_disahkan/' . $surat->file_disahkan))) {
            unlink(storage_path('app/public/surat_disahkan/' . $surat->file_disahkan));
        }
        $surat->delete();
        return redirect()->route('surat.index')
            ->with('success', 'Surat berhasil dihapus.');
    }

    /**
     * Preview PDF di browser (inline), bukan download
     */
    public function preview(Surat $surat)
    {
        $filePath = null;

        // Prioritas: file yang sudah disahkan (ada QR), fallback ke file asli
        if ($surat->file_disahkan && file_exists(storage_path('app/public/surat_disahkan/' . $surat->file_disahkan))) {
            $filePath = storage_path('app/public/surat_disahkan/' . $surat->file_disahkan);
        } elseif ($surat->file_surat && file_exists(storage_path('app/public/surat/' . $surat->file_surat))) {
            $filePath = storage_path('app/public/surat/' . $surat->file_surat);
        }

        if (!$filePath) {
            abort(404, 'File PDF tidak ditemukan.');
        }

        return response()->file($filePath, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
        ]);
    }
}