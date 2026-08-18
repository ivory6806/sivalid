<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitSelectController extends Controller
{
    /**
     * Ditampilkan setelah login. Kalau user hanya punya 1 kombinasi unit+role,
     * langsung diarahkan ke dashboard tanpa perlu memilih.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isSuperadmin()) {
            return redirect()->route('dashboard');
        }

        $memberships = $user->unitMemberships()
            ->where('is_active', true)
            ->whereHas('unit', fn ($q) => $q->where('is_active', true))
            ->with('unit')
            ->get();

        // Transisi: user lama (kolom role kepala/staff) yang belum punya baris
        // unit_user sama sekali -> anggap sebagai anggota unit Akademik.
        if ($memberships->isEmpty() && in_array($user->role, ['kepala', 'staff'])) {
            $akademik = Unit::where('code', 'akademik')->where('is_active', true)->first();
            if ($akademik) {
                $this->setActiveUnit($akademik->id, $user->role, null, null);
                return redirect()->route('dashboard');
            }
        }

        if ($memberships->isEmpty()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Akun Anda belum ditugaskan ke unit manapun. Hubungi Superadmin.');
        }

        if ($memberships->count() === 1) {
            $m = $memberships->first();
            $this->setActiveUnit($m->unit_id, $m->role, $m->id, $m->jabatan);
            return redirect()->route('dashboard');
        }

        return view('units.pilih', compact('memberships'));
    }

    public function select(UnitUser $member)
    {
        $user = Auth::user();

        if ($member->user_id !== $user->id || ! $member->is_active) {
            abort(403);
        }

        $this->setActiveUnit($member->unit_id, $member->role, $member->id, $member->jabatan);

        return redirect()->route('dashboard');
    }

    /**
     * Dipanggil dari navbar ("Ganti Unit") kapan saja.
     */
    public function switch()
    {
        session()->forget(['active_unit_id', 'active_unit_role', 'active_unit_member_id', 'active_unit_jabatan']);
        return redirect()->route('units.select');
    }

    private function setActiveUnit(int $unitId, string $role, ?int $memberId, ?string $jabatan = null): void
    {
        session([
            'active_unit_id' => $unitId,
            'active_unit_role' => $role,
            'active_unit_member_id' => $memberId,
            'active_unit_jabatan' => $jabatan,
        ]);
    }
}
