<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use App\Models\Invoice;
use Carbon\Carbon;

class GenerateMonthlyInvoices extends Command
{
    // Signature perintah yang dipanggil oleh cron job 
    protected $signature = 'invoices:generate';
    protected $description = 'Otomatis generate tagihan bulanan untuk setiap penghuni kost';

    public function handle()
    {
        $today = Carbon::today();
        
        // Ambil semua penghuni yang aktif 
        $tenants = Tenant::with('room')->get();
        $count = 0;

        foreach ($tenants as $tenant) {
            // Logika: Buat tagihan jika hari ini sama dengan tanggal masuk (misal masuk tgl 5, tagihan muncul tiap tgl 5) [cite: 231]
            if ($today->day == $tenant->entry_date->day) {
                
                // Cek apakah tagihan untuk bulan ini sudah pernah dibuat sebelumnya [cite: 79]
                $exists = Invoice::where('tenant_id', $tenant->id)
                    ->whereMonth('bill_date', $today->month)
                    ->whereYear('bill_date', $today->year)
                    ->exists();

                if (!$exists) {
                    Invoice::create([
                        'tenant_id' => $tenant->id,
                        'amount' => $tenant->room->price, // Mengambil harga sewa dari kamar 
                        'bill_date' => $today,
                        'status' => 'unpaid', // Status default adalah belum bayar [cite: 65]
                    ]);
                    $count++;
                }
            }
        }

        $this->info("Berhasil membuat $count tagihan baru.");
    }
}