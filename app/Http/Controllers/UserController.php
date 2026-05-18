<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        // Tampilkan semua user, sertakan relasi tenant untuk ambil nomor kamar jika ada
        $users = User::with('tenant.room')->orderBy('role', 'asc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function resetPassword(User $user)
    {
        $newPassword = 'kost' . rand(100, 999);
        
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        return redirect()->back()->with('success', "Password {$user->name} berhasil di-reset menjadi: {$newPassword}.");
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return redirect()->back()->with('error', 'Nggak bisa hapus akun sendiri.');
        }

        // Jika user punya foto profil, hapus filenya dari storage
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        
        $user->delete();
        return redirect()->back()->with('success', 'Akun user berhasil dihapus dari sistem.');
    }
}