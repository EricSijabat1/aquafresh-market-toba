<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AquaFresh Market')</title>
    <meta name="description" content="@yield('description', 'Ikan segar dan olahan berkualitas tinggi langsung dari nelayan')">

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <script src="https://unpkg.com/@alpinejs/intersect@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    @livewireStyles

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .fish-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23a5b4fc' fill-opacity='0.05'%3E%3Cpath d='M30 30c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20zM0 30c0-11.046 8.954-20 20-20s20 8.954 20 20-8.954 20-20 20S0 41.046 0 30z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>

<body class="bg-gray-50" x-data x-init="// Membuat Global Store untuk Notifikasi
Alpine.store('notification', {
    visible: false,
    message: '',
    timer: null,

    show(message) {
        this.visible = true;
        this.message = message;
        if (this.timer) clearTimeout(this.timer);
        this.timer = setTimeout(() => { this.visible = false }, 3000);
    }
});

// Membuat Global Store untuk Keranjang
Alpine.store('cart', {
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

        // Panggil notifikasi store secara langsung
        Alpine.store('notification').show(`${product.name} telah ditambahkan ke keranjang!`);
    },

    remove(id) {
        this.items = this.items.filter(item => item.id !== id);
        this.updateCart();
    },

    updateCart() {
        this.count = this.items.reduce((sum, item) => sum + item.quantity, 0);
        this.total = this.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    },

    clearCart() {
        this.items = [];
        this.updateCart();
    }
});" @order-placed.window="$store.cart.clearCart()">
    <header x-data="{ atTop: true }"
        @scroll.window="
            const hero = document.getElementById('hero-section');
            if (hero) {
                atTop = (window.pageYOffset < (hero.offsetHeight - 80));
            } else {
                atTop = (window.pageYOffset < 50);
            }
        "
        :class="{
            'bg-white/90 shadow-lg backdrop-blur-md': !atTop,
            'bg-black/20 backdrop-blur-md': atTop
        }"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">

        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-2xl font-bold transition-colors"
                    :class="atTop ? 'text-white' : 'text-black'">

                    AquaFresh Market
                </a>

                <nav class="hidden md:flex space-x-8">
                    <a href="{{ route('home') }}" class="font-medium transition-colors hover:text-blue-300"
                        :class="atTop ? 'text-white' : 'text-gray-700'">Beranda</a>
                    <a href="{{ route('products.index') }}" class="font-medium transition-colors hover:text-blue-300"
                        :class="atTop ? 'text-white' : 'text-gray-700'">Produk</a>
                    <a href="#contact" class="font-medium transition-colors hover:text-blue-300"
                        :class="atTop ? 'text-white' : 'text-gray-700'">Kontak</a>
                </nav>

                <div class="flex items-center space-x-4">
                    <livewire:cart-counter />

                    <div class="md:hidden">
                        <button :class="atTop ? 'text-white' : 'text-gray-700'" class="hover:text-blue-600">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

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

    @livewireScripts

    <script>
        // ... fungsi formatCurrency dan smooth scrolling Anda ...
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
        // TAMBAHKAN FUNGSI BARU INI
        // FUNGSI UNTUK MENGARAHKAN KE HALAMAN CHECKOUT
        function goToCheckout() {
            const cart = Alpine.store('cart');
            if (cart.items.length === 0) {
                alert('Keranjang Anda kosong!');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('checkout.index') }}'; // Route ke controller

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);

            cart.items.forEach((item, index) => {
                const fields = {
                    id: item.id,
                    quantity: item.quantity
                };
                for (const property in fields) {
                    let input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `items[${index}][${property}]`;
                    input.value = fields[property];
                    form.appendChild(input);
                }
            });

            document.body.appendChild(form);
            form.submit();
        }
    </script>

    @stack('scripts')
    <div x-show="$store.notification.visible" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="transform translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        x-transition:enter-end="transform translate-y-0 opacity-100 sm:translate-x-0"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed top-20 right-5 z-50 bg-yellow-500 text-white py-3 px-6 rounded-lg shadow-lg flex items-center"
        style="display: none;">

        <i class="fas fa-check-circle mr-3"></i>
        <span x-text="$store.notification.message"></span>
    </div>
</body>

</html>
