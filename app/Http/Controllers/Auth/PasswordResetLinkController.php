<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
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
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Kirim email secara asynchronous menggunakan queue
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Langsung redirect tanpa menunggu email terkirim
        if ($status == Password::RESET_LINK_SENT) {
            return back()->with('status', 'Tautan reset password sedang dikirim ke email Anda. Mohon cek inbox dalam beberapa menit.');
        }

        return back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}