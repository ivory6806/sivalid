<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UnitRoleMiddleware
{
    /**
     * Cek role terhadap UNIT AKTIF (session), bukan kolom `role` lama di users.
     * Superadmin selalu lolos. Dipakai untuk route seperti /validasi yang harus
     * mengikuti peran user di unit yang sedang dipilih (bisa beda-beda per unit).
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            abort(401, 'Silakan login terlebih dahulu.');
        }

        if ($request->user()->isSuperadmin()) {
            return $next($request);
        }

        if (! in_array(session('active_unit_role'), $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini di unit yang sedang aktif.');
        }

        return $next($request);
    }
}
