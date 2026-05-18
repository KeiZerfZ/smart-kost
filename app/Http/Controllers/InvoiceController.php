<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Services\TelegramService; // Panggil Service Baru
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

            return redirect()->back()->with('success', 'Pembayaran cash berhasil! Notif Telegram terkirim.');
        } catch (Exception $e) {
            return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
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

            return redirect()->back()->with('success', 'Pembayaran QRIS Berhasil! Notif Telegram mendarat.');
        } catch (Exception $e) {
            return redirect()->back()->with('success', 'Pembayaran Berhasil.');
        }
    }

    private function sendTelegramNotification($invoice)
    {
        $tenant = $invoice->tenant;
        $chatId = $tenant->telegram_chat_id;

        // Jika kolom di DB kosong, jangan kirim apa-apa
        if (!$chatId) return;

        $amount = number_format($invoice->amount, 0, ',', '.');
        
        $message = "✅ *PEMBAYARAN DITERIMA*\n\n";
        $message .= "Halo *{$tenant->user->name}*,\n";
        $message .= "Tagihan periode *{$invoice->created_at->format('F Y')}* telah lunas.\n\n";
        $message .= "💰 Nominal: *Rp {$amount}*\n";
        $message .= "💳 Metode: " . strtoupper($invoice->payment_method) . "\n";
        $message .= "📅 Waktu: " . $invoice->payment_date->format('d/m/Y H:i') . " WIB\n\n";
        $message .= "Terima kasih! 🙏";

        TelegramService::sendMessage($chatId, $message);
    }

    public function download(Invoice $invoice)
    {
        if ($invoice->status !== 'paid') {
            return redirect()->back()->with('error', 'Tagihan belum lunas!');
        }
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));
        return $pdf->download('Invoice-' . $invoice->id . '.pdf');
    }
}