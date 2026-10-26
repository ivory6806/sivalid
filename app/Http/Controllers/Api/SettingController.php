<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json([
            'prodi' => Setting::getOptions('master_prodi'),
            'semester' => Setting::getOptions('master_semester'),
            'tahun_ajaran' => Setting::getOptions('master_tahun_ajaran'),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'key' => 'required|in:master_prodi,master_semester,master_tahun_ajaran',
            'value' => 'required|string|max:255',
        ]);

        try {
            Setting::addOption($request->key, $request->value);
            return response()->json(['message' => 'Berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function remove(Request $request)
    {
        $request->validate([
            'key' => 'required|in:master_prodi,master_semester,master_tahun_ajaran',
            'value' => 'required|string',
        ]);

        Setting::removeOption($request->key, $request->value);

        return response()->json(['message' => 'Berhasil dihapus.']);
    }
}
