@extends('layouts.app')

@section('title', 'Produk Kategori: ' . $category->name)

@section('content')
    <section class="bg-blue-100 py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold text-blue-800 mb-2">{{ $category->name }}</h1>
            <p class="text-lg text-blue-600 max-w-2xl mx-auto">{{ $category->description }}</p>
        </div>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            @if (count($products) > 0)
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($products as $product)
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
                                <h3 class="font-bold text-lg mb-2 truncate">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-3 h-10">{{ Str::limit($product->description, 50) }}</p>
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-2xl font-bold text-blue-600">{{ $product->price_formatted }}</span>
                                    @if ($product->weight)
                                        <span class="text-sm text-gray-500">{{ $product->weight }} kg</span>
                                    @endif
                                </div>
                                <livewire:add-to-cart-button :productId="$product->id" :key="$product->id" />
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- <div class="mt-12">
                    {{ $products->links() }}
                </div> --}}
            @else
                <div class="text-center py-12">
                    <i class="fas fa-fish text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-700">Belum Ada Produk</h3>
                    <p class="text-gray-500 mt-2">Saat ini belum ada produk yang tersedia di kategori ini.</p>
                    <a href="{{ route('products.index') }}"
                        class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Lihat Semua Produk
                    </a>
                </div>
            @endif
        </div>
    </section>

    @livewire('checkout-component')
@endsection
