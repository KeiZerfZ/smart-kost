<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\WaLog;
use App\Services\FonnteService;
use Carbon\Carbon;

class SendWaReminders extends Command
{
    protected $signature = 'send:wa-reminders';
    protected $description = 'Kirim pengingat tagihan H-3 via WhatsApp';

    public function handle()
    {
        // Cari tagihan UNPAID yang bill_date-nya sudah lewat 27 hari (H-3 dari siklus 30 hari)
        // Atau sesuaikan dengan logika jatuh tempo lu
        $targetDate = Carbon::now()->subDays(27)->toDateString();

        $invoices = Invoice::with('tenant.user')
                    ->where('status', 'unpaid')
                    ->whereDate('bill_date', $targetDate)
                    ->get();

        foreach ($invoices as $inv) {
            $msg = "Halo *{$inv->tenant->user->name}*,\n\nIni pengingat otomatis dari *SmartKost*. Tagihan bulan {$inv->bill_date->format('F')} sebesar *Rp " . number_format($inv->amount, 0, ',', '.') . "* akan jatuh tempo dalam 3 hari. Mohon segera selesaikan pembayaran ya. Matur suwun!";

            $result = FonnteService::send($inv->tenant->phone, $msg);

            WaLog::create([
                'invoice_id' => $inv->id,
                'recipient_number' => $inv->tenant->phone,
                'message' => $msg,
                'status' => ($result['status'] ?? false) ? 'success' : 'failed'
            ]);
        }

        $this->info('Reminder WA berhasil diproses.');
    }
}