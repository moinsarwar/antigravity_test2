<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'invoice_number',
        'sale_date',
        'total_amount',
        'cash_amount',
        'credit_amount',
        'payment_type',
        'customer_name'
    ];

    protected $casts = [
        'sale_date' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }
}
