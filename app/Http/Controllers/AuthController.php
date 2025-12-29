<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Memproses autentikasi pengguna.
     *
     * - Melakukan validasi input email & password
     * - Melakukan login menggunakan Auth::attempt()
     * - Regenerasi session untuk keamanan
     * - Mencatat aktivitas login ke tabel activity_logs
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // =======================
        // Validasi Input Login
        // =======================
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // =======================
        // Proses Autentikasi
        // =======================
        if (Auth::attempt($credentials)) {
            // Regenerasi session (mencegah session fixation)
            $request->session()->regenerate();

            // =======================
            // Catat Aktivitas Login
            // =======================
            ActivityLog::record(
                'login',
                'Pengguna berhasil login ke sistem'
            );

            return redirect()->intended('/dashboard');
        }

        // =======================
        // Login Gagal
        // =======================
        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->onlyInput('email');
    }

    /**
     * Memproses logout pengguna.
     *
     * - Mencatat aktivitas logout
     * - Menghapus autentikasi user
     * - Menghancurkan session & token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // =======================
        // Catat Aktivitas Logout
        // =======================
        if (Auth::check()) {
            ActivityLog::record(
                'logout',
                'Pengguna keluar dari sistem'
            );
        }

        // =======================
        // Proses Logout
        // =======================
        Auth::logout();

        // Hancurkan session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
