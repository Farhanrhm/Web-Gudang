<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // CEK: Apakah dia Admin?
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request); // Silakan masuk bos!
        }

        // Jika Karyawan coba-coba masuk, tolak!
        abort(403, 'AKSES DITOLAK: Halaman ini khusus Admin.');
    }
}