<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('customer')->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('customer', 'orderItems.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled']);
        
        $order->update(['status' => $request->status]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Status pesanan berhasil diperbarui.');
    }
}
