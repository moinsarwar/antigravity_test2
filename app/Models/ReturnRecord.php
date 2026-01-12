<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnRecord extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'return_invoice_number',
        'original_sale_id',
        'return_date',
        'total_amount',
        'return_type'
    ];

    protected $casts = [
        'return_date' => 'datetime',
    ];

    public function originalSale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'original_sale_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReturnItem::class, 'return_id');
    }
}
