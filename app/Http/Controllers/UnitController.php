<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\UnitUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::withCount([
            'members as kepala_count' => fn ($q) => $q->where('role', 'kepala')->where('is_active', true),
            'members as staff_count' => fn ($q) => $q->where('role', 'staff')->where('is_active', true),
        ])->orderBy('name')->get();

        return view('units.index', compact('units'));
    }

    public function create()
    {
        return view('units.form');
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|alpha_dash|unique:units,code',
            'jenis_dokumen_label' => 'nullable|string|max:255',
        ]);

        $v['code'] = $v['code'] ?: Str::slug($v['name']);
        $v['jenis_dokumen_label'] = $v['jenis_dokumen_label'] ?: 'Pengajuan Surat';
        $v['is_active'] = true;

        $unit = Unit::create($v);

        return redirect()->route('units.edit', $unit)->with('success', 'Unit berhasil ditambahkan. Silakan tambahkan Kepala dan Staff untuk unit ini.');
    }

    public function edit(Unit $unit)
    {
        $unit->load(['members.user']);

        // User yang belum jadi anggota unit ini sama sekali, untuk dropdown tambah anggota.
        // Satu akun tetap boleh dipilih lagi untuk unit lain (tidak dibatasi lintas unit).
        $existingUserIds = $unit->members()->pluck('user_id');
        $availableUsers = User::where('is_superadmin', false)
            ->orderBy('name')
            ->get()
            ->reject(fn ($u) => $existingUserIds->contains($u->id) && $unit->members->where('user_id', $u->id)->count() >= 2);
            // reject hanya jika user itu sudah punya KEDUA role (kepala & staff) di unit ini

        return view('units.edit', compact('unit', 'availableUsers'));
    }

    public function update(Request $request, Unit $unit)
    {
        $v = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|alpha_dash|unique:units,code,' . $unit->id,
            'jenis_dokumen_label' => 'required|string|max:255',
        ]);

        $v['is_active'] = $request->boolean('is_active');

        $unit->update($v);

        return back()->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->code === 'akademik') {
            return back()->with('error', 'Unit Akademik tidak dapat dihapus karena menaungi fitur Surat Keterangan Aktif Kuliah.');
        }

        if ($unit->surats()->exists()) {
            return back()->with('error', 'Unit tidak dapat dihapus karena masih memiliki data surat.');
        }

        $unit->delete();

        return redirect()->route('units.index')->with('success', 'Unit berhasil dihapus.');
    }

    // ==========================================
    // KELOLA ANGGOTA (Kepala & Staff) — tidak dibatasi jumlahnya
    // ==========================================

    public function addMember(Request $request, Unit $unit)
    {
        $v = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:kepala,staff',
            'jabatan' => 'nullable|string|max:100',
        ]);

        $exists = UnitUser::where('unit_id', $unit->id)
            ->where('user_id', $v['user_id'])
            ->where('role', $v['role'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'User tersebut sudah menjadi ' . ucfirst($v['role']) . ' di unit ini.');
        }

        UnitUser::create([
            'unit_id' => $unit->id,
            'user_id' => $v['user_id'],
            'role' => $v['role'],
            'jabatan' => $v['jabatan'] ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', 'Anggota berhasil ditambahkan ke unit.');
    }

    public function updateMemberJabatan(Request $request, UnitUser $member)
    {
        $v = $request->validate([
            'jabatan' => 'nullable|string|max:100',
        ]);

        $member->update(['jabatan' => $v['jabatan'] ?: null]);

        return back()->with('success', 'Jabatan berhasil diperbarui.');
    }

    public function toggleMember(UnitUser $member)
    {
        $member->update(['is_active' => !$member->is_active]);

        return back()->with('success', $member->is_active ? 'Anggota diaktifkan kembali.' : 'Anggota dinonaktifkan.');
    }

    public function removeMember(UnitUser $member)
    {
        $member->delete();

        return back()->with('success', 'Anggota berhasil dikeluarkan dari unit.');
    }
}
