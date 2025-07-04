<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Snap;

class OrderService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $customer = Customer::updateOrCreate(
                ['whatsapp' => $data['whatsapp']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                ]
            );

            $totalAmount = 0;
            foreach ($data['items'] as $item) {
                $product = Product::find($item['id']);
                $totalAmount += $product->price * $item['quantity'];
            }

            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $data['notes'],
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['id']);
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }

            if ($order->payment_method === 'qris') {
                $snapToken = $this->getSnapToken($order, $customer);
                $order->snap_token = $snapToken;
                $order->save();
            }

            return $order;
        });
    }

    private function getSnapToken(Order $order, Customer $customer): string
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $customer->name,
                'email' => $customer->email,
                'phone' => $customer->whatsapp,
            ]
        ];

        return Snap::getSnapToken($params);
    }
}
