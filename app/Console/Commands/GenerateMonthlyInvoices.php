<?php

namespace App\Console\Exceptions; // Sesuaikan jika namespace bawaan Anda menggunakan App\Console\Commands
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\Invoice;
use App\Services\TelegramService;
use Carbon\Carbon;

class GenerateMonthlyInvoices extends Command
{
    /**
     * Signature perintah yang dipanggil oleh cron job atau CLI
     */
    protected $signature = 'invoices:generate';

    /**
     * Deskripsi perintah eksekusi
     */
    protected $description = 'Otomatis menerbitkan tagihan bulanan untuk setiap penghuni kost aktif berdasarkan siklus tanggal masuk';

    public function handle()
    {
        $today = Carbon::today();
        
        // Hanya ambil penghuni yang berstatus aktif beserta relasinya
        $tenants = Tenant::with(['user', 'room'])->where('is_active', true)->get();
        $count = 0;

        foreach ($tenants as $tenant) {
            // Pastikan properti entry_date telah di-cast sebagai date/carbon di Model Tenant
            $entryDate = Carbon::parse($tenant->entry_date);

            // Logika Siklus: Tagihan terbit jika hari ini sama dengan tanggal masuk
            if ($today->day === $entryDate->day) {
                
                // Proteksi: Cek apakah tagihan untuk periode bulan dan tahun ini sudah pernah dibuat
                $exists = Invoice::where('tenant_id', $tenant->id)
                    ->whereMonth('bill_date', $today->month)
                    ->whereYear('bill_date', $today->year)
                    ->exists();

                if (!$exists) {
                    // Buat satu data tagihan baru secara resmi
                    $invoice = Invoice::create([
                        'tenant_id' => $tenant->id,
                        'amount'    => $tenant->room->price, 
                        'bill_date' => $today,
                        'due_date'  => $today->copy()->addMonth(), // Jatuh tempo 1 bulan ke depan
                        'status'    => 'unpaid',
                    ]);

                    $count++;

                    // Kirim Notifikasi Tagihan Baru ke Telegram Tenant
                    $this->sendTelegramBillNotification($tenant, $invoice);
                }
            }
        }

        $this->info("Proses selesai. Berhasil menerbitkan $count tagihan baru.");
    }

    /**
     * Fungsi Bantu: Mengirimkan informasi tagihan baru ke Telegram
     */
    private function sendTelegramBillNotification($tenant, $invoice)
    {
        $chatId = $tenant->telegram_chat_id;
        if (!$chatId) return;

        $amount = number_format($invoice->amount, 0, ',', '.');
        $dueDate = Carbon::parse($invoice->due_date)->format('d/m/Y');

        $message = "🔔 *TAGIHAN BARU TELAH TERBIT*\n\n";
        $message .= "Halo *{$tenant->user->name}*,\n";
        $message .= "Tagihan hunian Anda untuk periode *{$invoice->bill_date->format('F Y')}* telah diterbitkan oleh sistem.\n\n";
        $message .= "📊 *Rincian Tagihan:*\n";
        $message .= "• No. Invoice: `#INV-{$invoice->id}`\n";
        $message .= "• Kamar: *Kamar {$tenant->room->room_number}*\n";
        $message .= "• Total Tagihan: *Rp {$amount}*\n";
        $message .= "• Batas Jatuh Tempo: *{$dueDate}*\n\n";
        $message .= "Mohon lakukan penyelesaian pembayaran sebelum tanggal jatuh tempo melalui tautan di bawah ini.";

        $inlineKeyboard = [
            [
                ['text' => '💳 Bayar Sekarang', 'url' => config('app.url')]
            ]
        ];

        TelegramService::sendMessage($chatId, $message, $inlineKeyboard);
    }
}