<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AquaFresh Market')</title>
    <meta name="description" content="@yield('description', 'Ikan segar dan olahan berkualitas tinggi langsung dari nelayan')">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Livewire -->
    @livewireStyles

    <!-- Custom Styles -->
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .fish-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23a5b4fc' fill-opacity='0.05'%3E%3Cpath d='M30 30c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20zM0 30c0-11.046 8.954-20 20-20s20 8.954 20 20-8.954 20-20 20S0 41.046 0 30z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="bg-gray-50" x-data="{
    cart: {
        items: [],
        count: 0,
        total: 0,
        add(product) {
            let existingItem = this.items.find(item => item.id === product.id);
            if (existingItem) {
                existingItem.quantity++;
            } else {
                this.items.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    quantity: 1,
                    image: product.image
                });
            }
            this.updateCart();
        },
        remove(id) {
            this.items = this.items.filter(item => item.id !== id);
            this.updateCart();
        },
        updateCart() {
            this.count = this.items.reduce((sum, item) => sum + item.quantity, 0);
            this.total = this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        }
    }
}" x-init="$store.cart = cart">
    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                        <i class="fas fa-fish mr-2"></i>
                        AquaFresh Market
                    </a>
                </div>

                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}"
                        class="text-gray-700 hover:text-blue-600 transition-colors">Beranda</a>
                    <a href="{{ route('products.index') }}"
                        class="text-gray-700 hover:text-blue-600 transition-colors">Produk</a>
                    <a href="#contact" class="text-gray-700 hover:text-blue-600 transition-colors">Kontak</a>
                </nav>

                <div class="flex items-center space-x-4">
                    <!-- Cart Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Keranjang (<span x-text="$store.cart.count">0</span>)
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border">
                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-4">Keranjang Belanja</h3>
                                <div x-show="$store.cart.items.length === 0" class="text-gray-500 text-center py-8">
                                    Keranjang kosong
                                </div>
                                <div x-show="$store.cart.items.length > 0">
                                    <template x-for="item in $store.cart.items" :key="item.id">
                                        <div class="flex items-center justify-between py-2 border-b">
                                            <div class="flex-1">
                                                <div class="font-semibold" x-text="item.name"></div>
                                                <div class="text-sm text-gray-600">
                                                    <span x-text="item.quantity"></span> x
                                                    <span x-text="'Rp ' + item.price.toLocaleString('id-ID')"></span>
                                                </div>
                                            </div>
                                            <button @click="$store.cart.remove(item.id)"
                                                class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </template>
                                    <div class="mt-4 pt-4 border-t">
                                        <div class="flex justify-between font-bold text-lg">
                                            <span>Total:</span>
                                            <span x-text="'Rp ' + $store.cart.total.toLocaleString('id-ID')"></span>
                                        </div>
                                        <button @click="$dispatch('open-checkout')"
                                            class="w-full bg-green-600 text-white py-2 rounded-lg mt-4 hover:bg-green-700 transition-colors">
                                            Checkout
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden">
                        <button class="text-gray-700 hover:text-blue-600">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="text-2xl font-bold mb-4">
                        <i class="fas fa-fish mr-2"></i>
                        AquaFresh Market
                    </div>
                    <p class="text-gray-400">Menyediakan ikan segar dan olahan berkualitas tinggi untuk keluarga
                        Indonesia.</p>
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-4">Produk</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('products.index') }}" class="hover:text-white">Ikan Segar</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-white">Olahan Ikan</a></li>
                        <li><a href="{{ route('products.index') }}" class="hover:text-white">Produk Baru</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-4">Layanan</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Delivery</a></li>
                        <li><a href="#" class="hover:text-white">Customer Service</a></li>
                        <li><a href="#" class="hover:text-white">Riwayat Pesanan</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-lg mb-4">Kontak</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fab fa-whatsapp mr-2"></i>+62 812-3456-7890</li>
                        <li><i class="fas fa-envelope mr-2"></i>info@aquafresh.com</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>Medan, Sumatera Utara</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} AquaFresh Market. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Custom Scripts -->
    <script>
        // Format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>
