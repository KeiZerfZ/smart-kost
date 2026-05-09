<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 'owner') {
            $invoices = Invoice::with('tenant.user', 'tenant.room')->get();
        } else {
            $invoices = Invoice::where('tenant_id', Auth::user()->tenant->id)->get();
        }
        return view('invoices.index', compact('invoices'));
    }

    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid']);
        return redirect()->back()->with('success', 'Pembayaran dikonfirmasi.');
    }

        // 1. Fungsi untuk Admin (Konfirmasi Cash)
    public function pay(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
            'payment_method' => 'cash',
            'payment_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran cash berhasil dikonfirmasi.');
    }

    // 2. Fungsi untuk Tenant (Bayar via QRIS - Simulasi)
    public function payQRIS(Invoice $invoice)
    {
        // Di sini biasanya ada integrasi Payment Gateway (Midtrans/Duitku)
        $invoice->update([
            'status' => 'paid',
            'payment_method' => 'qris',
            'payment_date' => now(),
        ]);

        return redirect()->back()->with('success', 'Pembayaran QRIS Berhasil! Tagihan lu sudah lunas.');
    }

    public function download(Invoice $invoice)
    {
        // Pastikan hanya tagihan yang sudah LUNAS yang bisa cetak bukti
        if ($invoice->status !== 'paid') {
            return redirect()->back()->with('error', 'Tagihan belum lunas, nggak bisa cetak bukti!');
        }

        // Load view khusus PDF dan kirim datanya
        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice'));

        // Download file dengan nama invoice-nomor.pdf
        return $pdf->download('Invoice-' . $invoice->id . '.pdf');
    }
}