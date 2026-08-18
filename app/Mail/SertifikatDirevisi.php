<?php

namespace App\Mail;

use App\Models\Sertifikat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SertifikatDirevisi extends Mailable
{
    use Queueable, SerializesModels;

    public $sertifikat;

    public function __construct(Sertifikat $sertifikat)
    {
        $this->sertifikat = $sertifikat;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[SIAKTIF] Sertifikat Perlu Direvisi - ' . $this->sertifikat->nomor_sertifikat,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.sertifikat_direvisi');
    }
}
