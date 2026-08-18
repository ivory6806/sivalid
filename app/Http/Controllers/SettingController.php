<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        return view('setting.index', [
            'prodiList'    => Setting::getOptions('master_prodi'),
            'semesterList' => Setting::getOptions('master_semester'),
            'taList'       => Setting::getOptions('master_tahun_ajaran'),
        ]);
    }

    public function add(Request $request)
    {
        try {
            $request->validate([
                'key'   => 'required|in:master_prodi,master_semester,master_tahun_ajaran',
                'value' => 'required|string|max:255',
            ]);

            Setting::addOption($request->key, $request->value);

            return response()->json(['success' => true, 'message' => 'Berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function remove(Request $request)
    {
        $request->validate([
            'key'   => 'required|in:master_prodi,master_semester,master_tahun_ajaran',
            'value' => 'required|string',
        ]);

        Setting::removeOption($request->key, $request->value);

        return response()->json(['success' => true, 'message' => 'Berhasil dihapus.']);
    }
}