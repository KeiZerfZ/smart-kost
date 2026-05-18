<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramService
{
    public static function sendMessage($chatId, $message)
    {
        $token = env('TELEGRAM_BOT_TOKEN');
        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        try {
            // withoutVerifying() wajib buat pengguna Laragon/Windows biar gak error SSL
            $response = Http::withoutVerifying()->post($url, [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error("Telegram Error: " . $e->getMessage());
            return false;
        }
    }
}