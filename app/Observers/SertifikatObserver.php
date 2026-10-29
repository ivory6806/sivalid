<?php

namespace App\Observers;

use App\Models\Sertifikat;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Support\Str;

class SertifikatObserver
{
    public function created(Sertifikat $sertifikat): void
    {
        if (($sertifikat->status ?? 'diajukan') === 'diajukan') {
            $this->notifyValidators($sertifikat);
        }
    }

    public function updated(Sertifikat $sertifikat): void
    {
        if (!$sertifikat->wasChanged('status')) {
            return;
        }

        $status = $sertifikat->status;
        $previous = $sertifikat->getOriginal('status');

        if ($status === 'diajukan') {
            if ($previous === 'direvisi') {
                $this->notifyValidators($sertifikat);
            }
            return;
        }

        if (in_array($status, ['disetujui', 'direvisi'], true)) {
            $this->notifyOwner($sertifikat);
        }
    }

    private function notifyValidators(Sertifikat $sertifikat): void
    {
        // Validasi sertifikat bersifat global (tidak per unit): semua kepala + superadmin aktif.
        $userIds = User::where('is_active', true)
            ->where(function ($query) {
                $query->where('is_superadmin', true)->orWhere('role', 'kepala');
            })
            ->pluck('id');

        $uploader = $sertifikat->uploader?->name ?? 'Staff';
        $jenis = $sertifikat->jenis_sertifikat ?: 'Sertifikat';

        app(FcmService::class)->sendToUsers(
            $userIds,
            'Sertifikat Menunggu Validasi',
            Str::limit("{$jenis} dari {$uploader}", 140),
            ['type' => 'sertifikat', 'scope' => 'validasi', 'id' => (string) $sertifikat->id],
        );
    }

    private function notifyOwner(Sertifikat $sertifikat): void
    {
        if (!$sertifikat->uploaded_by) {
            return;
        }

        $approved = $sertifikat->status === 'disetujui';
        $judul = $sertifikat->nomor_sertifikat ?: ($sertifikat->jenis_sertifikat ?: 'Sertifikat');

        $body = $approved
            ? "{$judul} telah selesai dan siap diunduh."
            : Str::limit("{$judul} perlu direvisi. " . ($sertifikat->catatan_revisi ?? ''), 140);

        app(FcmService::class)->sendToUsers(
            [$sertifikat->uploaded_by],
            $approved ? 'Sertifikat Selesai' : 'Sertifikat Perlu Direvisi',
            $body,
            ['type' => 'sertifikat', 'scope' => 'status', 'id' => (string) $sertifikat->id],
        );
    }
}
