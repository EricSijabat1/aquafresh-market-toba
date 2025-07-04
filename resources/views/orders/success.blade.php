{{-- resources/views/orders/success.blade.php --}}
@extends('layouts.app')

@section('title', 'Pesanan Berhasil - AquaFresh Market')

@section('content')
    <div class="min-h-screen bg-gray-50 py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto">
                <!-- Success Icon -->
                <div class="text-center mb-8">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check text-3xl text-green-600"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Pesanan Berhasil!</h1>
                    <p class="text-gray-600">Terima kasih telah memesan di AquaFresh Market</p>
                </div>

                <!-- Order Details -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Pesanan</h2>

                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">Nomor Pesanan</p>
                            <p class="font-bold text-lg">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Pesanan</p>
                            <p class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Status</p>
                            <span
                                class="inline-block px-3 py-1 text-sm font-medium rounded-full 
                               {{ $order->status === 'pending'
                                   ? 'bg-yellow-100 text-yellow-800'
                                   : ($order->status === 'confirmed'
                                       ? 'bg-blue-100 text-blue-800'
                                       : ($order->status === 'delivered'
                                           ? 'bg-green-100 text-green-800'
                                           : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Pembayaran</p>
                            <p class="font-bold text-lg text-green-600">Rp
                                {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="border-t pt-4 mb-6">
                        <h3 class="font-bold text-lg mb-3">Informasi Pelanggan</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Nama</p>
                                <p class="font-medium">{{ $order->customer->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">WhatsApp</p>
                                <p class="font-medium">{{ $order->customer->whatsapp }}</p>
                            </div>
                            @if ($order->customer->email)
                                <div>
                                    <p class="text-sm text-gray-600">Email</p>
                                    <p class="font-medium">{{ $order->customer->email }}</p>
                                </div>
                            @endif
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-600">Alamat</p>
                                <p class="font-medium">{{ $order->customer->address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="border-t pt-4">
                        <h3 class="font-bold text-lg mb-3">Produk Pesanan</h3>
                        <div class="space-y-3">
                            @foreach ($order->orderItems as $item)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        @if ($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                alt="{{ $item->product->name }}"
                                                class="w-12 h-12 object-cover rounded mr-3">
                                        @else
                                            <div
                                                class="w-12 h-12 bg-gray-300 rounded flex items-center justify-center mr-3">
                                                <i class="fas fa-fish text-gray-500"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium">{{ $item->product->name }}</p>
                                            <p class="text-sm text-gray-600">{{ $item->quantity }} x Rp
                                                {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="bg-blue-50 rounded-lg p-6 mb-6">
                    <h3 class="font-bold text-lg mb-3 text-blue-800">Langkah Selanjutnya</h3>
                    <div class="space-y-2 text-blue-700">
                        <p><i class="fas fa-check-circle mr-2"></i>Pesanan Anda telah diterima</p>
                        <p><i class="fas fa-phone mr-2"></i>Tim kami akan menghubungi Anda via WhatsApp untuk konfirmasi</p>
                        <p><i class="fas fa-truck mr-2"></i>Produk akan disiapkan dan dikirim sesuai jadwal</p>
                        @if ($order->payment_method === 'qris')
                            <p><i class="fas fa-qrcode mr-2"></i>Instruksi pembayaran QRIS akan dikirim via WhatsApp</p>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4">
                    <a href="{{ route('home') }}"
                        class="flex-1 bg-gray-600 text-white py-3 rounded-lg font-medium text-center hover:bg-gray-700 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Kembali ke Beranda
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-medium text-center hover:bg-blue-700 transition-colors">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Belanja Lagi
                    </a>
                </div>

                <!-- WhatsApp Contact -->
                <div class="text-center mt-8">
                    <p class="text-gray-600 mb-4">Butuh bantuan? Hubungi kami langsung</p>
                    <a href="https://wa.me/6281959243545"
                        class="inline-flex items-center bg-green-600 text-white px-6 py-3 rounded-lg font-medium hover:bg-green-700 transition-colors">
                        <i class="fab fa-whatsapp mr-2"></i>
                        Hubungi via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
