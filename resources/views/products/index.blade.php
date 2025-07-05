@extends('layouts.app')

@section('title', 'Semua Produk AquaFresh Market')

@section('content')
    <!-- Hero Section -->
    <section class="relative py-8 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-20 left-10 w-3 h-3 bg-white/20 rounded-full animate-pulse"></div>
            <div class="absolute top-40 right-20 w-2 h-2 bg-yellow-300/30 rounded-full animate-bounce"></div>
            <div class="absolute bottom-32 left-1/4 w-4 h-4 bg-green-300/20 rounded-full animate-pulse delay-300"></div>
            <div class="absolute bottom-20 right-1/3 w-3 h-3 bg-blue-300/30 rounded-full animate-bounce delay-500"></div>
        </div>

        <div class="container mx-auto px-4 text-center relative z-10">

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-black mb-6 leading-tight">
                Semua Produk <span class="text-yellow-300">AquaFresh</span>
            </h1>
            <p class="text-lg md:text-xl text-black max-w-3xl mx-auto leading-relaxed mb-3">
                Temukan ikan segar dan olahan berkualitas terbaik dengan pilihan lengkap untuk memenuhi kebutuhan kuliner
                Anda
            </p>
            <!-- Product Count -->
            <div
                class="inline-flex items-center gap-2 bg-teal-500/30 border-teal-400/30 backdrop-blur-sm rounded-full px-2">
                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                <span class="text-black text-md font-medium ">
                    {{ count($products) }} Produk Tersedia
                </span>
            </div>
        </div>

    </section>

    <!-- Products Section -->
    <section class=" bg-gradient-to-b from-gray-50 to-white mb-6">
        <div class="container mx-auto px-4">
            @if (!empty($products))
                <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach ($products as $product)
                        <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl overflow-hidden transition-all duration-500 transform hover:-translate-y-2 border border-gray-100 hover:border-teal-200"
                            x-data="{ product: {{ json_encode($product) }}, isHovered: false }" @mouseenter="isHovered = true" @mouseleave="isHovered = false">

                            <!-- Product Image -->
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

                                <!-- Overlay -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>

                                <!-- Quality Badge -->
                                <div
                                    class="absolute top-4 left-4 bg-teal-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                    FRESH
                                </div>

                                <!-- Heart Icon -->
                                <div
                                    class="absolute top-4 right-4 bg-white/20 backdrop-blur-sm rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <i class="fas fa-heart text-red-500"></i>
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-6">
                                <h3 class="font-bold text-xl mb-3 group-hover:text-teal-600 transition-colors line-clamp-2"
                                    title="{{ $product->name }}">
                                    {{ $product->name }}
                                </h3>

                                <p class="text-gray-600 text-sm mb-4 leading-relaxed line-clamp-2">
                                    {{ $product->description ?? 'Produk ikan segar berkualitas tinggi langsung dari nelayan terpercaya.' }}
                                </p>

                                <!-- Price and Rating -->
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex flex-col">
                                        <span class="text-2xl font-bold text-gray-900">
                                            {{ $product->price_formatted ?? 'Rp 0' }}
                                        </span>
                                        @if (isset($product->weight))
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

                                <!-- Add to Cart Button -->
                                <div class="transform transition-transform duration-300 hover:scale-105">
                                    <livewire:add-to-cart-button :productId="$product->id" :key="$product->id" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="mb-8">
                        <div
                            class="w-32 h-32 bg-gradient-to-br from-teal-100 to-teal-200 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-box-open text-6xl text-teal-400"></i>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-700 mb-4">Produk Tidak Ditemukan</h3>
                        <p class="text-gray-500 text-lg max-w-md mx-auto mb-8">
                            Saat ini belum ada produk yang tersedia. Silakan periksa kembali nanti.
                        </p>
                        <a href="{{ route('home') }}"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-teal-600 to-teal-700 text-white px-8 py-4 rounded-xl font-bold text-lg hover:from-teal-700 hover:to-teal-800 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <i class="fas fa-home"></i>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Custom CSS for line-clamp -->
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

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
    </style>
@endsection
