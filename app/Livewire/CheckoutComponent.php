<?php
// app/Http/Livewire/CheckoutComponent.php
namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckoutComponent extends Component
{
    public $showCheckout = false;
    public $cartItems = [];
    public $total = 0;

    // Customer data
    public $name = '';
    public $email = '';
    public $phone = '';
    public $whatsapp = '';
    public $address = '';
    public $notes = '';

    protected $rules = [
        'name' => 'required|min:3',
        'whatsapp' => 'required|min:10',
        'address' => 'required|min:10',
        'email' => 'nullable|email',
        'phone' => 'nullable|min:10',
        'notes' => 'nullable|max:500'
    ];

    protected $listeners = ['open-checkout' => 'openCheckout'];

    public function openCheckout()
    {
        $this->showCheckout = true;
    }

    public function closeCheckout()
    {
        $this->showCheckout = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->whatsapp = '';
        $this->address = '';
        $this->notes = '';
    }

    public function mount()
    {
        $this->cartItems = session('cart', []);
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total = collect($this->cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function submitOrder()
    {
        $this->validate();

        if (empty($this->cartItems)) {
            session()->flash('error', 'Keranjang belanja kosong');
            return;
        }

        DB::transaction(function () {
            // Create or find customer
            $customer = Customer::where('whatsapp', $this->whatsapp)->first();

            if (!$customer) {
                $customer = Customer::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'whatsapp' => $this->whatsapp,
                    'address' => $this->address,
                ]);
            } else {
                // Update customer data
                $customer->update([
                    'name' => $this->name,
                    'email' => $this->email ?: $customer->email,
                    'phone' => $this->phone ?: $customer->phone,
                    'address' => $this->address,
                ]);
            }

            // Create order
            $order = Order::create([
                'customer_id' => $customer->id,
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => $this->total,
                'status' => 'pending',
                'notes' => $this->notes,
            ]);

            // Create order items
            foreach ($this->cartItems as $item) {
                $product = Product::find($item['id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $product->price * $item['quantity'],
                ]);

                // Update product stock
                $product->decrement('stock', $item['quantity']);
            }

            // Clear cart
            session()->forget('cart');
            $this->cartItems = [];

            // Send WhatsApp message (optional)
            $this->sendWhatsAppMessage($order);

            // Redirect to success page
            return redirect()->route('orders.success', $order->id);
        });
    }

    private function sendWhatsAppMessage($order)
    {
        $message = "Pesanan Baru AquaFresh Market\n\n";
        $message .= "No. Pesanan: {$order->order_number}\n";
        $message .= "Pelanggan: {$order->customer->name}\n";
        $message .= "WhatsApp: {$order->customer->whatsapp}\n";
        $message .= "Alamat: {$order->customer->address}\n\n";
        $message .= "Detail Pesanan:\n";

        foreach ($order->orderItems as $item) {
            $message .= "- {$item->product->name} x{$item->quantity} = Rp " . number_format($item->subtotal, 0, ',', '.') . "\n";
        }

        $message .= "\nTotal: Rp " . number_format($order->total_amount, 0, ',', '.') . "\n";

        if ($order->notes) {
            $message .= "\nCatatan: {$order->notes}\n";
        }

        $adminWhatsApp = '+6281234567890'; // Replace with actual admin WhatsApp
        $whatsappUrl = "https://wa.me/{$adminWhatsApp}?text=" . urlencode($message);

        // You can implement actual WhatsApp API integration here
        // For now, we'll just store the URL in session for redirect
        session()->flash('whatsapp_url', $whatsappUrl);
    }

    public function render()
    {
        return view('livewire.checkout-component');
    }
}
