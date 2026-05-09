<?php

namespace App\Models;

// Tambahkan import ini
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory; // Tambahan standar

class User extends Authenticatable
{
    // Panggil trait-nya di sini
    use Notifiable, HasFactory;

    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke data penghuni jika role-nya adalah 'tenant'
    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class);
    }
}