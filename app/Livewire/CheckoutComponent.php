<?php
// app/Http/Livewire/CheckoutComponent.php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutComponent extends Component
{
    public $showCheckout = false;
    public $cartItems = [];
    public $total = 0;

    // Data pelanggan
    public $name = '';
    public $email = '';
    public $phone = '';
    public $whatsapp = '';
    public $address = '';
    public $notes = '';
    public $payment_method = 'cod';

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'whatsapp' => 'required|string|min:10|max:20',
        'address' => 'required|string|min:10',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|min:10|max:20',
        'notes' => 'nullable|string|max:500',
        'payment_method' => 'required|in:cod,qris'
    ];

    protected $messages = [
        'name.required' => 'Nama lengkap harus diisi',
        'name.min' => 'Nama minimal 3 karakter',
        'whatsapp.required' => 'Nomor WhatsApp harus diisi',
        'whatsapp.min' => 'Nomor WhatsApp minimal 10 digit',
        'address.required' => 'Alamat lengkap harus diisi',
        'address.min' => 'Alamat minimal 10 karakter',
        'email.email' => 'Format email tidak valid',
    ];

    // Listener untuk event 'open-checkout' dari Alpine.js
    protected $listeners = ['open-checkout' => 'openCheckout'];

    /**
     * Menerima data dari Alpine.js dan membuka modal.
     */
    public function openCheckout($cart)
    {
        // Pastikan data cart tidak kosong sebelum memproses
        if (!empty($cart['items'])) {
            $this->cartItems = $cart['items'];
            $this->total = $cart['total'];
            $this->showCheckout = true;
        } else {
            session()->flash('error', 'Keranjang belanja kosong');
        }
    }

    public function closeCheckout()
    {
        $this->showCheckout = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'phone', 'whatsapp', 'address', 'notes', 'payment_method']);
        $this->resetErrorBag();
    }

    public function submitOrder()
    {
        $this->validate();

        if (empty($this->cartItems)) {
            session()->flash('error', 'Keranjang belanja Anda kosong.');
            $this->closeCheckout();
            return;
        }

        try {
            $order = DB::transaction(function () {
                // Buat atau update customer
                $customer = Customer::updateOrCreate(
                    ['whatsapp' => $this->whatsapp],
                    [
                        'name' => $this->name,
                        'email' => $this->email,
                        'phone' => $this->phone,
                        'address' => $this->address,
                    ]
                );

                // Buat order
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'order_number' => Order::generateOrderNumber(),
                    'total_amount' => $this->total,
                    'status' => 'pending',
                    'notes' => $this->notes,
                    'payment_method' => $this->payment_method,
                    'payment_status' => 'pending',
                ]);

                // Buat order items dan kurangi stok
                foreach ($this->cartItems as $item) {
                    $product = Product::findOrFail($item['id']);

                    // Cek stok
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok {$product->name} tidak mencukupi");
                    }

                    // Buat order item
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $product->price * $item['quantity'],
                    ]);

                    // Kurangi stok produk
                    $product->decrement('stock', $item['quantity']);
                }

                return $order;
            });

            // Kirim pesan WhatsApp
            $this->sendWhatsAppMessage($order);

            // Tutup modal dan bersihkan keranjang
            $this->closeCheckout();
            $this->dispatch('order-placed');

            // Redirect ke halaman success
            return redirect()->route('orders.success', ['order' => $order->id]);
        } catch (\Exception $e) {
            Log::error('Order submission failed: ' . $e->getMessage());
            session()->flash('error', 'Gagal memproses pesanan: ' . $e->getMessage());
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
            $message .= "📧 *Email:* {$order->customer->email}\n";
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

    public function render()
    {
        return view('livewire.checkout-component');
    }
}
