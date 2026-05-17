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
use Symfony\Component\HttpFoundation\Response;

class TenantController extends Controller
{
    public function index()
    {
        // Hanya ambil penghuni yang berstatus AKTIF
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

            // 3. Buat Data Tenant (Pastikan is_active true)
            $tenant = Tenant::create([
                'user_id' => $user->id,
                'room_id' => $request->room_id,
                'phone' => $request->phone,
                'id_card_photo' => $path,
                'entry_date' => now(),
                'is_active' => true, // Set aktif saat daftar
            ]);

            // 4. Update Kamar
            Room::where('id', $request->room_id)->update(['status' => 'occupied']);

            // 5. Buat Tagihan
            Invoice::create([
                'tenant_id' => $tenant->id,
                'amount' => $tenant->room->price,
                'bill_date' => now(),
                'status' => 'unpaid',
            ]);

            return redirect()->route('tenants.index')->with('success', 'Penghuni berhasil didaftarkan.');
        });
    }

    /**
     * Fungsi Gatekeeper untuk menampilkan foto KTP secara aman.
     * Hanya diakses oleh Admin/Owner melalui Route khusus.
     */
    public function showKtp($filename)
    {
        // Pastikan path sesuai dengan penyimpanan di folder storage/app/ktp_photos/
        $path = 'ktp_photos/' . $filename;

        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Berkas identitas tidak ditemukan.');
        }

        // Mengembalikan file sebagai response gambar
        return Storage::disk('local')->response($path);
    }

    public function destroy(Tenant $tenant)
    {
        return DB::transaction(function() use ($tenant) {
            // 1. Kosongkan Kamar
            $tenant->room->update(['status' => 'empty']);

            // 2. Non-aktifkan Tenant (Bukan di-delete!)
            $tenant->update(['is_active' => false]);
            
            // 3. Opsional: Suspend akun user agar tidak bisa login lagi
            // $tenant->user->update(['is_active' => false]);

            return redirect()->route('tenants.index')->with('success', 'Penghuni telah checkout. Data arsip tersimpan.');
        });
    }
}