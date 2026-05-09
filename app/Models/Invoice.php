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
        'payment_method', // TAMBAHKAN INI
        'status'
    ];

    protected $casts = [
        'bill_date' => 'date',
        'payment_date' => 'date',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}