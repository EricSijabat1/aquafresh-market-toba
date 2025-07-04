<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    // app/Models/Order.php
    protected $fillable = [
        'customer_id',
        'order_number',
        'total_amount',
        'status',
        'notes',
        'payment_method',
        'payment_status',
        'snap_token', // <-- TAMBAHKAN INI
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


    public static function generateOrderNumber()
    {
        $prefix = 'AF'; // AquaFresh
        $date = date('ymd'); // Format: YYMMDD

        // Cari order terakhir hari ini
        $lastOrder = static::where('order_number', 'like', $prefix . $date . '%')
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            // Ambil nomor urut terakhir dan tambahkan 1
            $lastNumber = (int) substr($lastOrder->order_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            // Jika belum ada order hari ini, mulai dari 1
            $newNumber = 1;
        }

        // Format nomor urut menjadi 4 digit
        $sequence = str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return $prefix . $date . $sequence;
    }
}
