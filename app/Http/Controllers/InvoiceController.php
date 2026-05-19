<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Services\TelegramService;
use Exception;

class InvoiceController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'owner') {
            $invoices = Invoice::with(['tenant.user', 'tenant.room'])->latest()->get();
            return view('admin.invoices.index', compact('invoices'));
        } else {
            $invoices = Invoice::where('tenant_id', Auth::user()->tenant->id)->latest()->get();
            return view('tenant.dashboard', compact('invoices')); 
        }
    }

    public function pay(Invoice $invoice)
    {
        try {
            $invoice->update([
                'status' => 'paid',
                'payment_method' => 'cash',
                'payment_date' => now(),
            ]);

            $this->sendTelegramNotification($invoice);
            return redirect()->back()->with('success', 'Pembayaran cash berhasil diproses.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran.');
        }
    }

    public function payQRIS(Invoice $invoice)
    {
        try {
            $invoice->update([
                'status' => 'paid',
                'payment_method' => 'qris',
                'payment_date' => now(),
            ]);

            $this->sendTelegramNotification($invoice);
            return redirect()->back()->with('success', 'Pembayaran QRIS berhasil dikonfirmasi.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembayaran QRIS.');
        }
    }

    /**
     * Fitur Tambahan: Simulasi Aksi Scan QRIS Menggunakan Kamera HP Real
     */
    public function payQRISScan(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return response("
                <div style='text-align:center;font-family:sans-serif;margin-top:20%;padding:20px;'>
                    <h1 style='color:#16a34a;'>SmartKost Payments</h1>
                    <p style='font-size:18px;color:#4b5563;'>Transaksi ini telah dinyatakan lunas sebelumnya.</p>
                </div>
            ");
        }

        $invoice->update([
            'status' => 'paid',
            'payment_method' => 'qris',
            'payment_date' => now(),
        ]);

        $this->sendTelegramNotification($invoice);

        return response("
            <div style='text-align:center;font-family:sans-serif;margin-top:15%;padding:20px;'>
                <h1 style='color:#2563eb;font-weight:900;letter-spacing:-1px;'>SmartKost Core</h1>
                <div style='font-size:48px;margin-bottom:10px;'>✅</div>
                <p style='font-size:20px;font-weight:bold;color:#1f2937;'>Pembayaran QRIS Berhasil Dikonfirmasi!</p>
                <p style='color:#6b7280;font-size:14px;'>Data base telah diperbarui. Silakan periksa aplikasi Telegram Anda.</p>
            </div>
        ");
    }

    /**
     * Mengirimkan Notifikasi dengan Konstruksi Tombol Interaktif Telegram
     */
    private function sendTelegramNotification($invoice)
    {
        $tenant = $invoice->tenant;
        $chatId = $tenant->telegram_chat_id;

        if (!$chatId) return;

        $amount = number_format($invoice->amount, 0, ',', '.');
        $downloadUrl = route('invoices.download', $invoice->id);

        $message = "✅ *PEMBAYARAN KONFIRMASI BERHASIL*\n\n";
        $message .= "Yth. *{$tenant->user->name}*,\n";
        $message .= "Pembayaran tagihan hunian Anda telah berhasil diverifikasi oleh sistem.\n\n";
        $message .= "📊 *Rincian Transaksi:*\n";
        $message .= "• No. Invoice: `#INV-{$invoice->id}`\n";
        $message .= "• Periode: *{$invoice->created_at->format('F Y')}*\n";
        $message .= "• Total Bayar: *Rp {$amount}*\n";
        $message .= "• Metode: " . strtoupper($invoice->payment_method) . "\n";
        $message .= "• Tanggal: " . $invoice->payment_date->format('d/m/Y H:i') . " WIB\n\n";
        $message .= "Kuitansi digital resmi dapat diunduh melalui tombol interaktif di bawah ini.";

        // Penyusunan Baris Tombol Interaktif Berdasarkan Regulasi API Telegram
        $inlineKeyboard = [
            [
                ['text' => '📄 Unduh Kuitansi PDF', 'url' => $downloadUrl],
                ['text' => '🌐 Buka Dashboard', 'url' => config('app.url')]
            ]
        ];

        TelegramService::sendMessage($chatId, $message, $inlineKeyboard);
    }

    public function download(Invoice $invoice)
    {
        if ($invoice->status !== 'paid') {
            return redirect()->back()->with('error', 'Unduhan ditolak, tagihan belum diselesaikan.');
        }
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));
        return $pdf->download('Invoice-' . $invoice->id . '.pdf');
    }
}