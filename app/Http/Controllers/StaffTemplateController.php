<?php

namespace App\Http\Controllers;

use App\Models\SertifikatTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StaffTemplateController extends Controller
{
    public function index()
    {
        $templates = SertifikatTemplate::orderBy('updated_at', 'desc')->paginate(12);
        return view('sertifikat.staff.templates.index', compact('templates'));
    }

    public function create()
    {
        return view('sertifikat.staff.templates.upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'background' => 'required|image|mimes:png,jpg,jpeg|max:5120',
        ]);

        $file = $request->file('background');
        $path = $file->store('templates', 'public');
        $fullPath = storage_path('app/public/' . $path);
        $size = getimagesize($fullPath);

        SertifikatTemplate::create([
            'name'           => $request->name,
            'background_path' => $path,
            'width'          => $size[0],
            'height'         => $size[1],
            'elements'       => [],
            'qr_position'    => null,
            'nomor_format'   => 'SRT/{year}/{month}/{seq}',
        ]);

        return redirect()->route('staff.templates.index')->with('success', 'Template berhasil diupload.');
    }

    public function editor(SertifikatTemplate $template)
    {
        return view('sertifikat.staff.templates.editor', compact('template'));
    }

    public function update(Request $request, SertifikatTemplate $template)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'nomor_format' => 'nullable|string|max:255',
            'elements'     => 'nullable|array',
            'qr_position'  => 'nullable|array',
        ]);

        $template->update([
            'name'         => $request->name,
            'nomor_format' => $request->nomor_format,
            'elements'     => $request->elements ?? [],
            'qr_position'  => $request->qr_position,
        ]);

        return response()->json(['message' => 'Template berhasil disimpan.']);
    }

    public function destroy(SertifikatTemplate $template)
    {
        if ($template->background_path) {
            $fullPath = storage_path('app/public/' . $template->background_path);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
        $template->delete();
        return redirect()->route('staff.templates.index')->with('success', 'Template berhasil dihapus.');
    }
}