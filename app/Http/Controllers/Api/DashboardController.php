<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\User;
use App\Models\Sertifikat;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $unitId = $request->header('X-Unit-Id');
        $unitRole = $request->header('X-Unit-Role');

        // Fallback: tentukan role di unit aktif dari database jika header tidak dikirim
        if ($unitId && !$unitRole) {
            $membership = $user->unitMemberships()
                ->where('unit_id', $unitId)
                ->where('is_active', true)
                ->first();
            $unitRole = $membership?->role;
        }

        if ($user->isSuperadmin() && !$unitId) {
            return $this->superadmin();
        }

        if ($user->isSuperadmin() && $unitRole === 'kepala') {
            return $this->kepala($unitId);
        }

        if ($user->isSuperadmin() && $unitRole === 'staff') {
            return $this->staff($user, $unitId);
        }

        if ($unitRole === 'kepala') {
            return $this->kepala($unitId);
        }

        return $this->staff($user, $unitId);
    }

    private function staff($user, $unitId)
    {
        $q = Surat::where('user_id', $user->id);
        if ($unitId) $q->where('unit_id', $unitId);

        $total = (clone $q)->count();
        $diajukan = (clone $q)->where('status', 'diajukan')->count();
        $disetujui = (clone $q)->where('status', 'disetujui')->count();
        $direvisi = (clone $q)->where('status', 'direvisi')->count();

        $certQ = Sertifikat::where('uploaded_by', $user->id);
        $sertifikatTotal = (clone $certQ)->count();
        $sertifikatDiajukan = (clone $certQ)->where('status', 'diajukan')->count();
        $sertifikatDisetujui = (clone $certQ)->where('status', 'disetujui')->count();

        $recent = (clone $q)->latest()->take(5)->get();

        return response()->json([
            'role' => 'staff',
            'stats' => [
                'total' => $total,
                'diajukan' => $diajukan,
                'disetujui' => $disetujui,
                'direvisi' => $direvisi,
            ],
            // Bentuk flat yang dibaca aplikasi mobile
            'total_surat' => $total,
            'surat_diajukan' => $diajukan,
            'surat_disetujui' => $disetujui,
            'surat_direvisi' => $direvisi,
            'surat_pending_validasi' => 0,
            'total_validasi_hari_ini' => 0,
            'total_sertifikat' => $sertifikatTotal,
            'sertifikat_pending' => $sertifikatDiajukan,
            'recent_activity' => $this->formatRecentActivity($recent),
            'recent' => $recent,
        ]);
    }

    private function kepala($unitId)
    {
        $q = Surat::query();
        if ($unitId) $q->where('unit_id', $unitId);

        $total = (clone $q)->count();
        $diajukan = (clone $q)->where('status', 'diajukan')->count();
        $disetujui = (clone $q)->where('status', 'disetujui')->count();
        $direvisi = (clone $q)->where('status', 'direvisi')->count();
        $pendingValidasi = $diajukan + $direvisi;
        $validasiHariIni = (clone $q)->whereDate('validated_at', today())->count();

        $certQ = Sertifikat::query();
        $sertifikatPending = (clone $certQ)->whereIn('status', ['diajukan', 'direvisi'])->count();

        $recent = (clone $q)->with('user')->latest()->take(10)->get();

        return response()->json([
            'role' => 'kepala',
            'stats' => [
                'total' => $total,
                'diajukan' => $diajukan,
                'disetujui' => $disetujui,
                'direvisi' => $direvisi,
            ],
            // Bentuk flat yang dibaca aplikasi mobile
            'total_surat' => $total,
            'surat_diajukan' => $diajukan,
            'surat_disetujui' => $disetujui,
            'surat_direvisi' => $direvisi,
            'surat_pending_validasi' => $pendingValidasi,
            'total_validasi_hari_ini' => $validasiHariIni,
            'total_sertifikat' => Sertifikat::count(),
            'sertifikat_pending' => $sertifikatPending,
            'recent_activity' => $this->formatRecentActivity($recent),
            'recent' => $recent,
        ]);
    }

    private function superadmin()
    {
        $totalUsers = User::count();
        $totalSurat = Surat::count();
        $staffCount = User::where('role', 'staff')->count();
        $activeUsers = User::where('is_active', true)->count();
        $totalSertifikat = Sertifikat::count();
        $pendingSertifikat = Sertifikat::whereIn('status', ['diajukan', 'direvisi'])->count();
        $pendingSurat = Surat::whereIn('status', ['diajukan', 'direvisi'])->count();
        $validasiHariIni = Surat::whereDate('validated_at', today())->count();
        $recent = Surat::with(['user', 'unit'])->latest()->take(5)->get();

        return response()->json([
            'role' => 'superadmin',
            'stats' => [
                'total_users' => $totalUsers,
                'total_surat' => $totalSurat,
                'staff_count' => $staffCount,
                'active_users' => $activeUsers,
                'total_sertifikat' => $totalSertifikat,
                'pending_sertifikat' => $pendingSertifikat,
            ],
            // Bentuk flat yang dibaca aplikasi mobile
            'total_surat' => $totalSurat,
            'surat_diajukan' => $pendingSurat,
            'surat_disetujui' => Surat::where('status', 'disetujui')->count(),
            'surat_direvisi' => Surat::where('status', 'direvisi')->count(),
            'surat_pending_validasi' => $pendingSurat,
            'total_validasi_hari_ini' => $validasiHariIni,
            'total_sertifikat' => $totalSertifikat,
            'sertifikat_pending' => $pendingSertifikat,
            'recent_activity' => $this->formatRecentActivity($recent),
            'recent' => $recent,
        ]);
    }

    private function formatRecentActivity($surats)
    {
        return $surats->map(fn ($s) => [
            'description' => trim('Surat ' . ($s->nama_surat ?: $s->nomor_surat)
                . ($s->relationLoaded('user') && $s->user ? ' - ' . $s->user->name : '')),
            'type' => 'surat',
            'created_at' => $s->created_at?->toIso8601String(),
        ])->values()->all();
    }
}
