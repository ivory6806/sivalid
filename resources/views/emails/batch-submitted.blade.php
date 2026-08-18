<!-- resources/views/emails/batch-submitted.blade.php -->
@component('mail::message')
# Pengajuan Batch Sertifikat Baru

**{{ $batch->creator->name }}** telah mengajukan batch sertifikat untuk divalidasi.

| Detail | Isi |
|---|---|
| **Nama Batch** | {{ $batch->batch_name }} |
| **Template** | {{ $batch->template->name }} |
| **Jumlah Sertifikat** | {{ $batch->total_count }} sertifikat |
| **Diajukan Pada** | {{ $batch->submitted_at->format('d F Y, H:i') }} |

@component('mail::button', ['url' => $reviewUrl])
Lihat & Validasi Batch
@endcomponent

Terima kasih.
@endcomponent