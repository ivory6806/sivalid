<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                        ->withErrors(['email' => __($status)]);
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // 1. Cek apakah user ada DAN statusnya aktif
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user || !$user->is_active) {
            // 2. Kalau tidak ada atau non-aktif, pura-pura berhasil (Best practice keamanan)
            return back()->with('status', 'Jika email terdaftar pada akun aktif, tautan reset telah dikirim.');
        }

        // 3. Kalau user aktif, lanjutkan proses kirim email seperti biasa
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == \Illuminate\Auth\Passwords\Password::RESET_LINK_SENT
            ? back()->with('status', __($response))
            : back()->withErrors(['email' => __($response)]);
    }
}
