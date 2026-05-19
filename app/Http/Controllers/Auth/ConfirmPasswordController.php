<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class ConfirmPasswordController extends Controller
{
    use ConfirmsPasswords;

    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function redirectTo()
    {
        return auth()->user()->role === 'owner' ? route('admin.dashboard') : route('tenant.dashboard');
    }
}