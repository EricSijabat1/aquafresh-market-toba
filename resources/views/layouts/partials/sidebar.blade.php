<aside class="w-64 bg-gray-800 text-white min-h-screen p-4">
    <div class="text-2xl font-bold mb-8">
        <a href="{{ route('admin.dashboard') }}">AquaFresh Admin</a>
    </div>
    <nav>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700">
                    <i class="fas fa-tags mr-3"></i>
                    Kategori
                </a>
            </li>
            <li>
                <a href="{{ route('admin.products.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700">
                    <i class="fas fa-box mr-3"></i>
                    Produk
                </a>
            </li>
            <li>
                <a href="{{ route('admin.orders.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700">
                    <i class="fas fa-shopping-cart mr-3"></i>
                    Pesanan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.customers.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700">
                    <i class="fas fa-users mr-3"></i>
                    Pelanggan
                </a>
            </li>
        </ul>
        <div class="mt-auto pt-4 border-t border-gray-700">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault();
                    this.closest('form').submit();"
           class="flex items-center p-2 rounded-lg text-red-400 hover:bg-red-700 hover:text-white transition-colors duration-200">
            <i class="fas fa-sign-out-alt mr-3"></i>
            Logout
        </a>
    </form>
</div>
    </nav>
</aside>