<?php

namespace App\Livewire;

use App\Models\CartItem;
use Livewire\Component;
use Livewire\Attributes\On;

class CartComponent extends Component
{
    public $cartCount = 0;

    public function mount()
    {
        $this->updateCartCount();
    }

    // Dijalankan ketika ada event 'addToCart'
    #[On('addToCart')]
    public function addToCart($productId)
    {
        $sessionId = session()->getId();

        // Cek apakah produk sudah ada di keranjang untuk sesi ini
        $cartItem = CartItem::where('session_id', $sessionId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Jika sudah ada, tambah quantity
            $cartItem->increment('quantity');
        } else {
            // Jika belum ada, buat item baru
            CartItem::create([
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }

        // Update jumlah item di keranjang
        $this->updateCartCount();

        // Kirim event notifikasi
        $this->dispatch('notify', ['message' => 'Produk ditambahkan ke keranjang!']);
    }

    #[On('cart-updated')] // Listener untuk update dirinya sendiri
    public function updateCartCount()
    {
        $this->cartCount = CartItem::where('session_id', session()->getId())->sum('quantity');
    }

    public function render()
    {
        return view('livewire.cart-component');
    }
}
