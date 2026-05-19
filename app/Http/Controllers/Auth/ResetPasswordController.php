<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Menentukan Rute Pengalihan Secara Dinamis Berdasarkan Tingkat Hak Akses Peran
     */
    protected function redirectTo()
    {
        if (auth()->user()->role === 'owner') {
            return route('admin.dashboard');
        }

        return route('tenant.dashboard');
    }
}