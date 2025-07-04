{{-- Komponen harus memiliki satu elemen root. Kita gunakan <div>. --}}
    <div class="relative">
        <a href="{{ route('cart.index') }}" :class="atTop ? 'text-white' : 'text-gray-700'"
            class="bg-transparent px-4 py-2 rounded-lg transition-colors">
            <i class="fas fa-shopping-cart mr-2"></i>
            Keranjang (<span wire:key="cart-count">{{ $cartCount }}</span>)
        </a>
    </div>