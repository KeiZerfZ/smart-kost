<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Services\FonnteService; // Fokus ke WA
use Exception;

class InvoiceController extends Controller
{
    /**
     * Tampilkan daftar tagihan
     */
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

    /**
     * Konfirmasi Pembayaran Cash (Admin)
     */
    public function pay(Invoice $invoice)
    {
        try {
            // 1. Update Database
            $invoice->update([
                'status' => 'paid',
                'payment_method' => 'cash',
                'payment_date' => now(),
            ]);

            // 2. Kirim Notifikasi WA saja
            $this->sendWhatsAppNotification($invoice);

            return redirect()->back()->with('success', 'Pembayaran cash dikonfirmasi. Notifikasi WA terkirim!');

        } catch (Exception $e) {
            return redirect()->back()->with('success', 'Pembayaran OK, tapi WA gagal: ' . $e->getMessage());
        }
    }

    /**
     * Bayar via QRIS (Tenant)
     */
    public function payQRIS(Invoice $invoice)
    {
        try {
            $invoice->update([
                'status' => 'paid',
                'payment_method' => 'qris',
                'payment_date' => now(),
            ]);

            $this->sendWhatsAppNotification($invoice);

            return redirect()->back()->with('success', 'Pembayaran QRIS Berhasil! Notifikasi WA telah mendarat.');

        } catch (Exception $e) {
            return redirect()->back()->with('success', 'Pembayaran Berhasil! (Notifikasi WA gagal)');
        }
    }

    /**
     * Helper Function: Kirim WhatsApp saja
     */
    private function sendWhatsAppNotification($invoice)
    {
        $user = $invoice->tenant->user; 
        $phone = $invoice->tenant->phone; // Nomor dari tabel tenants

        $amount_formatted = number_format($invoice->amount, 0, ',', '.'); 
        
        $message = "Halo *{$user->name}*,\n\n";
        $message .= "Pembayaran tagihan SmartKost Anda periode *{$invoice->created_at->format('M Y')}* sebesar *Rp {$amount_formatted}* telah berhasil dikonfirmasi.\n\n";
        $message .= "Status: *LUNAS*\n";
        $message .= "Metode: " . strtoupper($invoice->payment_method) . "\n";
        $message .= "Tanggal: " . $invoice->payment_date->format('d M Y H:i') . " WIB\n\n";
        $message .= "Terima kasih telah melakukan pembayaran tepat waktu! 🙏";

        // Langsung kirim via Fonnte (HTTPS API)
        if ($phone) {
            FonnteService::send($phone, $message);
        }
    }

    public function download(Invoice $invoice)
    {
        if ($invoice->status !== 'paid') {
            return redirect()->back()->with('error', 'Tagihan belum lunas!');
        }
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));
        return $pdf->download('Invoice-' . $invoice->id . '.pdf');
    }

    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid']);
        return redirect()->back()->with('success', 'Status tagihan diperbarui.');
    }
}