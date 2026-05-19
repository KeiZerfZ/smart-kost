<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Password; // Wajib diimport

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | Controller ini bertanggung jawab untuk menangani permintaan atur ulang
    | kata sandi. Trait SendsPasswordResetEmails digunakan untuk menampilkan
    | halaman formulir secara otomatis bawaan framework.
    |
    */

    use SendsPasswordResetEmails; 

    /**
     * Mengoverride rute eksekusi pengiriman tautan menggunakan Password Broker Laravel
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::with('tenant')->where('email', $request->email)->first();

        // Validasi peran dan ketersediaan Chat ID Telegram
        if ($user && $user->role === 'tenant' && $user->tenant && $user->tenant->telegram_chat_id) {
            
            // Menggunakan Broker internal Laravel untuk membuat token secara aman dan standar
            $token = Password::broker()->createToken($user);

            $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

            $message = "🔒 *PERMINTAAN ATUR ULANG KATA SANDI*\n\nHalo *{$user->name}*,\nKami menerima permintaan penyesuaian kata sandi untuk akun SmartKost Anda.\n\nSilakan klik tombol interaktif di bawah ini untuk melanjutkan konfigurasi kata sandi baru.";

            $inlineKeyboard = [
                [
                    ['text' => '🔑 Atur Ulang Sandi', 'url' => $resetUrl]
                ]
            ];

            TelegramService::sendMessage($user->tenant->telegram_chat_id, $message, $inlineKeyboard);

            return redirect()->back()->with('status', 'Tautan pengaturan ulang kata sandi telah dikirimkan ke akun Telegram Anda.');
        }

        return redirect()->back()->withErrors([
            'email' => 'Alamat email tidak terdaftar atau instrumen Telegram Chat ID belum dikonfigurasi.'
        ]);
    }
}