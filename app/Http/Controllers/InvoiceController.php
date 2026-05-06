<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
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
}