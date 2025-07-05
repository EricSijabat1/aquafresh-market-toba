<?php

namespace App\Livewire;

use App\Models\CartItem;
use Livewire\Component;
use Livewire\Attributes\Title; // <-- 1. TAMBAHKAN USE STATEMENT INI

class CartPage extends Component
{
    public $cartItems;
    public $total = 0;

    // ... (method mount, loadCart, calculateTotal, updateQuantity, removeItem Anda sudah benar)
    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cartItems = CartItem::with('product')
            ->where('session_id', session()->getId())
            ->get();

        $this->calculateTotal();
    }

    private function calculateTotal()
    {
        $this->total = $this->cartItems->sum(function ($item) {
            return $item->product ? $item->product->price * $item->quantity : 0;
        });
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        $quantity = max(1, $quantity);

        $cartItem = $this->cartItems->find($cartItemId);

        if ($cartItem) {
            if ($cartItem->product->stock < $quantity) {
                $this->dispatch('notify', message: "Stok {$cartItem->product->name} tidak mencukupi!");
                return;
            }

            $cartItem->update(['quantity' => $quantity]);
            $this->loadCart();
            $this->dispatch('cart-updated');
        }
    }

    public function removeItem($cartItemId)
    {
        CartItem::where('id', $cartItemId)
            ->where('session_id', session()->getId())
            ->delete();

        $this->loadCart();
        $this->dispatch('cart-updated');
        $this->dispatch('notify', message: 'Produk berhasil dihapus dari keranjang.');
    }


    // 2. TAMBAHKAN ATRIBUT #[Title] DI SINI
    #[Title('Keranjang Belanja - AquaFresh Market')]
    public function render()
    {
        // 3. HAPUS ->title() DARI SINI
        return view('livewire.cart-page');
    }
}
