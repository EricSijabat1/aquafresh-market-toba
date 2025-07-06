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

    /**
     * Menampilkan form untuk mengedit pesanan.
     */
    public function edit(Order $order)
    {
        // Anda bisa membuat view 'admin.orders.edit' jika diperlukan
        // Untuk saat ini, kita arahkan ke halaman detail saja
        return view('admin.orders.show', compact('order'))->with('info', 'Halaman edit sedang dalam pengembangan. Gunakan "Ubah Status" untuk saat ini.');
    }


    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled']);
        
        $order->update(['status' => $request->status]);

        // Respon JSON untuk AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diperbarui.',
                'order' => $order->fresh() // Kirim data order terbaru
            ]);
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Menghasilkan tampilan invoice untuk dicetak.
     */
    public function invoice(Order $order)
    {
        $order->load('customer', 'orderItems.product');
        // Anda bisa membuat view khusus untuk invoice jika ingin tampilan cetak yang lebih baik
        return view('admin.orders.show', compact('order'));
    }
}