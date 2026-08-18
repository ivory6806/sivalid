<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUnitSelected
{
    /**
     * Superadmin tidak perlu unit aktif. User lain (kepala/staff) wajib
     * sudah memilih unit (session active_unit_id) sebelum mengakses
     * halaman yang di-scope per unit (dashboard, surat, validasi, dst).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->isSuperadmin()) {
            return $next($request);
        }

        if (! session('active_unit_id')) {
            return redirect()->route('units.select');
        }

        return $next($request);
    }
}
