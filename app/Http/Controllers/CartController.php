<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $sessionId = session()->getId();
        $productId = $request->product_id;

        $cartItem = CartItem::where('session_id', $sessionId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function index()
    {
        $sessionId = session()->getId();
        $cartItemsData = CartItem::with('product')->where('session_id', $sessionId)->get();

        $total = $cartItemsData->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', ['cartItems' => $cartItemsData, 'total' => $total]);
    }
}
