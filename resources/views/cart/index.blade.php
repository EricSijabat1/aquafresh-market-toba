@extends('layouts.app')
@section('title', 'Keranjang Belanja')

@section('content')
    <div class="container mx-auto px-4 py-8 pt-24">
        <h1 class="text-3xl font-bold text-center mb-6">Keranjang Belanja Anda</h1>
        @if ($cartItems->count() > 0)
            {{-- ... Tampilkan daftar item di sini (looping $cartItems) ... --}}
            {{-- ... Tampilkan total harga ... --}}
            <div class="text-center mt-6">
                <a href="{{ route('checkout.index') }}" class="bg-green-600 text-white px-8 py-3 rounded-lg font-bold">
                    Lanjut ke Checkout
                </a>
            </div>
        @else
            <p class="text-center text-gray-600">Keranjang Anda masih kosong.</p>
        @endif
    </div>
@endsection
