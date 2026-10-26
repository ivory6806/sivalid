<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('is_superadmin', false);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $v = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:staff,kepala',
            'nip' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
        ]);

        $v['password'] = Hash::make($v['password']);
        $v['is_active'] = true;

        $user = User::create($v);

        return response()->json([
            'message' => 'User berhasil ditambahkan.',
            'user' => $user->makeHidden(['password', 'remember_token']),
        ], 201);
    }

    public function show(User $user)
    {
        $user->load('unitMemberships.unit');
        return response()->json($user->makeHidden(['password', 'remember_token']));
    }

    public function update(Request $request, User $user)
    {
        $v = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255|unique:users,email,' . $user->id,
            'role' => 'sometimes|in:staff,kepala',
            'nip' => 'sometimes|nullable|string|max:20',
            'jabatan' => 'sometimes|nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->filled('password')) {
            $v['password'] = Hash::make($v['password']);
        } else {
            unset($v['password']);
        }

        $user->update($v);

        return response()->json([
            'message' => 'User berhasil diperbarui.',
            'user' => $user->makeHidden(['password', 'remember_token']),
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus.']);
    }
}
