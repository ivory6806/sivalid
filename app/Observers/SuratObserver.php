<?php

namespace App\Observers;

use App\Models\Surat;
use App\Models\UnitUser;
use App\Services\FcmService;
use Illuminate\Support\Str;

class SuratObserver
{
    public function created(Surat $surat): void
    {
        // Status default 'diajukan' baru ada di DB, belum terisi di model setelah insert.
        if (($surat->status ?? 'diajukan') === 'diajukan') {
            $this->notifyValidators($surat);
        }
    }

    public function updated(Surat $surat): void
    {
        if (!$surat->wasChanged('status')) {
            return;
        }

        $status = $surat->status;
        $previous = $surat->getOriginal('status');

        if ($status === 'diajukan') {
            // Dikirim ulang setelah revisi; pembatalan validasi tidak perlu notifikasi.
            if ($previous === 'direvisi') {
                $this->notifyValidators($surat);
            }
            return;
        }

        if (in_array($status, ['disetujui', 'direvisi'], true)) {
            $this->notifyOwner($surat);
        }
    }

    private function notifyValidators(Surat $surat): void
    {
        $userIds = UnitUser::where('unit_id', $surat->unit_id)
            ->where('role', 'kepala')
            ->where('is_active', true)
            ->pluck('user_id');

        $pengaju = $surat->user?->name ?? 'Staff';
        $judul = $surat->nama_surat ?: 'Surat';

        app(FcmService::class)->sendToUsers(
            $userIds,
            'Surat Baru Menunggu Validasi',
            Str::limit("{$judul} dari {$pengaju}", 140),
            ['type' => 'surat', 'scope' => 'validasi', 'id' => (string) $surat->id],
        );
    }

    private function notifyOwner(Surat $surat): void
    {
        if (!$surat->user_id) {
            return;
        }

        $approved = $surat->status === 'disetujui';
        $judul = $surat->nomor_surat ?: ($surat->nama_surat ?: 'Surat');

        $body = $approved
            ? "{$judul} telah disetujui."
            : Str::limit("{$judul} perlu direvisi. " . ($surat->catatan_revisi ?? ''), 140);

        app(FcmService::class)->sendToUsers(
            [$surat->user_id],
            $approved ? 'Surat Disetujui' : 'Surat Perlu Direvisi',
            $body,
            ['type' => 'surat', 'scope' => 'status', 'id' => (string) $surat->id],
        );
    }
}
