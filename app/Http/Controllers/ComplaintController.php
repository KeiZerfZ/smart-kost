<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    // Lihat semua keluhan (Untuk Owner)
    public function index()
    {
        $complaints = Complaint::with('tenant.user', 'tenant.room')->latest()->get();
        return view('admin.complaints.index', compact('complaints'));
    }

    // Penghuni kirim keluhan
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
        ]);

        // Cari ID tenant milik user yang sedang login
        $tenant = Tenant::where('user_id', auth()->id())->first();

        Complaint::create([
            'tenant_id' => $tenant->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Keluhan lu sudah terkirim, sabar ya!');
    }

    // Update status keluhan (Untuk Owner)
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $complaint->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status keluhan berhasil diperbarui.');
    }
}