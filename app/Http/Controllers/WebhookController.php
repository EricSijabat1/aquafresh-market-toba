<?php
// app/Http/Controllers/WebhookController.php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        $order = Order::where('order_number', $order_id)->first();

        if ($transaction == 'capture' || $transaction == 'settlement') {
            // Jika transaksi berhasil
            if ($fraud == 'accept') {
                // Update status pembayaran di database Anda
                $order->update(['payment_status' => 'paid']);
            }
        } else if ($transaction == 'expire') {
            $order->update(['payment_status' => 'expired']);
        }

        return response()->json(['status' => 'ok']);
    }
}
