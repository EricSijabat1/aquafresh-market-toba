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

    protected $rules = [
        'name' => 'required|string|min:3|max:255',
        'whatsapp' => 'required|string|min:10|max:20',
        'address' => 'required|string|min:10',
        'email' => 'nullable|email|max:255',
        'phone' => 'nullable|string|min:10|max:20',
        'notes' => 'nullable|string|max:500'
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
            // Jika keranjang kosong, kita bisa menampilkan notifikasi (opsional)
            // Untuk saat ini, kita tidak akan membuka modal jika keranjang kosong.
        }
    }

    public function closeCheckout()
    {
        $this->showCheckout = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'phone', 'whatsapp', 'address', 'notes']);
        $this->resetErrorBag();
    }

    public function submitOrder()
    {
        $this->validate();

        if (empty($this->cartItems)) {
            // Menambahkan flash message jika keranjang kosong
            session()->flash('error', 'Keranjang belanja Anda kosong. Silakan tambahkan produk terlebih dahulu.');
            $this->closeCheckout();
            return;
        }

        try {
            $order = DB::transaction(function () {
                // Buat atau temukan pelanggan berdasarkan nomor WhatsApp
                $customer = Customer::updateOrCreate(
                    ['whatsapp' => $this->whatsapp],
                    [
                        'name' => $this->name,
                        'email' => $this->email,
                        'phone' => $this->phone,
                        'address' => $this->address,
                    ]
                );

                // Buat pesanan
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'order_number' => Order::generateOrderNumber(),
                    'total_amount' => $this->total,
                    'status' => 'pending',
                    'notes' => $this->notes,
                ]);

                // Buat item pesanan
                foreach ($this->cartItems as $item) {
                    $product = Product::find($item['id']);
                    if ($product) {
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
                }

                return $order;
            });

            // Kirim pesan WhatsApp (opsional)
            $this->sendWhatsAppMessage($order);

            // Tutup modal dan emit event untuk mengosongkan keranjang di AlpineJS
            $this->closeCheckout();
            $this->dispatch('order-placed');

            // Redirect ke halaman sukses
            return redirect()->route('orders.success', ['order' => $order->id]);
        } catch (\Exception $e) {
            // Jika terjadi error, kirim notifikasi
            Log::error('Order submission failed: ' . $e->getMessage());
            $this->addError('general', 'Gagal memproses pesanan. Silakan coba lagi.');
        }
    }

    private function sendWhatsAppMessage($order)
    {
        // ... (logika pengiriman pesan WhatsApp Anda tetap sama)
        // Saran: Sebaiknya nomor admin disimpan di file .env
        $adminWhatsApp = env('ADMIN_WHATSAPP_NUMBER', '6281234567890'); // Ganti dengan nomor asli
        // ...
    }

    public function render()
    {
        return view('livewire.checkout-component');
    }
}
