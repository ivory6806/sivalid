<?php

namespace App\Mail;

use App\Models\Surat;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuratDiajukan extends Mailable
{
    use Queueable, SerializesModels;

    public $surat;

    public function __construct(Surat $surat)
    {
        $this->surat = $surat;
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