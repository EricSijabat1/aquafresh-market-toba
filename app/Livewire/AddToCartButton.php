<?php

namespace App\Livewire;

use App\Models\CartItem;
use Livewire\Component;

class AddToCartButton extends Component
{
    public $productId;

    public function mount($productId)
    {
        $this->productId = $productId;
    }

    public function addToCart()
    {
        $sessionId = session()->getId();

        $cartItem = CartItem::where('session_id', $sessionId)
            ->where('product_id', $this->productId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'session_id' => $sessionId,
                'product_id' => $this->productId,
                'quantity' => 1,
            ]);
        }

        // Mengirim event 'cart-updated' yang akan ditangkap oleh CartCounter
        $this->dispatch('cart-updated');
        // Mengirim event untuk notifikasi
        $this->dispatch('notify', ['message' => 'Produk berhasil ditambahkan!']);
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}
