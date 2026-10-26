<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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

        return response()->json($units);
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

        return response()->json([
            'message' => 'Unit berhasil ditambahkan.',
            'unit' => $unit,
        ], 201);
    }

    public function show(Unit $unit)
    {
        $unit->load(['members.user']);
        return response()->json($unit);
    }

    public function update(Request $request, Unit $unit)
    {
        $v = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|alpha_dash|unique:units,code,' . $unit->id,
            'jenis_dokumen_label' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $unit->update($v);

        return response()->json([
            'message' => 'Unit berhasil diperbarui.',
            'unit' => $unit,
        ]);
    }

    public function destroy(Unit $unit)
    {
        if ($unit->code === 'akademik') {
            return response()->json(['message' => 'Unit Akademik tidak dapat dihapus.'], 422);
        }

        if ($unit->surats()->exists()) {
            return response()->json(['message' => 'Unit masih memiliki data surat.'], 422);
        }

        $unit->delete();

        return response()->json(['message' => 'Unit berhasil dihapus.']);
    }

    // ============ MEMBER MANAGEMENT ============

    // ============ MOBILE - UNIT SELECTION ============

    public function myMembers(Request $request)
    {
        $units = $request->user()->unitMemberships()
            ->where('is_active', true)
            ->whereHas('unit', fn ($q) => $q->where('is_active', true))
            ->with('unit')
            ->get();

        return response()->json([
            'data' => $units->map(fn ($m) => [
                'member_id' => $m->id,
                'unit_id' => $m->unit_id,
                'unit_name' => $m->unit->name,
                'unit_code' => $m->unit->code,
                'role' => $m->role,
                'jabatan' => $m->jabatan,
            ]),
        ]);
    }

    public function selectMember(Request $request, UnitUser $member)
    {
        if ((int) $member->user_id !== (int) $request->user()->id || !$member->is_active) {
            return response()->json(['message' => 'Membership unit tidak valid.'], 403);
        }

        $member->load('unit');

        return response()->json([
            'message' => 'Unit berhasil dipilih.',
            'member' => [
                'member_id' => $member->id,
                'unit_id' => $member->unit_id,
                'unit_name' => $member->unit->name,
                'unit_code' => $member->unit->code,
                'role' => $member->role,
                'jabatan' => $member->jabatan,
            ],
        ]);
    }

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
            return response()->json(['message' => 'User sudah menjadi ' . ucfirst($v['role']) . ' di unit ini.'], 422);
        }

        $member = UnitUser::create([
            'unit_id' => $unit->id,
            'user_id' => $v['user_id'],
            'role' => $v['role'],
            'jabatan' => $v['jabatan'] ?? null,
            'is_active' => true,
        ]);

        $member->load('user');

        return response()->json([
            'message' => 'Anggota berhasil ditambahkan.',
            'member' => $member,
        ], 201);
    }

    public function toggleMember(UnitUser $member)
    {
        $member->update(['is_active' => !$member->is_active]);

        return response()->json([
            'message' => $member->is_active ? 'Anggota diaktifkan.' : 'Anggota dinonaktifkan.',
            'member' => $member->fresh('user'),
        ]);
    }

    public function updateMemberJabatan(Request $request, UnitUser $member)
    {
        $v = $request->validate([
            'jabatan' => 'nullable|string|max:100',
        ]);

        $member->update(['jabatan' => $v['jabatan'] ?: null]);

        return response()->json([
            'message' => 'Jabatan berhasil diperbarui.',
            'member' => $member->fresh('user'),
        ]);
    }

    public function removeMember(UnitUser $member)
    {
        $member->delete();

        return response()->json(['message' => 'Anggota berhasil dikeluarkan dari unit.']);
    }
}
