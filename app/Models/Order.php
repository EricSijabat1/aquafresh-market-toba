<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'order_number',
        'total_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getTotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public static function generateOrderNumber(): string
    {
        $prefix = 'AF-' . date('Ymd') . '-';
        $lastOrder = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }
}
