<?php

namespace App\Livewire;

use App\Models\CartItem;
use App\Models\Product; // <-- TAMBAHKAN use statement ini
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

        $product = Product::find($this->productId); // Ambil data produk

        // Mengirim event 'cart-updated' yang akan ditangkap oleh CartCounter
        $this->dispatch('cart-updated');

        // Mengirim event untuk notifikasi dengan format yang benar
        $this->dispatch('notify', message: "'{$product->name}' berhasil ditambahkan ke keranjang!");
    }

    public function render()
    {
        return view('livewire.add-to-cart-button');
    }
}
