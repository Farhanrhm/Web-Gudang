<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Menangani request masuk dan memastikan hanya user dengan role ADMIN
     * yang dapat mengakses route tertentu.
     *
     * Alur kerja:
     * 1. Mengecek apakah user sudah login
     * 2. Mengecek apakah role user adalah "admin"
     * 3. Jika memenuhi, request diteruskan
     * 4. Jika tidak, akses ditolak (HTTP 403)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // =======================
        // Cek autentikasi & role
        // =======================
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // =======================
        // Akses ditolak
        // =======================
        abort(403, 'AKSES DITOLAK: Halaman ini khusus Admin.');
    }
}
