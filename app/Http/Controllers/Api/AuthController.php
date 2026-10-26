<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UnitUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['Akun Anda belum aktif. Hubungi admin.'],
            ]);
        }

        $token = $user->createToken('mobile-token')->plainTextToken;

        $units = $user->unitMemberships()
            ->where('is_active', true)
            ->whereHas('unit', fn ($q) => $q->where('is_active', true))
            ->with('unit')
            ->get();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'nip' => $user->nip,
                'jabatan' => $user->jabatan,
                'is_superadmin' => $user->isSuperadmin(),
                'is_active' => $user->is_active,
            ],
            'units' => $units->map(fn ($m) => [
                'member_id' => $m->id,
                'unit_id' => $m->unit_id,
                'unit_name' => $m->unit->name,
                'unit_code' => $m->unit->code,
                'role' => $m->role,
                'jabatan' => $m->jabatan,
            ]),
            'token' => $token,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff',
            'is_active' => true,
        ]);

        $token = $user->createToken('mobile-token')->plainTextToken;

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'units' => [],
            'token' => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Berhasil logout.']);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        $units = $user->unitMemberships()
            ->where('is_active', true)
            ->whereHas('unit', fn ($q) => $q->where('is_active', true))
            ->with('unit')
            ->get();

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'nip' => $user->nip,
                'jabatan' => $user->jabatan,
                'is_superadmin' => $user->isSuperadmin(),
                'is_active' => $user->is_active,
            ],
            'units' => $units->map(fn ($m) => [
                'member_id' => $m->id,
                'unit_id' => $m->unit_id,
                'unit_name' => $m->unit->name,
                'unit_code' => $m->unit->code,
                'role' => $m->role,
                'jabatan' => $m->jabatan,
            ]),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'nip' => 'sometimes|nullable|string|max:20',
            'jabatan' => 'sometimes|nullable|string|max:255',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profil berhasil diperbarui.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'nip' => $user->nip,
                'jabatan' => $user->jabatan,
            ],
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password lama salah.'],
            ]);
        }

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Password berhasil diubah.']);
    }
}
