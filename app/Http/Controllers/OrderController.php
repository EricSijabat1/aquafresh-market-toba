<?php

// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20',
            'address' => 'required|string',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // 1. Tampung hasil dari transaksi ke dalam variabel $order
        $order = DB::transaction(function () use ($request) {
            // Create or update customer
            $customer = Customer::updateOrCreate(
                ['whatsapp' => $request->whatsapp],
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]
            );

            // Create order (gunakan nama variabel sementara jika perlu)
            $createdOrder = Order::create([
                'customer_id' => $customer->id,
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => 0,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            $totalAmount = 0;

            // Create order items
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $createdOrder->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $totalAmount += $subtotal;
            }

            // Update order total
            $createdOrder->update(['total_amount' => $totalAmount]);

            // 2. Kembalikan objek order yang sudah lengkap dari dalam transaksi
            return $createdOrder;
        });

        // 3. Sekarang variabel $order di sini berisi data dari $createdOrder
        return redirect()->route('orders.success', $order)
            ->with('success', 'Pesanan berhasil dibuat! Kami akan menghubungi Anda segera.');
    }

    public function success(Order $order)
    {
        $qrCode = null;

        // Jika metode pembayaran adalah COD, buat QR Code
        if ($order->payment_method === 'cod') {
            // Data yang akan di-encode di dalam QR Code adalah nomor pesanan
            $qrCodeData = $order->order_number;
            $qrCode = QrCode::size(250)->generate($qrCodeData);
        }

        // Kirim data order dan QR Code (jika ada) ke view
        return view('orders.success', compact('order', 'qrCode'));
    }

    public function history($whatsapp)
    {
        $customer = Customer::where('whatsapp', $whatsapp)->first();

        if (!$customer) {
            return redirect()->route('home')->with('error', 'Riwayat pesanan tidak ditemukan.');
        }

        $orders = $customer->orders()->with('orderItems.product')->latest()->paginate(10);

        return view('orders.history', compact('customer', 'orders'));
    }
}
