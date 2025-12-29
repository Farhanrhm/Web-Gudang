<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            // Regenerasi session (security)
            $request->session()->regenerate();

            // 🔹 Catat aktivitas login
            ActivityLog::record(
                'login',
                'Pengguna melakukan login ke sistem'
            );

            return redirect()->intended('/dashboard');
        }

        // Jika gagal login
        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->onlyInput('email');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        // 🔹 Catat aktivitas logout (pastikan user masih login)
        if (Auth::check()) {
            ActivityLog::record(
                'logout',
                'Pengguna keluar dari sistem'
            );
        }

        Auth::logout();

        // Hancurkan session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
