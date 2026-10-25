<?php

namespace App\Mail;

use App\Models\Surat;
use App\Models\User;
use App\Models\UnitUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuratDiajukan extends Mailable
{
    use Queueable, SerializesModels;

    public $surat;
    public $kepala;
    public $kepalaJabatan;

    public function __construct(Surat $surat, ?User $kepala = null)
    {
        $this->surat = $surat;
        $this->kepala = $kepala;

        // Jabatan kepala ini SPESIFIK untuk unit surat ini (karena 1 orang
        // bisa punya jabatan berbeda-beda di tiap unit).
        $this->kepalaJabatan = 'Kepala';
        if ($kepala && $surat->unit_id) {
            $unitUser = UnitUser::where('user_id', $kepala->id)
                ->where('unit_id', $surat->unit_id)
                ->where('role', 'kepala')
                ->first();
            if ($unitUser) {
                $this->kepalaJabatan = $unitUser->display_label;
            }
        }
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[SIVALID] Surat Baru Diajukan - ' . $this->surat->nomor_surat,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.surat_diajukan');
    }
}