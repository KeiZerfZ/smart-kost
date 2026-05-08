<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TenantController extends Controller
{
    /**
     * Menampilkan daftar penghuni dan form pendaftaran.
     * Dibutuhkan untuk memenuhi kriteria Manajemen Penghuni.
     */
    public function index()
    {
        // Ambil semua penghuni beserta data user dan kamarnya
        $tenants = Tenant::with(['user', 'room'])->get();

        // Ambil kamar yang statusnya masih kosong untuk form pendaftaran 
        $availableRooms = Room::where('status', 'empty')->get();

        return view('admin.tenants.index', compact('tenants', 'availableRooms'));
    }

    /**
     * Menyimpan data penghuni baru.
     * Menangani pembuatan akun user, link ke kamar, dan upload foto KTP.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'room_id' => 'required|exists:rooms,id',
            'entry_date' => 'required|date',
            'id_card_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi foto KTP 
        ]);

        // 1. Buat User baru untuk akun login penghuni
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password123'), // Password default
            'role' => 'tenant',
        ]);

        // 2. Proses upload foto KTP (jika ada) 
        $path = null;
        if ($request->hasFile('id_card_photo')) {
            $path = $request->file('id_card_photo')->store('public/ktp');
        }

        // 3. Simpan data ke tabel tenants 
        Tenant::create([
            'user_id' => $user->id,
            'room_id' => $request->room_id,
            'entry_date' => $request->entry_date,
            'id_card_photo' => $path,
        ]);

        // 4. Update status kamar menjadi terisi (occupied) 
        Room::find($request->room_id)->update(['status' => 'occupied']);

        return redirect()->back()->with('success', 'Penghuni berhasil didaftarkan dan akun user telah dibuat.');
    }
}