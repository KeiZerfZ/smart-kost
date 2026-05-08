<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Statistik Ringkasan (Fitur Wajib P-01) 
        $totalRevenue = Invoice::where('status', 'paid')->sum('amount');
        $emptyRooms = Room::where('status', 'empty')->count();
        $unpaidInvoices = Invoice::where('status', 'unpaid')->count();
        $activeTenants = Tenant::count();

        // 2. Data untuk Grafik Pendapatan Bulanan 
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->select(
                DB::raw('SUM(amount) as total'),
                DB::raw("DATE_FORMAT(bill_date, '%M') as month"),
                DB::raw("MONTH(bill_date) as month_num") // Ambil angka bulan untuk sorting
            )
            ->groupBy('month_num', 'month') // Kelompokkan berdasarkan keduanya
            ->orderBy('month_num', 'asc') // Urutkan berdasarkan angka bulan
            ->get();
            
        return view('admin.dashboard', compact(
            'totalRevenue', 
            'emptyRooms', 
            'unpaidInvoices', 
            'activeTenants',
            'monthlyRevenue'
        ));
    }
}