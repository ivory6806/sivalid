<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'superadmin')->latest()->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.form');
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
        $v['is_active'] = true; // User baru otomatis aktif

        User::create($v);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $v = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:staff,kepala',
            'nip' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:255',
            // 'is_active' DIHAPUS DARI SINI
        ]);

        // Tangkap checkbox secara manual: true jika dicentang, false jika tidak dicentang
        $v['is_active'] = $request->boolean('is_active');

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $v['password'] = Hash::make($request->password);
        }

        $user->update($v);
        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}