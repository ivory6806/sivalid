<?php

namespace App\Mail;

use App\Models\SertifikatBatch;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BatchProcessedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SertifikatBatch $batch) {}

    public function envelope(): array
    {
        $label = $this->batch->status === 'completed' ? 'Disetujui & Selesai' : 'Ditolak';
        return [
            'subject' => "[Sertifikat] Batch {$label}: {$this->batch->batch_name}",
        ];
    }

    public function content()
    {
        return new \Illuminate\Mail\MarkdownMessage('emails.batch-processed', [
            'batch'    => $this->batch,
            'detailUrl' => route('staff.batches.show', $this->batch),
        ]);
    }

    public function attachments(): array { return []; }
}