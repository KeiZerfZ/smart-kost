<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\WaLog;
use App\Services\FonnteService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Import untuk logging
use Exception;

class SendWaReminders extends Command
{
    protected $signature = 'send:wa-reminders';
    protected $description = 'Kirim pengingat tagihan H-3 via WhatsApp';

    public function handle()
    {
        try {
            // Logika: H-3 dari jatuh tempo (asumsi siklus 30 hari)
            $targetDate = Carbon::now()->subDays(27)->toDateString();

            $invoices = Invoice::with('tenant.user')
                        ->where('status', 'unpaid')
                        ->whereDate('bill_date', $targetDate)
                        ->get();

            if ($invoices->isEmpty()) {
                $this->info('Tidak ada tagihan yang masuk kriteria pengingat hari ini.');
                return;
            }

            foreach ($invoices as $inv) {
                try {
                    // Validasi nomor telepon sederhana
                    if (!$inv->tenant->phone) {
                        throw new Exception("Nomor telepon tidak ditemukan untuk tenant: {$inv->tenant->user->name}");
                    }

                    $msg = "Halo *{$inv->tenant->user->name}*,\n\nIni pengingat otomatis dari *SmartKost*. Tagihan bulan {$inv->bill_date->format('F')} sebesar *Rp " . number_format($inv->amount, 0, ',', '.') . "* akan jatuh tempo dalam 3 hari. Mohon segera selesaikan pembayaran ya. Matur suwun!";

                    // Eksekusi kirim via Service
                    $result = FonnteService::send($inv->tenant->phone, $msg);

                    // Catat log sukses/gagal dari respon API
                    WaLog::create([
                        'invoice_id' => $inv->id,
                        'recipient_number' => $inv->tenant->phone,
                        'message' => $msg,
                        'status' => ($result['status'] ?? false) ? 'success' : 'failed'
                    ]);

                } catch (Exception $e) {
                    // Jika satu pengiriman gagal, catat di log Laravel dan lanjut ke invoice berikutnya
                    Log::error("Gagal memproses reminder WA untuk Invoice #{$inv->id}: " . $e->getMessage());
                    
                    // Tetap buat log di database dengan status failed untuk audit trail
                    WaLog::create([
                        'invoice_id' => $inv->id,
                        'recipient_number' => $inv->tenant->phone ?? 'N/A',
                        'message' => "ERROR: " . $e->getMessage(),
                        'status' => 'failed'
                    ]);
                    
                    continue; 
                }
            }

            $this->info('Proses pengiriman reminder WA selesai dikerjakan.');

        } catch (Exception $e) {
            // Menangani error kritikal (misal: gagal query ke database)
            Log::critical("Kritikal Error pada Command SendWaReminders: " . $e->getMessage());
            $this->error('Terjadi kesalahan fatal pada sistem reminder.');
        }
    }
}