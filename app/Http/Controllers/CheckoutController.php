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

    public function __construct(OrderService $orderService) // <-- Inject service
    {
        $this->orderService = $orderService;
    }

    /**
     * Menampilkan halaman checkout dengan data dari keranjang.
     * Method ini dipanggil via POST dari JavaScript dengan data cart.
     */
    public function index()
    {
        $sessionId = session()->getId();
        $cartData = CartItem::with('product')->where('session_id', $sessionId)->get();

        if ($cartData->isEmpty()) {
            return redirect()->route('home')->with('error', 'Keranjang Anda kosong!');
        }

        $cartItems = [];
        $total = 0;

        foreach ($cartData as $item) {
            // Cek stok
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('home') // atau ke halaman keranjang nanti
                    ->with('error', "Stok produk {$item->product->name} tidak mencukupi.");
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

    /**
     * Menampilkan halaman checkout untuk GET request.
     * Biasanya digunakan ketika user mengakses URL langsung.
     */
    public function show(Request $request)
    {
        // Jika ada session cart data, gunakan itu
        if ($request->session()->has('checkout_cart')) {
            $cartData = $request->session()->get('checkout_cart');

            $cartItems = [];
            $total = 0;

            foreach ($cartData['items'] as $item) {
                $product = Product::find($item['id']);

                if (!$product) {
                    continue; // Skip jika produk tidak ditemukan
                }

                $subtotal = $product->price * $item['quantity'];
                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'image' => $product->image,
                    'subtotal' => $subtotal,
                ];
                $total += $subtotal;
            }

            return view('checkout.index', compact('cartItems', 'total'));
        }

        // Jika tidak ada data cart, redirect ke halaman produk
        return redirect()->route('products.index')
            ->with('error', 'Silakan pilih produk terlebih dahulu.');
    }

    /**
     * Menyimpan data pesanan dan memproses pembayaran.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
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
        ]);

        try {
            $order = $this->orderService->createOrder($validatedData);

            $this->sendWhatsAppMessage($order);

            // Clear checkout session data
            $request->session()->forget('checkout_cart');

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
