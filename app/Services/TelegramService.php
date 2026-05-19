<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\TelegramDeliveryLog;

class TelegramService
{
    /**
     * Kirim Pesan dengan Dukungan Tombol Interaktif dan Logging Audit
     */
    public static function sendMessage($chatId, $message, $inlineKeyboard = null)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        $payload = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown',
        ];

        // Jika terdapat parameter tombol interaktif, konversi ke format JSON JSONMarkup
        if ($inlineKeyboard) {
            $payload['reply_markup'] = json_encode([
                'inline_keyboard' => $inlineKeyboard
            ]);
        }

        try {
            $response = Http::withoutVerifying()->post($url, $payload);

            // Pencatatan Log Audit Otomatis ke Database
            TelegramDeliveryLog::create([
                'chat_id' => $chatId,
                'message' => $message,
                'status_code' => $response->status(),
                'is_success' => $response->successful(),
                'response_payload' => json_encode($response->json()),
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Telegram Logging Error: " . $e->getMessage());
            
            TelegramDeliveryLog::create([
                'chat_id' => $chatId,
                'message' => $message,
                'status_code' => 500,
                'is_success' => false,
                'response_payload' => json_encode(['error' => $e->getMessage()]),
            ]);

            return false;
        }
    }
}