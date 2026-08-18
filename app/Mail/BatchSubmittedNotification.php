<?php

namespace App\Mail;

use App\Models\SertifikatBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BatchSubmittedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SertifikatBatch $batch) {}

    public function envelope(): array
    {
        return [
            'subject' => "[Sertifikat] Pengajuan Batch: {$this->batch->batch_name}",
        ];
    }

    public function content()
    {
        return new \Illuminate\Mail\MarkdownMessage('emails.batch-submitted', [
            'batch'     => $this->batch,
            'reviewUrl' => route('kepala.batches.show', $this->batch),
        ]);
    }

    public function attachments(): array { return []; }
}