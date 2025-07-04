@extends('layouts.app')

@section('title', 'AquaFresh Market - Ikan Segar & Olahan Berkualitas')

@section('content')
    <!-- Hero Section -->

    <section id="hero-section" x-intersect:leave="scrolled = true" x-intersect:enter="scrolled = false"
        class="relative h-[100vh] flex items-center justify-center text-white">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-cover bg-center"
                style="background-image: url('https://images.unsplash.com/photo-1623598122059-9b5ef17619c8?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">
            </div>
            <div class="absolute inset-0 bg-black opacity-50"></div>
        </div>

        <div class="relative z-10 text-center px-4">
            <h1 class="text-4xl md:text-6xl font-bold mb-4 leading-tight shadow-text">
                Kesegaran Danau Toba, Langsung ke Meja Anda
            </h1>
            <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto shadow-text-sm">
                Nikmati cita rasa otentik ikan air tawar terbaik, hasil tangkapan nelayan lokal yang kami pilihkan khusus
                untuk Anda.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('products.index') }}"
                    class="bg-yellow-400 text-gray-900 px-8 py-4 rounded-lg font-bold text-lg hover:bg-yellow-300 transition-transform transform hover:scale-105 shadow-lg">
                    Lihat Semua Produk
                </a>
                <a href="#contact"
                    class="bg-transparent border-2 border-white text-black px-8 py-4 rounded-lg font-bold text-lg hover:bg-yellow hover:text-gray-800 transition-colors">
                    <i class="fas fa-phone mr-2"></i>
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Kategori Produk</h1>
                <p class="text-xl text-gray-600">Pilih kategori yang sesuai dengan kebutuhan Anda</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                @foreach ($categories as $category)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                        <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                            @if ($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-fish text-6xl text-white"></i>
                            @endif
                        </div>
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $category->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ $category->description }}</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">{{ $category->active_products_count }} Produk</span>
                                <a href="{{ route('products.category', $category->id) }}"
                                    class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                                    Lihat Produk
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Produk Unggulan</h2>
                <p class="text-xl text-gray-600">Produk pilihan dengan kualitas terbaik</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($featuredProducts as $product)
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover border" x-data="{ product: {{ json_encode($product) }} }">
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-image text-4xl text-gray-400"></i>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 50) }}</p>
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-black">{{ $product->price_formatted }}</span>
                                @if ($product->weight)
                                    <span class="text-sm text-gray-500">{{ $product->weight }} kg</span>
                                @endif
                            </div>
                            <livewire:add-to-cart-button :productId="$product->id" :key="$product->id" />
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('products.index') }}"
                    class="bg-gray-600 text-white px-8 py-3 rounded-lg font-bold text-lg hover:bg-gray-700 transition-colors">
                    Lihat Semua Produk
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Hubungi Kami</h2>
                <p class="text-xl text-gray-600">Siap melayani kebutuhan ikan segar dan olahan Anda</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fab fa-whatsapp text-2xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">WhatsApp</h3>
                    <p class="text-gray-600">+62 812-3456-7890</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope text-2xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Email</h3>
                    <p class="text-gray-600">info@aquafresh.com</p>
                </div>

                <div class="text-center">
                    <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-map-marker-alt text-2xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-lg mb-2">Lokasi</h3>
                    <p class="text-gray-600">Medan, Sumatera Utara</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Livewire Checkout Component -->
    @livewire('checkout-component')
@endsection
