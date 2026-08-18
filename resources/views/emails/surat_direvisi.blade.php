<!DOCTYPE html>
<html><head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#f4f6f9;font-family:Arial,Helvetica,sans-serif">
<div style="max-width:560px;margin:30px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08)">
    <div style="background:#dc2626;padding:28px 32px;text-align:center">
        <h1 style="margin:0;color:#fff;font-size:1.4rem;font-weight:800">SIVALID</h1>
        <p style="margin:4px 0 0;color:rgba(255,255,255,.8);font-size:.82rem">Sistem Informasi Validasi</p>
    </div>
    <div style="padding:28px 32px">
        <div style="text-align:center;margin-bottom:20px">
            <div style="width:56px;height:56px;border-radius:50%;background:#fef2f2;color:#dc2626;display:inline-flex;align-items:center;justify-content:center;font-size:1.6rem;margin-bottom:8px">&#8634;</div>
            <h2 style="margin:0;font-size:1.2rem;color:#dc2626;font-weight:800">Surat Perlu Direvisi</h2>
        </div>
        <p style="font-size:.92rem;color:#374151;margin:0 0 16px">Yth. <strong>{{ $surat->user->name ?? 'Staff' }}</strong>,</p>
        <p style="font-size:.92rem;color:#374151;margin:0 0 16px">Surat keterangan aktif Anda dikembalikan untuk direvisi. Berikut catatan dari Kepala BAA dan Beasiswa:</p>
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:14px 16px;margin:0 0 20px">
            <div style="font-size:.78rem;font-weight:700;color:#dc2626;margin-bottom:4px">CATATAN REVISI:</div>
            <div style="font-size:.9rem;color:#92400e">{{ $surat->catatan_revisi ?? 'Tidak ada catatan.' }}</div>
        </div>
        <table style="width:100%;border-collapse:collapse;margin:0 0 20px;font-size:.88rem">
            <tr style="background:#f9fafb"><td style="padding:10px 14px;border-bottom:1px solid #e5e7eb;color:#6b7280;width:140px">Nomor Surat</td><td style="padding:10px 14px;border-bottom:1px solid #e5e7eb;font-weight:600">{{ $surat->nomor_surat }}</td></tr>
            <tr><td style="padding:10px 14px;color:#6b7280">Nama Mahasiswa</td><td style="padding:10px 14px;font-weight:600">{{ $surat->nama_mahasiswa }}</td></tr>
        </table>
        <p style="font-size:.85rem;color:#6b7280;margin:0 0 20px">Silakan login ke SIVALID, perbaiki data, dan ajukan kembali surat tersebut.</p>
        <div style="text-align:center">
            <a href="{{ url('/surat/' . $surat->id) }}" style="display:inline-block;padding:12px 32px;background:#dc2626;color:#fff;border-radius:8px;text-decoration:none;font-weight:700;font-size:.9rem">Lihat Detail Surat &rarr;</a>
        </div>
    </div>
    <div style="padding:16px 32px;background:#f9fafb;text-align:center;font-size:.75rem;color:#9ca3af;border-top:1px solid #e5e7eb">SIVALID — Politeknik LPP Yogyakarta<br>Email ini dikirim secara otomatis, jangan membalas.</div>
</div>
</body></html>