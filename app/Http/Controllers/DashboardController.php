<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->isSuperadmin()) {
            return $this->superadmin();
        }

        return session('active_unit_role') === 'kepala' ? $this->kepala() : $this->staff();
    }

    private function staff()
    {
        $uid = Auth::id();
        $unitId = session('active_unit_id');
        $total = Surat::where('user_id', $uid)->where('unit_id', $unitId)->count();
        $diajukan = Surat::where('user_id', $uid)->where('unit_id', $unitId)->where('status', 'diajukan')->count();
        $disetujui = Surat::where('user_id', $uid)->where('unit_id', $unitId)->where('status', 'disetujui')->count();
        $direvisi = Surat::where('user_id', $uid)->where('unit_id', $unitId)->where('status', 'direvisi')->count();
        $recent = Surat::where('user_id', $uid)->where('unit_id', $unitId)->latest()->take(5)->get();

        return view('dashboard.staff', compact('total', 'diajukan', 'disetujui', 'direvisi', 'recent'));
    }

    private function kepala()
    {
        $unitId = session('active_unit_id');
        $total = Surat::where('unit_id', $unitId)->count();
        $diajukan = Surat::where('unit_id', $unitId)->where('status', 'diajukan')->count();
        $disetujui = Surat::where('unit_id', $unitId)->where('status', 'disetujui')->count();
        $direvisi = Surat::where('unit_id', $unitId)->where('status', 'direvisi')->count();
        $recent = Surat::where('unit_id', $unitId)->with('user')->latest()->take(10)->get();

        return view('dashboard.kepala', compact('total', 'diajukan', 'disetujui', 'direvisi', 'recent'));
    }

    private function superadmin()
    {
        $totalUsers = User::count();
        $totalSurat = Surat::count();
        $staffCount = User::where('role', 'staff')->count();
        $activeUsers = User::where('is_active', true)->count();
        $recent = Surat::with(['user', 'unit'])->latest()->take(5)->get();

        return view('dashboard.superadmin', compact('totalUsers', 'totalSurat', 'staffCount', 'activeUsers', 'recent'));
    }
}
