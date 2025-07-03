@extends('layouts.app')

@section('title', 'AquaFresh Market - Ikan Segar & Olahan Berkualitas')

@section('content')
    <!-- Hero Section -->
    <section class="gradient-bg fish-pattern relative overflow-hidden">
        <div class="container mx-auto px-4 py-20">
            <div class="text-center text-white">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">
                    Ikan Segar & Olahan
                    <span class="block text-yellow-300">Berkualitas Premium</span>
                </h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                    Dapatkan ikan segar langsung dari nelayan dan berbagai olahan ikan berkualitas tinggi dengan harga terjangkau
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products.index') }}" class="bg-yellow-400 text-gray-800 px-8 py-4 rounded-lg font-bold text-lg hover:bg-yellow-300 transition-colors">
                        <i class="fas fa-fish mr-2"></i>
                        Lihat Produk
                    </a>
                    <a href="#contact" class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-white hover:text-gray-800 transition-colors">
                        <i class="fas fa-phone mr-2"></i>
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" class="w-full h-auto">
                <path fill="#f9fafb" d="M0,64L48,58.7C96,53,192,43,288,48C384,53,480,75,576,80C672,85,768,75,864,69.3C960,64,1056,64,1152,58.7C1248,53,1344,43,1392,37.3L1440,32L1440,120L1392,120C1344,120,1248,120,1152,120C1056,120,960,120,864,120C768,120,672,120,576,120C480,120,384,120,288,120C192,120,96,120,48,120L0,120Z"></path>
            </svg>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Kategori Produk</h2>
                <p class="text-xl text-gray-600">Pilih kategori yang sesuai dengan kebutuhan Anda</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                @foreach($categories as $category)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                    <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                        @if($category->image)
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-fish text-6xl text-white"></i>
                        @endif
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $category->name }}</h3>
                        <p class="text-gray-600 mb-4">{{ $category->description }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500">{{ $category->active_products_count }} Produk</span>
                            <a href="{{ route('products.category', $category->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
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
                @foreach($featuredProducts as $product)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover border" x-data="{ product: {{ json_encode($product) }} }">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-image text-4xl text-gray-400"></i>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2">{{ $product->name }}</h3>
                        <p class="text-gray-600 text-sm mb-3">{{ Str::limit($product->description, 50) }}</p>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-2xl font-bold text-blue-600">{{ $product->price_formatted }}</span>
                            @if($product->weight)
                                <span class="text-sm text-gray-500">{{ $product->weight }} kg</span>
                            @endif
                        </div>
                        <button 
                            @click="$store.cart.add(product)"
                            class="w-full bg-{{ $product->category->id == 1 ? 'blue' : 'orange' }}-600 text-white py-2 rounded-lg hover:bg-{{ $product->category->id == 1 ? 'blue' : 'orange' }}-700 transition-colors"
                        >
                            <i class="fas fa-cart-plus mr-2"></i>
                            Tambah ke Keranjang
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold text-lg hover:bg-blue-700 transition-colors">
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