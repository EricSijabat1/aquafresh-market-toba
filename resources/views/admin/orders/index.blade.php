@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <table class="w-full">
        <thead>
            <tr>
                <th class="text-left py-2">Nomor Pesanan</th>
                <th class="text-left py-2">Pelanggan</th>
                <th class="text-left py-2">Total</th>
                <th class="text-left py-2">Status</th>
                <th class="text-right py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr class="border-t">
                <td class="py-2">{{ $order->order_number }}</td>
                <td class="py-2">{{ $order->customer->name }}</td>
                <td class="py-2">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td class="py-2">{{ $order->status }}</td>
                <td class="py-2 text-right">
                    <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-500">Detail</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection