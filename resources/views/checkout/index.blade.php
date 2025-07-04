@extends('layouts.app')

@section('title', 'Checkout - AquaFresh Market')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8 pt-24">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">

                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Checkout Pesanan</h1>
                    <p class="text-gray-600">Lengkapi data Anda untuk menyelesaikan pesanan</p>
                </div>

                <!-- Alert Messages -->
                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('checkout.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Hidden cart items -->
                    @foreach ($cartItems as $index => $item)
                        <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item['id'] }}">
                        <input type="hidden" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}">
                    @endforeach

                    <div class="grid lg:grid-cols-2 gap-8">

                        <!-- Left Column - Form Data -->
                        <div class="space-y-6">

                            <!-- Customer Information -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h2 class="text-xl font-semibold mb-4 text-gray-900">Data Pelanggan</h2>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                                        <input type="text" name="name" value="{{ old('name') }}"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') @enderror"
                                            placeholder="Masukkan nama lengkap">
                                        @error('name')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp *</label>
                                        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('whatsapp')  @enderror"
                                            placeholder="Contoh: 08123456789">
                                        @error('whatsapp')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-4 mt-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email (Opsional)</label>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') @enderror"
                                            placeholder="nama@email.com">
                                        @error('email')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Telepon
                                            (Opsional)</label>
                                        <input type="tel" name="phone" value="{{ old('phone') }}"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') @enderror"
                                            placeholder="021-1234567">
                                        @error('phone')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap *</label>
                                    <textarea name="address" rows="3"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address')  @enderror"
                                        placeholder="Masukkan alamat lengkap untuk pengiriman">{{ old('address') }}</textarea>
                                    @error('address')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h2 class="text-xl font-semibold mb-4 text-gray-900">Metode Pembayaran</h2>

                                <div class="space-y-3">
                                    <label
                                        class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="payment_method" value="cod"
                                            {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <div class="ml-3">
                                            <div class="font-medium text-gray-900">Bayar di Tempat (COD)</div>
                                            <div class="text-sm text-gray-500">Bayar ketika pesanan sampai</div>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                        <input type="radio" name="payment_method" value="qris"
                                            {{ old('payment_method') == 'qris' ? 'checked' : '' }}
                                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <div class="ml-3">
                                            <div class="font-medium text-gray-900">QRIS</div>
                                            <div class="text-sm text-gray-500">Scan QR code untuk pembayaran</div>
                                        </div>
                                    </label>
                                </div>

                                @error('payment_method')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Notes -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                <h2 class="text-xl font-semibold mb-4 text-gray-900">Catatan Tambahan</h2>

                                <textarea name="notes" rows="3"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('notes')@enderror"
                                    placeholder="Tambahkan catatan khusus jika diperlukan (opsional)">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>

                        <!-- Right Column - Order Summary -->
                        <div class="space-y-6">

                            <!-- Order Summary -->
                            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                                <h2 class="text-xl font-semibold mb-4 text-gray-900">Ringkasan Pesanan</h2>

                                <div class="space-y-3 max-h-64 overflow-y-auto">
                                    @foreach ($cartItems as $item)
                                        <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-lg">
                                            @if ($item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}"
                                                    alt="{{ $item['name'] }}" class="w-16 h-16 object-cover rounded-lg">
                                            @else
                                                <div
                                                    class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-fish text-gray-400"></i>
                                                </div>
                                            @endif
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">{{ $item['name'] }}</h4>
                                                <p class="text-sm text-gray-600">{{ $item['quantity'] }} x Rp
                                                    {{ number_format($item['price'], 0, ',', '.') }}</p>
                                            </div>
                                            <p class="font-semibold text-green-600">Rp
                                                {{ number_format($item['subtotal'], 0, ',', '.') }}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="border-t pt-4 mt-4">
                                    <div class="flex justify-between items-center text-lg font-bold">
                                        <span class="text-gray-900">Total:</span>
                                        <span class="text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                <div class="mt-6 space-y-3">
                                    <button type="submit"
                                        class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition-colors">
                                        <i class="fas fa-check mr-2"></i>
                                        Konfirmasi Pesanan
                                    </button>

                                    <a href="{{ route('products.index') }}"
                                        class="w-full bg-gray-500 text-white py-3 px-4 rounded-lg font-semibold hover:bg-gray-600 transition-colors text-center block">
                                        <i class="fas fa-arrow-left mr-2"></i>
                                        Kembali Berbelanja
                                    </a>
                                </div>
                            </div>

                        </div>

                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
