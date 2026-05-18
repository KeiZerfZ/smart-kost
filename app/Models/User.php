<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute; // Penting untuk Accessor

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    /**
     * Mass assignable attributes.
     * Pastikan 'avatar' ada di sini agar bisa di-update via ProfileController.
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role',
        'avatar' 
    ];

    /**
     * Attributes hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Accessor: avatar_url
     * Menangani logika foto profil default menggunakan UI Avatars.
     * Panggil di Blade dengan: {{ $user->avatar_url }}
     */
    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->avatar 
                ? asset('storage/' . $this->avatar) 
                : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF',
        );
    }

    /**
     * Relasi ke data penghuni jika role-nya adalah 'tenant'.
     */
    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class);
    }
}