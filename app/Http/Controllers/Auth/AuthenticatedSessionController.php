<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('pages.auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Buat sanctum token
        $token = Auth::user()->createToken('web-token')->plainTextToken;
        // Simpan token ke session (atau return ke frontend kalau pakai SPA)
        session(['api_token' => $token]);

        if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('super_admin')) {
            return redirect()->intended(route('admin.dashboard'))->with('api_token', $token);
        }

        return redirect()->intended(route('home'))->with('api_token', $token);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
