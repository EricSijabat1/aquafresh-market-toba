<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $sessionId = session()->getId();
        $cartItemsData = CartItem::with('product')->where('session_id', $sessionId)->get();

        $total = $cartItemsData->sum(function ($item) {
            if ($item->product) {
                return $item->product->price * $item->quantity;
            }
            return 0;
        });

        return view('cart.index', ['cartItems' => $cartItemsData, 'total' => $total]);
    }

    public function update(Request $request, $cartItemId)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $cartItem = CartItem::where('id', $cartItemId)->where('session_id', session()->getId())->firstOrFail();

        // Cek stok sebelum update
        if ($cartItem->product->stock < $request->quantity) {
            return redirect()->route('cart.index')->with('error', "Stok {$cartItem->product->name} tidak mencukupi.");
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index')->with('success', 'Jumlah produk diperbarui.');
    }

    public function remove($cartItemId)
    {
        $cartItem = CartItem::where('id', $cartItemId)->where('session_id', session()->getId())->firstOrFail();
        $cartItem->delete();

        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }
}
