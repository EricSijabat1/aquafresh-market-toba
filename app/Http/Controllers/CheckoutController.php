<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Validasi cart data dari request
        $cartData = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|numeric',
            'items.*.quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0'
        ]);

        // Verifikasi bahwa semua produk masih ada dan stok mencukupi
        $validatedItems = [];
        $calculatedTotal = 0;

        foreach ($cartData['items'] as $item) {
            $product = Product::find($item['id']);

            if (!$product) {
                return redirect()->route('products.index')
                    ->with('error', "Produk {$item['name']} tidak ditemukan.");
            }

            if ($product->stock < $item['quantity']) {
                return redirect()->route('products.index')
                    ->with('error', "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}");
            }

            $validatedItems[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $item['quantity'],
                'image' => $product->image,
                'subtotal' => $product->price * $item['quantity']
            ];

            $calculatedTotal += $product->price * $item['quantity'];
        }

        return view('checkout.index', [
            'cartItems' => $validatedItems,
            'total' => $calculatedTotal
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'whatsapp' => 'required|string|min:10|max:20',
            'address' => 'required|string|min:10',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|min:10|max:20',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cod,qris',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ], [
            'name.required' => 'Nama lengkap harus diisi',
            'name.min' => 'Nama minimal 3 karakter',
            'whatsapp.required' => 'Nomor WhatsApp harus diisi',
            'whatsapp.min' => 'Nomor WhatsApp minimal 10 digit',
            'address.required' => 'Alamat lengkap harus diisi',
            'address.min' => 'Alamat minimal 10 karakter',
            'email.email' => 'Format email tidak valid',
            'payment_method.required' => 'Metode pembayaran harus dipilih'
        ]);

        try {
            $order = DB::transaction(function () use ($request) {
                // Buat atau update customer
                $customer = Customer::updateOrCreate(
                    ['whatsapp' => $request->whatsapp],
                    [
                        'name' => $request->name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'address' => $request->address,
                    ]
                );

                // Hitung total dari database untuk memastikan akurasi
                $totalAmount = 0;
                $orderItems = [];

                foreach ($request->items as $item) {
                    $product = Product::findOrFail($item['id']);

                    // Cek stok
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi");
                    }

                    $subtotal = $product->price * $item['quantity'];
                    $totalAmount += $subtotal;

                    $orderItems[] = [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $subtotal
                    ];
                }

                // Buat order
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'order_number' => Order::generateOrderNumber(),
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'notes' => $request->notes,
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                ]);

                // Buat order items dan kurangi stok
                foreach ($orderItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    // Kurangi stok produk
                    $item['product']->decrement('stock', $item['quantity']);
                }

                return $order;
            });

            // Kirim pesan WhatsApp
            $this->sendWhatsAppMessage($order);

            // Redirect ke halaman success
            return redirect()->route('orders.success', $order)
                ->with('success', 'Pesanan berhasil dibuat! Kami akan menghubungi Anda segera.');
        } catch (\Exception $e) {
            Log::error('Order submission failed: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    private function sendWhatsAppMessage($order)
    {
        try {
            $adminWhatsApp = env('ADMIN_WHATSAPP_NUMBER', '6281959243545');

            $message = "*PESANAN BARU - AquaFresh Market*\n\n";
            $message .= "📝 *Nomor Pesanan:* {$order->order_number}\n";
            $message .= "👤 *Nama:* {$order->customer->name}\n";
            $message .= "📱 *WhatsApp:* {$order->customer->whatsapp}\n";
            $message .= "📧 *Email:* " . ($order->customer->email ?: 'Tidak ada') . "\n";
            $message .= "📍 *Alamat:* {$order->customer->address}\n\n";

            $message .= "*DETAIL PRODUK:*\n";
            foreach ($order->orderItems as $item) {
                $message .= "• {$item->product->name} - {$item->quantity} pcs\n";
                $message .= "  Harga: Rp " . number_format($item->price, 0, ',', '.') . "\n";
                $message .= "  Subtotal: Rp " . number_format($item->subtotal, 0, ',', '.') . "\n\n";
            }

            $message .= "💰 *TOTAL: Rp " . number_format($order->total_amount, 0, ',', '.') . "*\n";
            $message .= "💳 *Metode Pembayaran:* " . strtoupper($order->payment_method) . "\n";

            if ($order->notes) {
                $message .= "📝 *Catatan:* {$order->notes}\n";
            }

            $message .= "\n⏰ *Waktu Pesanan:* " . $order->created_at->format('d/m/Y H:i') . "\n";

            $whatsappUrl = "https://wa.me/{$adminWhatsApp}?text=" . urlencode($message);

            // Log URL untuk debugging
            Log::info('WhatsApp URL generated: ' . $whatsappUrl);
        } catch (\Exception $e) {
            Log::error('Failed to generate WhatsApp message: ' . $e->getMessage());
        }
    }
}
