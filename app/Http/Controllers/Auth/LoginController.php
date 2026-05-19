<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Intersepsi Proses Otentikasi Pasca Pengguna Berhasil Login
     */
    protected function authenticated(Request $request, $user)
    {
        // Blokir akses jika user adalah tenant dan statusnya masih pending
        if ($user->role === 'tenant' && $user->tenant && $user->tenant->status === 'pending') {
            $this->guard()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda masih dalam proses verifikasi berkas oleh Administrator.'
            ]);
        }

        // Pengalihan Rute Bersyarat Berdasarkan Peran Pengguna
        if ($user->role === 'owner') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('tenant.dashboard');
    }
}