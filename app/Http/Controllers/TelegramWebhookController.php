<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Invoice;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Log;

class TelegramWebhookController extends Controller
{
    /**
     * Handle incoming Webhook from Telegram
     */
    public function __invoke(Request $request)
    {
        $webhookData = $request->all();

        // Validasi struktur payload dari Telegram
        if (!isset($webhookData['message']['chat']['id']) || !isset($webhookData['message']['text'])) {
            return response()->json(['status' => 'ignored']);
        }

        $chatId = $webhookData['message']['chat']['id'];
        $text = trim($webhookData['message']['text']);

        // Kita hanya respon perintah /start
        if (str_starts_with($text, '/start')) {
            
            // Cari tenant berdasarkan telegram_chat_id
            $tenant = Tenant::with('user', 'room')->where('telegram_chat_id', $chatId)->first();

            if (!$tenant) {
                $reply = "❌ *AKSES DITOLAK*\n\nID Telegram Anda (`{$chatId}`) belum terdaftar di sistem SmartKost. Silakan hubungi Pemilik Kost untuk melakukan pendaftaran.";
                TelegramService::sendMessage($chatId, $reply);
                return response()->json(['status' => 'success']);
            }

            // Jika tenant ditemukan, cek apakah ada tagihan aktif yang belum dibayar (unpaid)
            $unpaidInvoice = Invoice::where('tenant_id', $tenant->id)
                                    ->where('status', 'unpaid')
                                    ->orderBy('due_date', 'asc')
                                    ->first();

            $reply = "👋 *Halo, {$tenant->user->name}!*\n";
            $reply .= "Selamat datang di Bot Notifikasi *SmartKost*.\n";
            $reply .= "Kamar Anda: *Kamar {$tenant->room->room_number}*\n\n";

            if ($unpaidInvoice) {
                $amount = number_format($unpaidInvoice->amount, 0, ',', '.');
                $dueDate = \Carbon\Carbon::parse($unpaidInvoice->due_date)->format('d F Y');

                $reply .= "⚠️ *INFORMASI TAGIHAN AKTIF:*\n";
                $reply .= "• Periode: *{$unpaidInvoice->created_at->format('F Y')}*\n";
                $reply .= "• Total: *Rp {$amount}*\n";
                $reply .= "• Jatuh Tempo: *{$dueDate}*\n\n";
                $reply .= "Silakan lakukan pembayaran melalui aplikasi web SmartKost sebelum tanggal jatuh tempo. Terima kasih! 🙏";
            } else {
                $reply .= "✅ *STATUS KEUANGAN AMAN*\n";
                $reply .= "Saat ini Anda tidak memiliki tagihan aktif yang menunggak. Terima kasih telah menjadi penghuni yang tertib! ✨";
            }

            TelegramService::sendMessage($chatId, $reply);
        }

        return response()->json(['status' => 'success']);
    }
}