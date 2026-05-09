<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'tenant_id', 
        'amount', 
        'bill_date', 
        'payment_date', 
        'status'
    ];

    // INI KUNCINYA: Kasih tahu Laravel kalau ini adalah tanggal
    protected $casts = [
        'bill_date' => 'date',
        'payment_date' => 'date',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}