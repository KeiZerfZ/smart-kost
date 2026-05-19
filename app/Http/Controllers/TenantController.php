<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Invoice;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['user', 'room'])->where('status', 'active')->get();
        return view('admin.tenants.index', compact('tenants'));
    }

    public function verificationIndex()
    {
        $pendingTenants = Tenant::with(['user', 'room'])->where('status', 'pending')->get();
        return view('admin.tenants.verification', compact('pendingTenants'));
    }

    public function create()
    {
        $rooms = Room::where('status', 'empty')->orderBy('room_number', 'asc')->get();
        return view('admin.tenants.create', compact('rooms'));
    }

    // Pendaftaran Manual oleh Admin (Langsung Aktif)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'telegram_chat_id' => 'nullable|string',
            'room_id' => 'required|exists:rooms,id',
            'id_card_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        return DB::transaction(function() use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('penghuni123'),
                'role' => 'tenant',
            ]);

            $path = $request->file('id_card_photo')->store('ktp_photos', 'local');

            $tenant = Tenant::create([
                'user_id' => $user->id,
                'room_id' => $request->room_id,
                'phone' => $request->phone,
                'telegram_chat_id' => $request->telegram_chat_id,
                'id_card_photo' => $path,
                'entry_date' => now(),
                'status' => 'active',
            ]);

            Room::where('id', $request->room_id)->update(['status' => 'occupied']);

            Invoice::create([
                'tenant_id' => $tenant->id,
                'amount' => $tenant->room->price,
                'bill_date' => now(),
                'due_date' => now()->addMonth(),
                'status' => 'unpaid',
            ]);

            return redirect()->route('tenants.index')->with('success', 'Penghuni baru berhasil didaftarkan secara manual.');
        });
    }

    // VERIFIKASI: MENYETUJUI PENDAFTARAN MANDIRI TENANT
    public function approve(Tenant $tenant)
    {
        // Pastikan kamar masih kosong sebelum disetujui
        if ($tenant->room->status !== 'empty') {
            return redirect()->back()->with('error', 'Kamar nomor ' . $tenant->room->room_number . ' telah diisi oleh penghuni lain.');
        }

        DB::transaction(function() use ($tenant) {
            // 1. Update data tenant menjadi aktif
            $tenant->update([
                'status' => 'active',
                'entry_date' => now()
            ]);

            // 2. Ubah status kamar kost
            $tenant->room->update(['status' => 'occupied']);

            // 3. Terbitkan invoice pertama
            Invoice::create([
                'tenant_id' => $tenant->id,
                'amount' => $tenant->room->price,
                'bill_date' => now(),
                'due_date' => now()->addMonth(),
                'status' => 'unpaid',
            ]);
        });

        // 4. Kirim notifikasi selamat datang ke Telegram Tenant secara real-time
        if ($tenant->telegram_chat_id) {
            $message = "🎉 *AKUN ANDA TELAH DIVERIFIKASI*\n\nSelamat, pendaftaran Anda di *SmartKost* telah disetujui oleh Administrator.\n\n📦 *Detail Fasilitas:*\n• Nama: *{$tenant->user->name}*\n• Kamar: *Kamar {$tenant->room->room_number}* ({$tenant->room->type})\n\nSilakan masuk ke aplikasi menggunakan email Anda untuk melihat rincian tagihan perdana. Terima kasih!";
            TelegramService::sendMessage($tenant->telegram_chat_id, $message);
        }

        return redirect()->route('tenants.index')->with('success', 'Pendaftaran hunian ' . $tenant->user->name . ' telah disetujui.');
    }

    // VERIFIKASI: MENOLAK PENDAFTARAN MANDIRI TENANT
    public function reject(Tenant $tenant)
    {
        DB::transaction(function() use ($tenant) {
            // Ambil data user terkait
            $user = $tenant->user;
            
            // Hapus file foto KTP dari storage local untuk efisiensi ruang penyimpanan
            if (Storage::disk('local')->exists($tenant->id_card_photo)) {
                Storage::disk('local')->delete($tenant->id_card_photo);
            }

            // Hapus tenant dan user karena ditolak
            $tenant->delete();
            $user->delete();
        });

        return redirect()->back()->with('success', 'Permintaan pendaftaran berhasil ditolak dan data berkas telah dihapus dari sistem.');
    }

    public function showKtp($filename)
    {
        $path = 'ktp_photos/' . $filename;
        if (!Storage::disk('local')->exists($path)) { abort(404); }
        return Storage::disk('local')->response($path);
    }

    public function destroy(Tenant $tenant)
    {
        DB::transaction(function() use ($tenant) {
            $tenant->room->update(['status' => 'empty']);
            $tenant->update(['status' => 'inactive']);
        });
        return redirect()->route('tenants.index')->with('success', 'Penghuni telah checkout.');
    }
}