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

        // Ambil ID tenant dari user yang lagi login
        $tenant = \App\Models\Tenant::where('user_id', auth()->id())->first();

        if (!$tenant) {
            return redirect()->back()->with('error', 'Data penghuni tidak ditemukan.');
        }

        \App\Models\Complaint::create([
            'tenant_id' => $tenant->id,
            'title' => $request->title,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Keluhan sudah terkirim, sabar ya!');
    }

    // Update status keluhan (Untuk Owner)
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:pending,process,resolved',
        ]);

        $complaint->update([
            'status' => $request->status
        ]);

        $msg = "Keluhan berhasil di-update jadi " . strtoupper($request->status);
        return redirect()->back()->with('success', $msg);
    }
}