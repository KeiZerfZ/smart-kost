<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Invoice;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantDashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil data tenant berdasarkan user yang sedang login
        $tenant = Tenant::with(['room', 'user'])->where('user_id', Auth::id())->first();

        // Jika user ini belum terdaftar di tabel tenants (error handling)
        if (!$tenant) {
            return view('tenant.incomplete');
        }

        // 2. Ambil tagihan yang belum dibayar (unpaid)
        $unpaidInvoices = Invoice::where('tenant_id', $tenant->id)
                                  ->where('status', 'unpaid')
                                  ->get();

        // 3. Ambil riwayat keluhan terbaru
        $recentComplaints = Complaint::where('tenant_id', $tenant->id)
                                      ->latest()
                                      ->take(5)
                                      ->get();

        return view('tenant.dashboard', compact('tenant', 'unpaidInvoices', 'recentComplaints'));
    }
}