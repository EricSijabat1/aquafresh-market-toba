@extends('layouts.app')

@section('title', 'Checkout - AquaFresh Market')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 pt-24">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="mb-4">
                    <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                        <i class="fas fa-lock mr-2"></i>
                        Checkout Aman
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    Konfirmasi <span class="text-teal-600">Pesanan</span> Anda
                </h1>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                    Lengkapi data Anda untuk menyelesaikan pemesanan produk segar berkualitas
                </p>
            </div>

            <div class="max-w-4xl mx-auto mb-12">
                <div class="flex items-center justify-center space-x-4">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-teal-600 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <span class="ml-2 text-sm font-medium text-teal-600">Keranjang</span>
                    </div>
                    <div class="w-16 h-0.5 bg-teal-600"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-teal-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">2</span>
                        </div>
                        <span class="ml-2 text-sm font-medium text-teal-600">Checkout</span>
                    </div>
                    <div class="w-16 h-0.5 bg-gray-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                            <span class="text-gray-600 text-sm font-bold">3</span>
                        </div>
                        <span class="ml-2 text-sm font-medium text-gray-500">Selesai</span>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="max-w-4xl mx-auto mb-8">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-green-800 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="max-w-4xl mx-auto mb-8">
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('checkout.store') }}" method="POST" class="max-w-6xl mx-auto">
                @csrf

                {{-- Input tersembunyi untuk item keranjang sudah tidak diperlukan lagi --}}
                {{-- Controller akan mengambil data langsung dari session/database --}}

                <div class="grid lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                                <h2 class="text-xl font-semibold ml-3 text-gray-900">Data Pelanggan</h2>
                            </div>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors @error('name') border-red-500 @enderror"
                                        placeholder="Masukkan nama lengkap">
                                    @error('name')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp <span
                                            class="text-red-500">*</span></label>
                                    <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors @error('whatsapp') border-red-500 @enderror"
                                        placeholder="08123456789">
                                    @error('whatsapp')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email (Opsional)</label>
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors @error('email') border-red-500 @enderror"
                                        placeholder="nama@email.com">
                                    @error('email')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Telepon (Opsional)</label>
                                    <input type="tel" name="phone" value="{{ old('phone') }}"
                                        class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors @error('phone') border-red-500 @enderror"
                                        placeholder="021-1234567">
                                    @error('phone')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap <span
                                        class="text-red-500">*</span></label>
                                <textarea name="address" rows="3"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors @error('address') border-red-500 @enderror"
                                    placeholder="Masukkan alamat lengkap untuk pengiriman">{{ old('address') }}</textarea>
                                @error('address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-credit-card text-white"></i>
                                </div>
                                <h2 class="text-xl font-semibold ml-3 text-gray-900">Metode Pembayaran</h2>
                            </div>
                            <div class="space-y-3">
                                <label
                                    class="group flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-teal-300 hover:bg-teal-50 transition-colors duration-200">
                                    <input type="radio" name="payment_method" value="cod"
                                        {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}
                                        class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300">
                                    <div class="ml-4 flex items-center">
                                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-hand-holding-usd text-orange-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">Bayar di Tempat (COD)</div>
                                            <div class="text-sm text-gray-500">Bayar ketika pesanan sampai ke tujuan</div>
                                        </div>
                                    </div>
                                </label>
                                <label
                                    class="group flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-teal-300 hover:bg-teal-50 transition-colors duration-200">
                                    <input type="radio" name="payment_method" value="qris"
                                        {{ old('payment_method') == 'qris' ? 'checked' : '' }}
                                        class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300">
                                    <div class="ml-4 flex items-center">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-qrcode text-blue-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">QRIS</div>
                                            <div class="text-sm text-gray-500">Scan QR code untuk pembayaran instan</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('payment_method')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-white rounded-2xl shadow-lg p-6">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-sticky-note text-white"></i>
                                </div>
                                <h2 class="text-xl font-semibold ml-3 text-gray-900">Catatan Tambahan</h2>
                            </div>
                            <textarea name="notes" rows="3"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors @error('notes') border-red-500 @enderror"
                                placeholder="Tambahkan catatan khusus jika diperlukan (opsional)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                            <div class="flex items-center mb-6">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-receipt text-white"></i>
                                </div>
                                <h2 class="text-xl font-semibold ml-3 text-gray-900">Ringkasan Pesanan</h2>
                            </div>
                            <div class="space-y-4 max-h-80 overflow-y-auto mb-6 pr-2">
                                @foreach ($cartItems as $item)
                                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                                        <div
                                            class="w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 rounded-xl overflow-hidden">
                                            @if ($item['image'])
                                                <img src="{{ asset('storage/' . $item['image']) }}"
                                                    alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <i class="fas fa-fish text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 text-sm">{{ $item['name'] }}</h4>
                                            <p class="text-sm text-gray-600">{{ $item['quantity'] }} x Rp
                                                {{ number_format($item['price'], 0, ',', '.') }}</p>
                                        </div>
                                        <p class="font-semibold text-teal-600 text-sm">
                                            Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t pt-4 space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Subtotal</span>
                                    <span class="font-semibold text-gray-900">Rp
                                        {{ number_format($total, 0, ',', '.') }}</span>
                                </div>

                                <div class="flex justify-between items-center pt-3 border-t">
                                    <span class="text-lg font-bold text-gray-900">Total</span>
                                    <span class="text-2xl font-bold text-teal-600">Rp
                                        {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- BAGIAN YANG DILENGKAPI --}}
                            <div class="mt-8 space-y-4">
                                <button type="submit"
                                    class="w-full bg-teal-600 text-white py-4 px-4 rounded-xl font-semibold text-lg hover:bg-teal-700 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-teal-300">
                                    <i class="fas fa-shield-alt mr-2"></i>
                                    Konfirmasi & Pesan Sekarang
                                </button>
                                <a href="{{ route('cart.index') }}"
                                    class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-xl font-semibold hover:bg-gray-200 transition-colors text-center block">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Kembali ke Keranjang
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
