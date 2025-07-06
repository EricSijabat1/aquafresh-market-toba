@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700">Total Pesanan</h3>
            <p class="text-3xl font-bold">{{ $stats['total_orders'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700">Pesanan Pending</h3>
            <p class="text-3xl font-bold">{{ $stats['pending_orders'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700">Total Produk</h3>
            <p class="text-3xl font-bold">{{ $stats['total_products'] }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-700">Total Pelanggan</h3>
            <p class="text-3xl font-bold">{{ $stats['total_customers'] }}</p>
        </div>
    </div>
@endsection