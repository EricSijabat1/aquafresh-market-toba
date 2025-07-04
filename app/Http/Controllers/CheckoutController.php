<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    /**
     * Mengatur konfigurasi Midtrans setiap kali controller ini digunakan.
     */
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index() // Method ini untuk menampilkan halaman checkout
    {
        $cartItemsData = CartItem::with('product')->where('session_id', session()->getId())->get();

        if ($cartItemsData->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong!');
        }

        $cartItems = [];
        $total = 0;

        foreach ($cartItemsData as $item) {
            if (!$item->product) continue; // Skip jika produk terhapus

            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Stok produk {$item->product->name} tidak mencukupi. Sisa stok: {$item->product->stock}.");
            }

            $subtotal = $item->product->price * $item->quantity;
            $cartItems[] = [
                'id' => $item->product->id,
                'name' => $item->product->name,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
                'image' => $item->product->image,
                'subtotal' => $subtotal,
            ];
            $total += $subtotal;
        }

        return view('checkout.index', compact('cartItems', 'total'));
    }

    public function store(Request $request) // Method ini untuk memproses pesanan
    {
        $validatedData = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'whatsapp' => 'required|string|min:10|max:20',
            'address' => 'required|string|min:10',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|min:10|max:20',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:cod,qris',
        ]);

        $cartItems = CartItem::where('session_id', session()->getId())->get();
        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Sesi checkout berakhir. Silakan coba lagi.');
        }

        // Tambahkan item keranjang ke data yang divalidasi
        $validatedData['items'] = $cartItems->map(function ($item) {
            return ['id' => $item->product_id, 'quantity' => $item->quantity];
        })->toArray();

        try {
            $order = $this->orderService->createOrder($validatedData);

            // Kosongkan keranjang setelah order berhasil
            CartItem::where('session_id', session()->getId())->delete();

            $this->sendWhatsAppMessage($order); // Fungsi ini sudah bagus

            // Redirect ke halaman sukses
            return redirect()->route('orders.success', $order)
                ->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            Log::error('Order submission failed: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Mengirim notifikasi pesanan baru ke WhatsApp Admin.
     */
    private function sendWhatsAppMessage($order)
    {
        try {
            $adminWhatsApp = env('ADMIN_WHATSAPP_NUMBER', '6281959243545');
            $message = "*PESANAN BARU - AquaFresh Market*\n\n"
                . "📝 *Nomor Pesanan:* {$order->order_number}\n"
                . "👤 *Nama:* {$order->customer->name}\n"
                . "📱 *WhatsApp:* {$order->customer->whatsapp}\n"
                . "📍 *Alamat:* {$order->customer->address}\n\n"
                . "*DETAIL PRODUK:*\n";

            foreach ($order->orderItems as $item) {
                $message .= "• {$item->product->name} ({$item->quantity}x) - Rp " . number_format($item->subtotal, 0, ',', '.') . "\n";
            }

            $message .= "\n💰 *TOTAL: Rp " . number_format($order->total_amount, 0, ',', '.') . "*\n"
                . "💳 *Pembayaran:* " . strtoupper($order->payment_method) . "\n"
                . ($order->notes ? "📝 *Catatan:* {$order->notes}\n" : "");

            // Untuk saat ini, kita hanya log URL-nya untuk debugging
            $whatsappUrl = "https://wa.me/{$adminWhatsApp}?text=" . urlencode($message);
            Log::info('WhatsApp URL generated: ' . $whatsappUrl);
        } catch (\Exception $e) {
            Log::error('Failed to generate WhatsApp message: ' . $e->getMessage());
        }
    }
}
