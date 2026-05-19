<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails; // WAJIB DIIMPORT
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\TelegramService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    // Mengembalikan trait agar fungsi showLinkRequestForm() aktif kembali
    use SendsPasswordResetEmails; 

    /**
     * Mengoverride rute eksekusi pengiriman tautan dari Email ke Telegram Bot
     * Fungsi showLinkRequestForm() bawaan trait di atas akan tetap berjalan normal
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::with('tenant')->where('email', $request->email)->first();

        // Validasi ketersediaan Chat ID Telegram pada database akun tenant
        if ($user && $user->role === 'tenant' && $user->tenant && $user->tenant->telegram_chat_id) {
            $token = Str::random(60);

            // Simpan atau perbarui token ke tabel penampungan password_resets
            DB::table('password_resets')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => Hash::make($token), 
                    'created_at' => now()
                ]
            );

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