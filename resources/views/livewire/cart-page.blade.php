<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-8 pt-24">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                Keranjang <span class="text-teal-600">Belanja</span> Anda
            </h1>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Tinjau pesanan Anda sebelum melanjutkan ke pembayaran
            </p>
        </div>

        @if (session('success'))
            {{-- Notifikasi dari redirect sebelumnya, bisa dihapus jika tidak ada lagi redirect --}}
        @endif

        @if ($cartItems->count() > 0)
            <div class="max-w-6xl mx-auto grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="p-6 bg-gradient-to-r from-teal-50 to-blue-50 border-b border-gray-100">
                            <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-list-ul mr-3 text-teal-600"></i>
                                Daftar Produk ({{ $cartItems->count() }} item)
                            </h2>
                        </div>

                        <div class="divide-y divide-gray-100">
                            @foreach ($cartItems as $item)
                                <div class="p-6">
                                    <div class="flex items-center space-x-6">
                                        <div
                                            class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl overflow-hidden shadow-md">
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                {{ $item->product->name }}</h3>
                                            <p class="text-lg font-bold text-teal-600">Rp
                                                {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                        </div>

                                        <div class="flex items-center space-x-5">
                                            <div class="flex items-center bg-gray-100 rounded-lg pr-3">
                                                <button
                                                    wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                                    class="p-2 hover:bg-gray-200 rounded-l-lg transition-colors">
                                                    <i class="fas fa-minus text-gray-600"></i>
                                                </button>
                                                <span
                                                    class="w-16 text-center border-0 bg-transparent focus:outline-none font-semibold">{{ $item->quantity }}</span>
                                                <button
                                                    wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                                    class="p-2 hover:bg-gray-200 rounded-r-lg transition-colors">
                                                    <i class="fas fa-plus text-gray-600"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-5">
                                            <p class="text-lg font-bold text-gray-900">Rp
                                                {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                            </p>
                                            <button wire:click="removeItem({{ $item->id }})"
                                                wire:confirm="Anda yakin ingin menghapus item ini?"
                                                class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">Ringkasan Pesanan</h2>
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span>Subtotal ({{ $cartItems->count() }} item)</span>
                                <span class="font-semibold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="border-t pt-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold">Total</span>
                                    <span class="text-2xl font-bold text-teal-600">Rp
                                        {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <a href="{{ route('checkout.index') }}"
                                class="w-full bg-gradient-to-r from-teal-600 to-teal-700 text-white py-4 px-6 rounded-xl font-bold text-center hover:from-teal-700 hover:to-teal-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center group">
                                <i class="fas fa-credit-card mr-2"></i>
                                Lanjutkan ke Pembayaran
                                <i class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                            </a>

                            <a href="{{ route('products.index') }}"
                                class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-xl font-semibold text-center hover:bg-gray-200 transition-colors flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Lanjutkan Belanja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="... text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Keranjang Belanja Kosong</h2>
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center gap-3 bg-gradient-to-r from-teal-600 to-teal-700 text-white px-8 py-4 rounded-xl font-bold text-lg hover:from-teal-700 hover:to-teal-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">Mulai
                    Belanja Sekarang</a>
            </div>
        @endif
    </div>
</div>
