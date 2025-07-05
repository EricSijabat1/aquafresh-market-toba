@extends('layouts.app')

@section('title', 'AquaFresh Market - Ikan Segar & Olahan Berkualitas')

@section('content')
    <!-- Hero Section -->
    <section id="hero-section" x-intersect:leave="scrolled = true" x-intersect:enter="scrolled = false"
        class="relative h-[100vh] flex items-center justify-center text-white overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-cover bg-center transform scale-110 transition-transform duration-1000"
                style="background-image: url('https://images.unsplash.com/photo-1623598122059-9b5ef17619c8?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');">
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/40 via-black/50 to-black/60"></div>
        </div>

        <!-- Floating Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-3 h-3 bg-blue-300/30 rounded-full animate-pulse"></div>
            <div class="absolute top-40 right-20 w-2 h-2 bg-yellow-300/40 rounded-full animate-bounce"></div>
            <div class="absolute bottom-32 left-1/4 w-4 h-4 bg-green-300/30 rounded-full animate-pulse delay-300"></div>
            <div class="absolute bottom-20 right-1/3 w-3 h-3 bg-blue-300/40 rounded-full animate-bounce delay-500"></div>
        </div>

        <div class="relative z-10 text-center px-4 animate-fade-in">
            <div class="mb-6">
                <span
                    class="inline-block bg-blue-500/20 backdrop-blur-sm border border-blue-300/30 text-blue-100 px-4 py-2 rounded-full text-sm font-medium">
                    Langsung dari Danau Toba
                </span>
            </div>
            <h1
                class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 leading-tight bg-gradient-to-r from-white via-blue-50 to-yellow-100 bg-clip-text text-transparent">
                Kesegaran Danau Toba, Langsung ke Meja Anda
            </h1>
            <p class="text-lg md:text-xl lg:text-2xl mb-10 max-w-4xl mx-auto text-gray-200 leading-relaxed">
                Nikmati cita rasa otentik ikan air tawar terbaik, hasil tangkapan nelayan lokal yang kami pilihkan khusus
                untuk Anda dengan kualitas premium.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white px-8 py-4 rounded-xl font-bold text-lg hover:from-orange-600 hover:to-orange-700 transition-all duration-300 transform hover:scale-105 hover:-translate-y-1 shadow-xl hover:shadow-2xl">
                    <i class="fas fa-shopping-bag transition-transform hover:scale-110"></i>
                    Lihat Semua Produk
                </a>
                <a href="#contact"
                    class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border-2 border-white/30 text-white px-8 py-4 rounded-xl font-bold text-lg hover:bg-white/20 hover:border-white/50 transition-all duration-300 transform hover:scale-105">
                    <i class="fas fa-phone transition-transform hover:rotate-12"></i>
                    Hubungi Kami
                </a>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 border-2 border-white/50 rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white/70 rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <div class="mb-4">
                    <span class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-medium">
                        Kategori Produk
                    </span>
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-6">
                    Pilih Kategori <span class="text-teal-600">Favorit</span> Anda
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Temukan beragam pilihan ikan segar dan olahan berkualitas tinggi yang sesuai dengan kebutuhan Anda
                </p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                @foreach ($categories as $category)
                    <div
                        class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 hover:border-teal-200">
                        <div class="relative h-56 bg-gradient-to-br from-blue-400 to-blue-600 overflow-hidden">
                            @if ($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-500 to-cyan-500">
                                    <i
                                        class="fas fa-fish text-8xl text-white/90 transition-transform duration-500 group-hover:scale-110 group-hover:rotate-6"></i>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            <div class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm rounded-full p-2">
                                <i class="fas fa-star text-yellow-300"></i>
                            </div>
                        </div>
                        <div class="p-8">
                            <h3 class="text-2xl font-bold text-gray-800 mb-3 group-hover:text-teal-600 transition-colors">
                                {{ $category->name }}
                            </h3>
                            <p class="text-gray-600 mb-6 leading-relaxed">{{ $category->description }}</p>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <span class="text-sm text-gray-500 font-medium">{{ $category->active_products_count }}
                                        Produk Tersedia</span>
                                </div>
                                <a href="{{ route('products.category', $category->id) }}"
                                    class="group/btn bg-gradient-to-r from-teal-600 to-teal-700 text-white px-6 py-3 rounded-xl hover:from-teal-700 hover:to-teal-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <span class="flex items-center gap-2">
                                        Lihat Produk
                                        <i
                                            class="fas fa-arrow-right transition-transform group-hover/btn:translate-x-1"></i>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <div class="mb-4">
                    <span class="inline-block bg-yellow-100 text-yellow-800 px-4 py-2 rounded-full text-sm font-medium">
                        Produk Unggulan
                    </span>
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-6">
                    Pilihan <span class="text-orange-600">Terbaik</span> Kami
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Produk pilihan dengan kualitas premium yang telah dipercaya oleh ribuan pelanggan
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($featuredProducts as $product)
                    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 hover:border-orange-200"
                        x-data="{ product: {{ json_encode($product) }}, isHovered: false }" @mouseenter="isHovered = true" @mouseleave="isHovered = false">
                        <div class="relative h-56 bg-gray-100 overflow-hidden">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                                    <i
                                        class="fas fa-image text-5xl text-gray-400 transition-transform duration-500 group-hover:scale-110"></i>
                                </div>
                            @endif
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                            <div
                                class="absolute top-4 left-4 bg-orange-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                POPULER
                            </div>
                            <div
                                class="absolute top-4 right-4 bg-transparent/20 backdrop-blur-sm rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <i class="fas fa-heart text-red-500"></i>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-bold text-xl mb-3 group-hover:text-orange-600 transition-colors">
                                {{ $product->name }}
                            </h3>
                            <p class="text-gray-600 text-sm mb-4 leading-relaxed">
                                {{ Str::limit($product->description, 60) }}
                            </p>
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex flex-col">
                                    <span class="text-2xl font-bold text-gray-900">{{ $product->price_formatted }}</span>
                                    @if ($product->weight)
                                        <span class="text-sm text-gray-500">{{ $product->weight }} kg</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="flex text-yellow-400">
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                        <i class="fas fa-star text-xs"></i>
                                    </div>
                                    <span class="text-xs text-gray-500 ml-1">(4.9)</span>
                                </div>
                            </div>
                            <div class="transform transition-transform duration-300 hover:scale-105">
                                <livewire:add-to-cart-button :productId="$product->id" :key="$product->id" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-16">
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center gap-3 bg-gradient-to-r from-teal-600 to-teal-700 text-white px-10 py-4 rounded-xl font-bold text-lg hover:from-teal-700 hover:to-teal-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                    Lihat Semua Produk
                    <i class="fas fa-arrow-right transition-transform hover:translate-x-1"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-gradient-to-b from-gray-50 to-gray-100">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <div class="mb-4">
                    <span class="inline-block bg-green-100 text-green-800 px-4 py-2 rounded-full text-sm font-medium">
                        Hubungi Kami
                    </span>
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-800 mb-6">
                    Siap <span class="text-teal-600">Melayani</span> Anda
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Tim customer service kami siap membantu kebutuhan ikan segar dan olahan Anda 24/7
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                <div
                    class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl p-8 text-center transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 hover:border-teal-200">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                        <i class="fab fa-whatsapp text-3xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 group-hover:text-teal-600 transition-colors">WhatsApp</h3>
                    <p class="text-gray-600 mb-4">Chat langsung dengan tim kami</p>
                    <a href="https://wa.me/6281234567890" class="text-teal-600 font-semibold hover:underline">+62
                        812-3456-7890</a>
                </div>

                <div
                    class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl p-8 text-center transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 hover:border-slate-200">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-slate-500 to-slate-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                        <i class="fas fa-envelope text-3xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 group-hover:text-slate-600 transition-colors">Email</h3>
                    <p class="text-gray-600 mb-4">Kirim pertanyaan detail Anda</p>
                    <a href="mailto:info@aquafresh.com"
                        class="text-slate-600 font-semibold hover:underline">info@aquafresh.com</a>
                </div>

                <div
                    class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl p-8 text-center transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 hover:border-orange-200">
                    <div
                        class="w-20 h-20 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg group-hover:shadow-xl transition-shadow duration-300">
                        <i class="fas fa-map-marker-alt text-3xl text-white"></i>
                    </div>
                    <h3 class="font-bold text-xl mb-3 group-hover:text-orange-600 transition-colors">Lokasi</h3>
                    <p class="text-gray-600 mb-4">Kunjungi toko offline kami</p>
                    <p class="text-orange-600 font-semibold">Medan, Sumatera Utara</p>
                </div>
            </div>


        </div>
    </section>

    <!-- Custom CSS for animations -->
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 1s ease-out;
        }

        .shadow-text {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .shadow-text-sm {
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection
