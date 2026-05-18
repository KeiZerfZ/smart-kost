<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 
        'amount', 
        'bill_date', 
        'payment_date', 
        'payment_method', 
        'status'
    ];

    protected $casts = [
        'amount' => 'integer',       // Pastikan dibaca sebagai angka
        'bill_date' => 'date',
        'payment_date' => 'datetime', // WAJIB datetime biar muncul jam:menit
    ];

    /**
     * Relasi ke Tenant
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relasi ke Log WhatsApp (Jika ada)
     */
    public function waLogs(): HasMany
    {
        return $this->hasMany(WaLog::class);
    }
}