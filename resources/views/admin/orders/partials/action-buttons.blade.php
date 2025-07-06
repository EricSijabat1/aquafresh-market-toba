{{-- Menggunakan Alpine.js untuk mengelola state dropdown --}}
<div x-data="{ open: false }" @click.outside="open = false" class="relative inline-block text-left">
    
    {{-- Tombol untuk membuka/menutup dropdown --}}
    <button @click="open = !open" type="button" 
            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            title="Aksi Lainnya">
        <i class="fas fa-ellipsis-v text-sm"></i>
    </button>
    
    {{-- Konten Dropdown --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-20"
         style="display: none;"> {{-- `style="display: none;"` untuk mencegah FOUC --}}

        <div class="py-1" role="menu" aria-orientation="vertical">
            <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                <i class="fas fa-eye w-5 mr-2 text-gray-400"></i>
                Lihat Detail
            </a>
          
            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                <i class="fas fa-print w-5 mr-2 text-gray-400"></i>
                Cetak Invoice
            </a>
            <div class="border-t border-gray-100 my-1"></div>
          
        </div>
    </div>
</div>