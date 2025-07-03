<?php

// app/Http/Controllers/OrderController.php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        
        DB::transaction(function () use ($request) {
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
            
            // Create order
            $order = Order::create([
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
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);
                
                $totalAmount += $subtotal;
            }
            
            // Update order total
            $order->update(['total_amount' => $totalAmount]);
            
            $this->order = $order;
        });
        
        return redirect()->route('orders.success', $this->order)
            ->with('success', 'Pesanan berhasil dibuat! Kami akan menghubungi Anda segera.');
    }
    
    public function success(Order $order)
    {
        return view('orders.success', compact('order'));
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
