<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role'];

    // Relasi ke data penghuni jika role-nya adalah 'tenant' [cite: 63]
    public function tenant(): HasOne
    {
        return $this->hasOne(Tenant::class);
    }
}