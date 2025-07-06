@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">Item Pesanan</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-200 text-left text-sm">
                            <th class="py-2 px-4">Produk</th>
                            <th class="py-2 px-4">Kuantitas</th>
                            <th class="py-2 px-4">Harga Satuan</th>
                            <th class="py-2 px-4 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr class="border-b">
                            <td class="py-2 px-4 flex items-center">
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-12 h-12 object-cover rounded-md mr-4">
                                <span>{{ $item->product->name }}</span>
                            </td>
                            <td class="py-2 px-4">{{ $item->quantity }}</td>
                            <td class="py-2 px-4">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-2 px-4 text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="py-2 px-4 text-right font-bold">Total</td>
                            <td class="py-2 px-4 text-right font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div>
        <div class="bg-white p-6 rounded-lg shadow-md mb-6">
            <h3 class="text-xl font-bold mb-4">Detail Pesanan</h3>
            <p><strong>Nomor Pesanan:</strong> {{ $order->order_number }}</p>
            <p><strong>Tanggal:</strong> {{ $order->created_at->format('d F Y, H:i') }}</p>
            <p><strong>Status:</strong> <span class="font-semibold uppercase">{{ $order->status }}</span></p>
            <p><strong>Metode Pembayaran:</strong> <span class="font-semibold uppercase">{{ $order->payment_method }}</span></p>
            <p><strong>Status Pembayaran:</strong> <span class="font-semibold uppercase">{{ $order->payment_status }}</span></p>
            @if($order->notes)
                <p><strong>Catatan:</strong> {{ $order->notes }}</p>
            @endif
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">Detail Pelanggan</h3>
            <p><strong>Nama:</strong> {{ $order->customer->name }}</p>
            <p><strong>WhatsApp:</strong> {{ $order->customer->whatsapp }}</p>
            <p><strong>Email:</strong> {{ $order->customer->email ?? '-' }}</p>
            <p><strong>Alamat:</strong> {{ $order->customer->address }}</p>
        </div>
    </div>
</div>
@endsection
