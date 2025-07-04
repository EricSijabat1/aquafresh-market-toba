<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- TAMBAHKAN INI

class CartItem extends Model
{
    protected $fillable = ['session_id', 'product_id', 'quantity'];

    /**
     * Mendefinisikan bahwa setiap CartItem "milik" satu Product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
