<div>
    {{-- 
        Kita menggunakan x-data untuk mengontrol visibilitas modal di sisi klien (browser).
        @entangle menyinkronkan variabel 'show' di Alpine.js dengan '$showCheckout' di Livewire.
    --}}
    <div x-data="{ show: @entangle('showCheckout').live }"
         x-show="show"
         x-on:keydown.escape.window="show = false"
         class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4"
         style="display: none;">

        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] flex flex-col" @click.away="show = false">
            
            <div class="flex items-center justify-between p-4 md:p-6 border-b sticky top-0 bg-white rounded-t-lg">
                <h2 class="text-2xl font-bold text-gray-800">Checkout Pesanan</h2>
                <button @click="show = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            {{-- 
                Tag <form> sekarang membungkus semua konten dan tombol aksi.
                Konten di dalamnya dibuat bisa di-scroll dengan 'overflow-y-auto'.
            --}}
            <form wire:submit.prevent="submitOrder" class="flex-grow overflow-y-auto">
                <div class="p-4 md:p-6 space-y-6">
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-bold text-lg mb-3 text-gray-800">Ringkasan Pesanan</h3>
                        <div class="space-y-2 max-h-40 overflow-y-auto pr-2">
                            @forelse ($cartItems as $item)
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-medium text-gray-800">{{ $item['name'] }}</span>
                                        <span class="text-sm text-gray-600">({{ $item['quantity'] }}x)</span>
                                    </div>
                                    <span class="font-medium text-gray-800">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500">Keranjang kosong.</p>
                            @endforelse
                        </div>
                        <div class="border-t pt-2 mt-2">
                            <div class="flex justify-between items-center font-bold text-lg">
                                <span class="text-gray-900">Total:</span>
                                <span class="text-green-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-lg mb-3 text-gray-800">Data Pelanggan</h3>
                        <div class="space-y-4">
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                                    <input type="text" wire:model.defer="name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Masukkan nama lengkap">
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">WhatsApp *</label>
                                    <input type="tel" wire:model.defer="whatsapp" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Contoh: 08123456789">
                                    @error('whatsapp') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap *</label>
                                <textarea wire:model.defer="address" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Masukkan alamat lengkap untuk pengiriman"></textarea>
                                @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-lg mb-3 text-gray-800">Metode Pembayaran</h3>
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50" :class="{ 'border-blue-500 ring-2 ring-blue-200': '{{ $payment_method }}' === 'cod' }">
                                <input type="radio" wire:model.live="payment_method" value="cod" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-3 font-medium text-gray-900">Bayar di Tempat (COD)</span>
                            </label>
                            <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50" :class="{ 'border-blue-500 ring-2 ring-blue-200': '{{ $payment_method }}' === 'qris' }">
                                <input type="radio" wire:model.live="payment_method" value="qris" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-3 font-medium text-gray-900">QRIS</span>
                            </label>
                        </div>
                        @error('payment_method') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                        <textarea wire:model.defer="notes" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" placeholder="Tambahkan catatan khusus jika diperlukan (opsional)"></textarea>
                        @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                </div>
                
                <div class="p-4 md:p-6 bg-gray-50 border-t sticky bottom-0 rounded-b-lg">
                    @if (session()->has('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="flex gap-4">
                        <button type="button" @click="show = false"
                            class="flex-1 bg-gray-500 text-white py-3 rounded-lg font-medium hover:bg-gray-600 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-green-600 text-white py-3 rounded-lg font-medium hover:bg-green-700 transition-colors"
                            wire:loading.attr="disabled" wire:target="submitOrder">
                            <span wire:loading.remove wire:target="submitOrder">
                                <i class="fas fa-check mr-2"></i>
                                Konfirmasi Pesanan
                            </span>
                            <span wire:loading wire:target="submitOrder">
                                Memproses...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>