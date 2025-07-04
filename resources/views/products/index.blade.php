@extends('layouts.app')

@section('title', 'Semua Produk AquaFresh Market')

@section('content')
    <section class="bg-gray-100 py-12">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Semua Produk Kami</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">Temukan ikan segar dan olahan berkualitas terbaik di sini.</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            @if(!empty($products))
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover border" x-data="{ product: {{ json_encode($product) }} }">
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                @if($product->image)
                                    {{-- Gunakan asset() untuk path gambar dari storage --}}
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-image text-4xl text-gray-400"></i>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-lg mb-2 truncate" title="{{ $product->name }}">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mb-3 h-10">{{ $product->description ?? 'Deskripsi produk belum tersedia.' }}</p>
                                <div class="flex items-center justify-between mb-4">
                                    {{-- Tampilkan harga dummy atau harga dari objek produk --}}
                                    <span class="text-2xl font-bold text-blue-600">{{ $product->price_formatted ?? 'Rp 0' }}</span>
                                    @if(isset($product->weight))
                                        <span class="text-sm text-gray-500">{{ $product->weight }} kg</span>
                                    @endif
                                </div>
                                <button
                                    @click="$store.cart.add(product)"
                                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors"
                                >
                                    <i class="fas fa-cart-plus mr-2"></i>
                                    Tambah Keranjang
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-bold text-gray-700">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-500 mt-2">Saat ini belum ada produk yang tersedia.</p>
                </div>
            @endif
        </div>
    </section>

    @livewire('checkout-component')
@endsection