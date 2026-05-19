<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 
        'amount', 
        'bill_date', 
        'due_date',
        'payment_date', 
        'payment_method', 
        'status'
    ];

    protected $casts = [
        'amount' => 'integer',
        'bill_date' => 'date',
        'due_date' => 'date',
        'payment_date' => 'datetime',
    ];

    /**
     * Relasi ke Tenant
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}