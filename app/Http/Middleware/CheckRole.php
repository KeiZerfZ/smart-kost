<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Menghilangkan error P1013
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     * * Digunakan untuk validasi akses multi-role (Pemilik Kost & Penghuni).
     * Sesuai fitur wajib MVP proyek SmartKost.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  Parameter role (owner/tenant)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah pengguna sudah login
        // Menggunakan Facade Auth:: agar terbaca oleh Intelephense
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 2. Cek apakah role user sesuai dengan yang diminta route
        // Sesuai kriteria: Autentikasi multi-role (Pemilik Kost, Penghuni)
        if ($user->role !== $role) {
            // Jika tidak sesuai, lempar error 403 (Akses Ditolak)
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}