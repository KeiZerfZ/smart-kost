<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Menampilkan formulir pendaftaran mandiri calon penghuni.
     */
    public function showRegistrationForm()
    {
        $rooms = Room::where('status', 'empty')->orderBy('room_number', 'asc')->get();
        return view('auth.register', compact('rooms'));
    }

    /**
     * Memproses data pendaftaran mandiri calon penghuni.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'telegram_chat_id' => 'required|string',
            'room_id' => 'required|exists:rooms,id',
            'id_card_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'required|string|min:6|confirmed',
        ]);

        DB::transaction(function() use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'tenant',
            ]);

            $path = $request->file('id_card_photo')->store('ktp_photos', 'local');

            Tenant::create([
                'user_id' => $user->id,
                'room_id' => $request->room_id,
                'phone' => $request->phone,
                'telegram_chat_id' => $request->telegram_chat_id,
                'id_card_photo' => $path,
                'status' => 'pending', 
            ]);
        });

        // Mengalihkan pengguna ke halaman sukses pendaftaran mandiri
        return redirect()->route('register.success');
    }

    /**
     * Menampilkan halaman pemberitahuan akun berhasil dibuat dan berstatus on-hold.
     */
    public function success()
    {
        return view('auth.register-success');
    }
}