<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Invoice; // Wajib di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['user', 'room'])->get();
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $rooms = Room::where('status', 'empty')->get();
        return view('admin.tenants.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'room_id' => 'required|exists:rooms,id',
            'id_card_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        return DB::transaction(function() use ($request) {
            // 1. Buat User (Password default: penghuni123)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('penghuni123'),
                'role' => 'tenant',
            ]);

            // 2. Upload File KTP
            $path = $request->file('id_card_photo')->store('ktp_photos', 'public');

            // 3. Buat Data Tenant
            $tenant = Tenant::create([
                'user_id' => $user->id,
                'room_id' => $request->room_id,
                'phone' => $request->phone,
                'id_card_photo' => $path,
                'entry_date' => now(),
            ]);

            // 4. Update Status Kamar jadi Terisi
            Room::where('id', $request->room_id)->update(['status' => 'occupied']);

            // 5. OTOMATISASI: Buat Tagihan Pertama (Bulan Pertama)
            Invoice::create([
                'tenant_id' => $tenant->id,
                'amount' => $tenant->room->price, // Ambil harga kamar otomatis
                'bill_date' => now(),
                'status' => 'unpaid',
            ]);

            return redirect()->route('tenants.index')->with('success', 'Penghuni didaftarkan & tagihan pertama telah dibuat. Password default: penghuni123');
        });
    }

    public function destroy(Tenant $tenant)
    {
        return DB::transaction(function() use ($tenant) {
            if ($tenant->id_card_photo && Storage::disk('public')->exists($tenant->id_card_photo)) {
                Storage::disk('public')->delete($tenant->id_card_photo);
            }

            $tenant->room->update(['status' => 'empty']);
            $tenant->user->delete();

            return redirect()->route('tenants.index')->with('success', 'Penghuni telah checkout.');
        });
    }
}