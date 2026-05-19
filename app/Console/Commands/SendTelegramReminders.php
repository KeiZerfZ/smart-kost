<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Services\TelegramService;
use Carbon\Carbon;

class SendTelegramReminders extends Command
{
    protected $signature = 'smartkost:send-reminders';
    protected $description = 'Melakukan pemindaian otomatis dan mengirimkan notifikasi penagihan berkala H-3, H-1, dan Hari-H';

    public function handle()
    {
        $unpaidInvoices = Invoice::with('tenant.user')
            ->where('status', 'unpaid')
            ->get();

        foreach ($unpaidInvoices as $invoice) {
            $tenant = $invoice->tenant;
            if (!$tenant || !$tenant->telegram_chat_id) continue;

            $dueDate = Carbon::parse($invoice->due_date)->startOfDay();
            $today = Carbon::now()->startOfDay();
            
            // Kalkulasi selisih hari secara presisi
            $daysRemaining = $today->diffInDays($dueDate, false);

            $message = "";
            $label = "";

            if ($daysRemaining === 3) {
                $label = "PENGINGAT WAKTU";
                $message = "⏳ *PEMBERITAHUAN: JATUH TEMPO H-3*\n\nHalo *{$tenant->user->name}*,\nTagihan sewa kamar Anda untuk periode *{$invoice->bill_date->format('F Y')}* sebesar *Rp " . number_format($invoice->amount, 0, ',', '.') . "* akan jatuh tempo dalam kurun waktu 3 hari kedepan (Tanggal " . $dueDate->format('d/m/Y') . ").";
            } elseif ($daysRemaining === 1) {
                $label = "PERINGATAN JATUH TEMPO";
                $message = "⚠️ *PERINGATAN: JATUH TEMPO H-1*\n\nHalo *{$tenant->user->name}*,\nTagihan sewa kamar Anda untuk periode *{$invoice->bill_date->format('F Y')}* akan jatuh tempo *BESOK*. Mohon segera lakukan penyelesaian pembayaran.";
            } elseif ($daysRemaining <= 0) {
                $label = "PANGGILAN KETERLAMBATAN";
                $message = "🚨 *PERINGATAN KRUSIAL: MASA TENGGAT TELAH LEWAT*\n\nHalo *{$tenant->user->name}*,\nTagihan sewa kamar Anda untuk periode *{$invoice->bill_date->format('F Y')}* dinyatakan *TELAH MELEWATI BATAS WAKTU* pembayaran. Mohon segera melunasi tagihan Anda.";
            }

            if ($message) {
                $inlineKeyboard = [
                    [
                        ['text' => '💳 Bayar Sekarang', 'url' => config('app.url')]
                    ]
                ];
                
                TelegramService::sendMessage($tenant->telegram_chat_id, $message, $inlineKeyboard);
                $this->info("[{$label}] Notifikasi berhasil dikirim ke Chat ID: {$tenant->telegram_chat_id}");
            }
        }
    }
}