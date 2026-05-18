<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function index()
    {
        // Ambil penghuni AKTIF
        $tenants = Tenant::with(['user', 'room'])
                    ->where('is_active', true)
                    ->get();
                    
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $rooms = Room::where('status', 'empty')->orderBy('room_number', 'asc')->get();
        return view('admin.tenants.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'telegram_chat_id' => 'nullable|string', // WAJIB TAMBAH INI
            'room_id' => 'required|exists:rooms,id',
            'id_card_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        return DB::transaction(function() use ($request) {
            // 1. Buat User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('penghuni123'),
                'role' => 'tenant',
            ]);

            // 2. Simpan Foto KTP
            $path = $request->file('id_card_photo')->store('ktp_photos', 'local');

            // 3. Buat Data Tenant
            $tenant = Tenant::create([
                'user_id' => $user->id,
                'room_id' => $request->room_id,
                'phone' => $request->phone,
                'telegram_chat_id' => $request->telegram_chat_id, // WAJIB MASUKIN SINI
                'id_card_photo' => $path,
                'entry_date' => now(),
                'is_active' => true,
            ]);

            // 4. Update Status Kamar
            Room::where('id', $request->room_id)->update(['status' => 'occupied']);

            // 5. Buat Tagihan Pertama
            Invoice::create([
                'tenant_id' => $tenant->id,
                'amount' => $tenant->room->price,
                'bill_date' => now(),
                'status' => 'unpaid',
            ]);

            return redirect()->route('tenants.index')->with('success', 'Penghuni berhasil didaftarkan.');
        });
    }

    public function showKtp($filename)
    {
        $path = 'ktp_photos/' . $filename;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Berkas identitas tidak ditemukan.');
        }

        return Storage::disk('local')->response($path);
    }

    public function destroy(Tenant $tenant)
    {
        return DB::transaction(function() use ($tenant) {
            // 1. Kosongkan Kamar
            $tenant->room->update(['status' => 'empty']);

            // 2. Non-aktifkan Tenant
            $tenant->update(['is_active' => false]);

            return redirect()->route('tenants.index')->with('success', 'Penghuni telah checkout.');
        });
    }
}