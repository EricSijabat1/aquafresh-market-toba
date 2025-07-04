<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\CartItem;

class CartServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Bagikan data 'cartCount' ke semua view
        View::composer('*', function ($view) {
            $sessionId = session()->getId();
            $cartCount = CartItem::where('session_id', $sessionId)->sum('quantity');
            $view->with('cartCount', $cartCount);
        });
    }
}
