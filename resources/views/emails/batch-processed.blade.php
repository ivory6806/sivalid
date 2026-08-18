<!-- resources/views/emails/batch-processed.blade.php -->
@component('mail::message')
@if($batch->status === 'completed')
# Batch Sertifikat Disetujui

Batch **{{ $batch->batch_name }}** telah divalidasi dan sertifikat final (dengan QR Code) telah digenerate.

| Detail | Isi |
|---|---|
| **Jumlah Sertifikat** | {{ $batch->success_count }} berhasil |
| **Divalidasi Oleh** | {{ $batch->validator->name }} |
| **Waktu Validasi** | {{ $batch->validated_at->format('d F Y, H:i') }} |

@component('mail::button', ['url' => $detailUrl])
Lihat & Unduh Batch
@endcomponent

@else
# Batch Sertifikat Ditolak

Batch **"{{ $batch->batch_name }}"** telah ditolak.

**Alasan Penolakan:**
> {{ $batch->rejection_reason ?? 'Tidak ada keterangan.' }}

@component('mail::button', ['url' => $detailUrl])
Lihat Detail Batch
@endcomponent
@endif

Terima kasih.
@endcomponent