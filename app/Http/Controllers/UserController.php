<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Tampilkan semua user, urutkan biar Owner ada di paling atas
        $users = User::orderBy('role', 'asc')->get();
        return view('admin.users.index', compact('users'));
    }

    // Fitur Reset Password oleh Admin (Jika user lupa total)
    public function resetPassword(User $user)
    {
        $newPassword = 'kost' . rand(100, 999); // Generate password acak sederhana
        
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        return redirect()->back()->with('success', "Password {$user->name} berhasil di-reset menjadi: {$newPassword}. Silakan infokan ke user terkait.");
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return redirect()->back()->with('error', 'Nggak bisa hapus akun sendiri, Nanti kamu gak bisa login lagi.');
        }
        
        $user->delete();
        return redirect()->back()->with('success', 'Akun user berhasil dihapus dari sistem.');
    }
}