<?php

namespace App\Livewire;

use App\Models\CartItem;
use Livewire\Component;
use Livewire\Attributes\On;

class CartCounter extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        $this->updateCartCount();
    }

    // Ini adalah "listener" yang akan dijalankan ketika
    // event 'cart-updated' diterima dari komponen lain.
    #[On('cart-updated')]
    public function updateCartCount()
    {
        // Menghitung total item dari DB berdasarkan session ID
        $this->cartCount = CartItem::where('session_id', session()->getId())->sum('quantity');
    }

    public function render()
    {
        return view('livewire.cart-counter');
    }
}
