<?php

namespace App\Http\Controllers;

use App\Models\SertifikatBatch;
use App\Models\SertifikatTemplate;
use App\Models\Sertifikat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode; // <-- TAMBAHKAN INI

class StaffBatchController extends Controller
{
    public function index(Request $request)
    {
        $batches = SertifikatBatch::with('template')
            ->when($request->filled('search'), fn($q) => $q->where('kegiatan', 'like', '%' . $request->search . '%'))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('sertifikat.staff.batches.index', compact('batches'));
    }

    public function create()
    {
        $templates = SertifikatTemplate::orderBy('name')->get();
        return view('sertifikat.staff.batches.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kegiatan'        => 'required|string|max:500',
            'penyelenggara'   => 'required|string|max:255',
            'tempat'          => 'nullable|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'judul'           => 'nullable|string|max:500',
            'template_id'     => 'required|exists:sertifikat_templates,id',
            'peserta_csv'     => 'required|file|mimes:csv,txt|max:10240',
        ]);

        return DB::transaction(function () use ($request) {
            $status = $request->action === 'submit' ? 'submitted' : 'draft';

            $batch = SertifikatBatch::create([
                'kegiatan'        => $request->kegiatan,
                'penyelenggara'   => $request->penyelenggara,
                'tempat'          => $request->tempat,
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'judul'           => $request->judul,
                'template_id'     => $request->template_id,
                'user_id'         => auth()->id(),
                'status'          => $status,
            ]);

            $csv = $request->file('peserta_csv');
            $handle = fopen($csv->getPathname(), 'r');
            $headers = array_map('strtolower', array_map('trim', fgetcsv($handle)));

            $seq = 1;
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($headers, array_map('trim', $row));
                if (empty($data['nama'])) continue;

                Sertifikat::create([
                    'batch_id'   => $batch->id,
                    'nama'       => $data['nama'],
                    'nip'        => $data['nip'] ?? null,
                    'jabatan'    => $data['jabatan'] ?? null,
                    'nomor'      => $this->generateNomor($batch, $seq++),
                    'data_extra' => array_diff_key($data, array_flip(['nama', 'nip', 'jabatan'])),
                ]);
            }
            fclose($handle);

            $msg = $status === 'submitted'
                ? 'Batch berhasil diajukan untuk validasi.'
                : 'Batch berhasil disimpan sebagai draft.';

            return redirect()->route('staff.batches.show', $batch)->with('success', $msg);
        });
    }

    public function show(SertifikatBatch $batch)
    {
        $sertifikats = $batch->sertifikats()->orderBy('nama')->paginate(20);
        return view('sertifikat.staff.batches.show', compact('batch', 'sertifikats'));
    }

    public function edit(SertifikatBatch $batch)
    {
        if (!in_array($batch->status, ['draft', 'rejected'])) abort(403);
        $templates = SertifikatTemplate::orderBy('name')->get();
        return view('sertifikat.staff.batches.edit', compact('batch', 'templates'));
    }

    public function update(Request $request, SertifikatBatch $batch)
    {
        if (!in_array($batch->status, ['draft', 'rejected'])) abort(403);

        $request->validate([
            'kegiatan'        => 'required|string|max:500',
            'penyelenggara'   => 'required|string|max:255',
            'tempat'          => 'nullable|string|max:255',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'judul'           => 'nullable|string|max:500',
            'template_id'     => 'required|exists:sertifikat_templates,id',
            'peserta_csv'     => 'nullable|file|mimes:csv,txt|max:10240',
        ]);

        return DB::transaction(function () use ($request, $batch) {
            $status = $request->action === 'submit' ? 'submitted' : 'draft';

            $batch->update([
                'kegiatan'        => $request->kegiatan,
                'penyelenggara'   => $request->penyelenggara,
                'tempat'          => $request->tempat,
                'tanggal_mulai'   => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'judul'           => $request->judul,
                'template_id'     => $request->template_id,
                'status'          => $status,
                'catatan'         => null,
            ]);

            if ($request->hasFile('peserta_csv')) {
                $batch->sertifikats()->delete();
                $csv = $request->file('peserta_csv');
                $handle = fopen($csv->getPathname(), 'r');
                $headers = array_map('strtolower', array_map('trim', fgetcsv($handle)));

                $seq = 1;
                while (($row = fgetcsv($handle)) !== false) {
                    $data = array_combine($headers, array_map('trim', $row));
                    if (empty($data['nama'])) continue;

                    Sertifikat::create([
                        'batch_id'   => $batch->id,
                        'nama'       => $data['nama'],
                        'nip'        => $data['nip'] ?? null,
                        'jabatan'    => $data['jabatan'] ?? null,
                        'nomor'      => $this->generateNomor($batch, $seq++),
                        'data_extra' => array_diff_key($data, array_flip(['nama', 'nip', 'jabatan'])),
                    ]);
                }
                fclose($handle);
            }

            $msg = $status === 'submitted' ? 'Batch berhasil diperbarui dan diajukan.' : 'Batch berhasil diperbarui.';
            return redirect()->route('staff.batches.show', $batch)->with('success', $msg);
        });
    }

    public function submit(SertifikatBatch $batch)
    {
        if ($batch->status !== 'draft') abort(403);
        $batch->update(['status' => 'submitted']);
        return redirect()->route('staff.batches.show', $batch)->with('success', 'Batch berhasil diajukan.');
    }

    public function destroy(SertifikatBatch $batch)
    {
        // Hapus data terkait (sertifikat peserta akan ikut terhapus karena cascadeOnDelete)
        $batch->delete();

        return redirect()->route('staff.batches.index')->with('success', 'Batch berhasil dihapus.');
    }

    public function csvExample()
    {
        $csv = "nama,nip,jabatan\nAhmad Fauzi,198501012010011001,Kepala Seksi\nSiti Nurhaliza,199001012015022001,Staff\nBudi Santoso,198703152011011003,Analis Kebijakan\n";
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="contoh_peserta.csv"',
        ]);
    }

    public function preview(SertifikatBatch $batch, Sertifikat $sertifikat)
    {
        if ($batch->status !== 'approved') abort(403);
        return $this->renderPdf($batch, $sertifikat, true);
    }

    public function download(SertifikatBatch $batch, Sertifikat $sertifikat)
    {
        if ($batch->status !== 'approved') abort(403);
        return $this->renderPdf($batch, $sertifikat, false);
    }

    public function downloadAll(SertifikatBatch $batch)
    {
        if ($batch->status !== 'approved') abort(403);

        $zip = new \ZipArchive;
        $filename = storage_path('app/public/zips/' . Str::slug($batch->kegiatan) . '.zip');
        if (!file_exists(dirname($filename))) mkdir(dirname($filename), 0755, true);

        if ($zip->open($filename, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($batch->sertifikats as $s) {
                $pdf = $this->renderPdf($batch, $s, false);
                $zip->addFromString(str_replace(['/', '\\'], '-', $s->nama) . '.pdf', $pdf->output());
            }
            $zip->close();
        }
        return response()->download($filename)->deleteFileAfterSend(true);
    }

    private function generateNomor(SertifikatBatch $batch, int $seq)
    {
        $format = $batch->template?->nomor_format ?? 'SRT/{year}/{month}/{seq}';
        $t = $batch->tanggal_mulai;
        return str_replace(
            ['{year}', '{month}', '{seq}', '{batch}'],
            [$t->format('Y'), $t->format('m'), str_pad($seq, 4, '0', STR_PAD_LEFT), $batch->id],
            $format
        );
    }

    private function renderPdf(SertifikatBatch $batch, Sertifikat $sertifikat, bool $stream)
    {
        $tpl = $batch->template;
        $bgPath = storage_path('app/public/' . $tpl->background_path);
        $bgBase64 = 'data:image/' . pathinfo($bgPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($bgPath));

        $replacements = [
            '{{nama}}'         => $sertifikat->nama,
            '{{nomor}}'        => $sertifikat->nomor,
            '{{tanggal}}'      => $batch->tanggal_mulai->translatedFormat('d F Y'),
            '{{kegiatan}}'     => $batch->kegiatan,
            '{{penyelenggara}}' => $batch->penyelenggara,
            '{{tempat}}'       => $batch->tempat ?? '-',
            '{{judul}}'        => $batch->judul ?? '-',
        ];

        $elementsHtml = '';
        foreach ($tpl->elements ?? [] as $el) {
            if ($el['type'] === 'text') {
                $text = str_replace(array_keys($replacements), array_values($replacements), $el['tag'] ?? $el['content'] ?? '');
                $elementsHtml .= '<div style="position:absolute;left:' . $el['x'] . 'px;top:' . $el['y'] . 'px;font-size:' . $el['fontSize'] . 'px;color:' . $el['color'] . ';font-family:' . ($el['fontFamily'] ?? 'serif') . ';font-weight:' . ($el['fontWeight'] ?? 'normal') . ';font-style:' . ($el['fontStyle'] ?? 'normal') . ';transform:rotate(' . ($el['rotation'] ?? 0) . 'deg);white-space:nowrap">' . e($text) . '</div>';
            } elseif ($el['type'] === 'line') {
                $elementsHtml .= '<div style="position:absolute;left:' . $el['x'] . 'px;top:' . $el['y'] . 'px;width:200px;height:' . ($el['fontSize'] ?? 2) . 'px;background:' . ($el['color'] ?? '#000') . ';transform:rotate(' . ($el['rotation'] ?? 0) . 'deg)"></div>';
            }
        }

        // ==============================================
        // LOGIKA QR CODE OTOMATIS DARI KOORDINAT KEPALA
        // ==============================================
        if ($batch->qr_position && !empty($batch->qr_position['x']) && !empty($batch->qr_position['y'])) {
            $qrSize = $batch->qr_position['size'] ?? 80;
            
            // Generate QR Code ke dalam format Base64 PNG agar bisa dibaca DomPDF
            $qrImage = base64_encode(QrCode::format('png')->size($qrSize * 4)->generate(route('verify.show', $sertifikat->nomor)));
            $qrBase64 = 'data:image/png;base64,' . $qrImage;

            // Tempelkan ke HTML sertifikat berdasarkan koordinat X, Y, dan Size
            $elementsHtml .= '<img src="' . $qrBase64 . '" style="position:absolute;left:' . $batch->qr_position['x'] . 'px;top:' . $batch->qr_position['y'] . 'px;width:' . $qrSize . 'px;height:' . $qrSize . 'px;">';
        }

        $html = '<!DOCTYPE html><html><head><meta charset="utf-8"><style>*{margin:0;padding:0}body{margin:0}.c{position:relative;width:' . $tpl->width . 'px;height:' . $tpl->height . 'px;overflow:hidden}.c img{position:absolute;top:0;left:0;width:100%;height:100%}</style></head><body><div class="c"><img src="' . $bgBase64 . '">' . $elementsHtml . '</div></body></html>';

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper([0, 0, $tpl->width * 0.75, $tpl->height * 0.75], 'points');
        $pdf->loadHTML($html);
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $stream ? $pdf->stream($sertifikat->nama . '.pdf') : $pdf;
    }

    
}