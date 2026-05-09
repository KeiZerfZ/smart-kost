<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use App\Models\Invoice;
use App\Models\Complaint;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalRooms = Room::count();
        $emptyRooms = Room::where('status', 'empty')->count();
        $totalTenants = Tenant::count();
        $pendingComplaints = Complaint::where('status', 'pending')->count();
        $monthlyIncome = Invoice::where('status', 'paid')
                                ->whereMonth('payment_date', now()->month)
                                ->sum('amount');

        // Pastikan variabel 'totalRooms' ada di dalam compact()
        return view('admin.dashboard', compact(
            'totalRooms', 
            'emptyRooms', 
            'totalTenants', 
            'pendingComplaints', 
            'monthlyIncome'
        ));
    }
}