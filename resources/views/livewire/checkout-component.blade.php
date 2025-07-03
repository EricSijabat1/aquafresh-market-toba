<div x-data="{ showCheckout: @entangle('showCheckout') }" x-show="showCheckout" x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75"
    @keydown.escape.window="showCheckout = false" style="display: none;">

    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl m-4 max-h-[90vh] flex flex-col"
        @click.away="showCheckout = false">

        <div class="flex justify-between items-center p-6 border-b">
            <h2 class="text-2xl font-bold text-gray-800">Checkout</h2>
            <button @click="showCheckout = false" class="text-gray-500 hover:text-gray-800">
                <i class="fas fa-times fa-lg"></i>
            </button>
        </div>

        <form wire:submit.prevent="submitOrder" class="flex-grow overflow-y-auto">
            <div class="p-6 space-y-6">
                <div>
                    <h3 class="font-bold text-lg mb-4 text-gray-700">Ringkasan Pesanan</h3>
                    <div class="space-y-3 max-h-48 overflow-y-auto pr-2">
                        @forelse($cartItems as $item)
                            <div class="flex justify-between items-center text-sm">
                                <div>
                                    <span class="font-semibold">{{ $item['name'] }}</span>
                                    <span class="text-gray-500">x {{ $item['quantity'] }}</span>
                                </div>
                                <span class="text-gray-800">Rp
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</span>
                            </div>
                        @empty
                            <p class="text-gray-500">Keranjang Anda kosong.</p>
                        @endforelse
                    </div>
                    <div class="flex justify-between font-bold text-lg mt-4 pt-4 border-t">
                        <span>Total:</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-4 text-gray-700">Detail Pengiriman</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input type="text" wire:model.defer="name" id="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="whatsapp" class="block text-sm font-medium text-gray-700">Nomor WhatsApp</label>
                            <input type="tel" wire:model.defer="whatsapp" id="whatsapp"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('whatsapp')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email
                                (Opsional)</label>
                            <input type="email" wire:model.defer="email" id="email"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('email')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700">Telepon Lain
                                (Opsional)</label>
                            <input type="tel" wire:model.defer="phone" id="phone"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('phone')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                            <textarea wire:model.defer="address" id="address" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                            @error('address')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Catatan
                                (Opsional)</label>
                            <textarea wire:model.defer="notes" id="notes" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            @error('notes')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                @error('general')
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="p-6 bg-gray-50 border-t rounded-b-xl">
                <button type="submit"
                    class="w-full bg-green-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>
                        <i class="fas fa-check-circle mr-2"></i>
                        Buat Pesanan
                    </span>
                    <span wire:loading>
                        Memproses...
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
