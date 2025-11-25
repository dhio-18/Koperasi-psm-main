<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Resend\Laravel\Facades\Resend;

class PasswordResetLinkController extends Controller
{
    /**
     * Display forgot-password page.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle email reset request using Resend API.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Cek apakah user terdaftar
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        // Generate token PLAIN TEXT (penting!)
        $plainToken = Str::random(64);

        // Simpan hashed token di DB (meniru Laravel)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Hash::make($plainToken),
                'created_at' => Carbon::now(),
            ]
        );

        // URL ke halaman reset password
        $resetUrl = url(route('password.reset', [
            'token' => $plainToken,
            'email' => $user->email,
        ], false));

        // Kirim email pakai Resend API
        Resend::emails()->send([
            'from' => 'no-reply@koperasipsm.web.id',
            'to' => $user->email,
            'subject' => 'Reset Password Koperasi PSM',
            'html' => view('emails.reset-password', [
                'name' => $user->name,
                'url' => $resetUrl,
            ])->render(),
        ]);

        return back()->with('status', 'Link reset password berhasil dikirim ke email Anda.');
    }
}
