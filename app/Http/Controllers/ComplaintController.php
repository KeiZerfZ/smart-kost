<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    // List keluhan (Pemilik liat semua, Penghuni liat miliknya sendiri)
    public function index()
    {
        if (Auth::user()->role == 'owner') {
            $complaints = Complaint::with('tenant.user')->latest()->get();
        } else {
            $complaints = Complaint::where('tenant_id', Auth::user()->tenant->id)->latest()->get();
        }
        return view('complaints.index', compact('complaints'));
    }

    // Simpan laporan baru (Sisi Penghuni)
    public function store(Request $request)
    {
        $request->validate(['description' => 'required']);
        
        Complaint::create([
            'tenant_id' => Auth::user()->tenant->id,
            'description' => $request->description,
            'status' => 'pending'
        ]);

        return redirect()->back()->with('success', 'Keluhan berhasil dikirim.');
    }

    // Update status (Sisi Pemilik)
    public function updateStatus(Request $request, Complaint $complaint)
    {
        $complaint->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status keluhan diperbarui.');
    }
}
