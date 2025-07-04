@extends('layouts.app')
@section('title', 'Keranjang Belanja')

@section('content')
    <div class="container mx-auto px-4 py-8 pt-24 min-h-screen">
        <h1 class="text-3xl font-bold text-center mb-8">Keranjang Belanja Anda</h1>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if ($cartItems->count() > 0)
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-4 text-left font-semibold text-gray-700">Produk</th>
                            <th class="p-4 text-left font-semibold text-gray-700">Harga</th>
                            <th class="p-4 text-center font-semibold text-gray-700">Jumlah</th>
                            <th class="p-4 text-right font-semibold text-gray-700">Subtotal</th>
                            <th class="p-4 text-center font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr class="border-b">
                                <td class="p-4 flex items-center">
                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                        alt="{{ $item->product->name }}" class="w-16 h-16 object-cover rounded-md mr-4">
                                    <div>
                                        <p class="font-semibold">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $item->product->weight }} kg</p>
                                    </div>
                                </td>
                                <td class="p-4">Rp {{ number_format($item->product->price, 0, ',', '.') }}</td>
                                <td class="p-4">
                                    {{-- Form untuk update quantity --}}
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                        class="flex justify-center">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $item->quantity }}"
                                            class="w-16 text-center border-gray-300 rounded" min="1">
                                        <button type="submit" class="ml-2 text-blue-600 hover:text-blue-800"><i
                                                class="fas fa-sync"></i></button>
                                    </form>
                                </td>
                                <td class="p-4 text-right font-semibold">Rp
                                    {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</td>
                                <td class="p-4 text-center">
                                    {{-- Form untuk remove item --}}
                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="p-6 bg-gray-50 flex justify-end items-center">
                    <div class="text-right">
                        <p class="text-lg text-gray-600">Total Belanja:</p>
                        <p class="text-3xl font-bold text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</p>
                        <a href="{{ route('checkout.index') }}"
                            class="mt-4 inline-block bg-green-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-green-700 transition-colors">
                            Lanjutkan ke Pembayaran <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-16 bg-white shadow-lg rounded-lg">
                <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-700">Keranjang Anda Kosong</h2>
                <p class="text-gray-500 mt-2">Sepertinya Anda belum menambahkan produk apapun.</p>
                <a href="{{ route('products.index') }}"
                    class="mt-6 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
@endsection
