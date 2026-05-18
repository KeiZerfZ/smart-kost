<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; // Wajib untuk kirim email
use App\Mail\InvoiceGenerated;      // Wajib panggil class Mailable lu
use Exception;

class InvoiceController extends Controller
{
    /**
     * Tampilkan daftar tagihan berdasarkan role.
     */
    public function index()
    {
        if (Auth::user()->role == 'owner') {
            // Admin: Liat semua tagihan
            $invoices = Invoice::with(['tenant.user', 'tenant.room'])
                        ->latest()
                        ->get();
            
            return view('admin.invoices.index', compact('invoices'));
        } else {
            // Tenant: Liat tagihan milik sendiri
            $invoices = Invoice::where('tenant_id', Auth::user()->tenant->id)
                        ->latest()
                        ->get();
                        
            return view('tenant.dashboard', compact('invoices')); 
        }
    }

    /**
     * Fungsi untuk Admin (Konfirmasi Pembayaran Cash)
     */
    public function pay(Invoice $invoice)
    {
        try {
            // 1. Update status pembayaran di database
            $invoice->update([
                'status' => 'paid',
                'payment_method' => 'cash',
                'payment_date' => now(),
            ]);

            // 2. Kirim Email Bukti Pembayaran
            Mail::to($invoice->tenant->user->email)->send(new InvoiceGenerated($invoice));

            return redirect()->back()->with('success', 'Pembayaran cash berhasil dikonfirmasi dan bukti email telah dikirim!');

        } catch (Exception $e) {
            // Jika database ok tapi email gagal, tetap lunas tapi kasih warning error
            return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi, namun email bukti gagal terkirim: ' . $e->getMessage());
        }
    }

    /**
     * Fungsi untuk Tenant (Bayar via QRIS)
     */
    public function payQRIS(Invoice $invoice)
    {
        try {
            // Update status pembayaran
            $invoice->update([
                'status' => 'paid',
                'payment_method' => 'qris',
                'payment_date' => now(),
            ]);

            // Kirim Email Bukti Pembayaran
            Mail::to($invoice->tenant->user->email)->send(new InvoiceGenerated($invoice));

            return redirect()->back()->with('success', 'Pembayaran QRIS Berhasil! Bukti invoice telah mendarat di email lu.');

        } catch (Exception $e) {
            return redirect()->back()->with('success', 'Pembayaran QRIS Berhasil! (Email notifikasi gagal terkirim)');
        }
    }

    /**
     * Download Bukti Pembayaran PDF
     */
    public function download(Invoice $invoice)
    {
        // Pastikan hanya tagihan yang sudah LUNAS yang bisa cetak bukti
        if ($invoice->status !== 'paid') {
            return redirect()->back()->with('error', 'Tagihan belum lunas, nggak bisa cetak bukti!');
        }

        // Load view khusus PDF dan kirim datanya
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));

        // Download file dengan nama Invoice-ID.pdf
        return $pdf->download('Invoice-' . $invoice->id . '.pdf');
    }

    /**
     * Shortcut mark as paid (opsional, jika masih dibutuhkan)
     */
    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid']);
        return redirect()->back()->with('success', 'Pembayaran dikonfirmasi.');
    }
}