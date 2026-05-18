<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Tenant extends Model
{
    protected $fillable = [
        'user_id', 
        'room_id', 
        'phone',
        'telegram_chat_id', 
        'id_card_photo', 
        'entry_date', 
        'is_active'
    ];

    protected $casts = [
        'entry_date' => 'date',
        'is_active' => 'boolean' // Pastikan di-cast ke boolean
    ];

    /**
     * Accessor untuk mendapatkan URL Secure foto KTP.
     * Menggunakan route gatekeeper agar tetap terlindungi.
     * Panggil di Blade dengan: {{ $tenant->ktp_url }}
     */
    protected function ktpUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->id_card_photo) {
                    return asset('images/default-ktp.png');
                }
                
                // Membersihkan path 'ktp_photos/' jika ada, karena route hanya butuh filename
                $filename = str_replace('ktp_photos/', '', $this->id_card_photo);
                
                return route('tenants.ktp.show', $filename);
            }
        );
    }

    /**
     * Relasi ke User (Akun Login)
     */
    public function user(): BelongsTo 
    { 
        return $this->belongsTo(User::class); 
    }

    /**
     * Relasi ke Kamar
     */
    public function room(): BelongsTo 
    { 
        return $this->belongsTo(Room::class); 
    }
    
    /**
     * Relasi ke riwayat tagihan
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}