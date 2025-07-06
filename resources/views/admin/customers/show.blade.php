@extends('layouts.admin')

@section('title', 'Detail Pelanggan: ' . $customer->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">Informasi Kontak</h3>
            <p><strong>Nama:</strong> {{ $customer->name }}</p>
            <p><strong>Email:</strong> {{ $customer->email ?? '-' }}</p>
            <p><strong>WhatsApp:</strong> {{ $customer->whatsapp }}</p>
            <p><strong>Telepon:</strong> {{ $customer->phone ?? '-' }}</p>
            <p><strong>Alamat:</strong> {{ $customer->address }}</p>
        </div>
    </div>
    <div class="lg:col-span-2">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-xl font-bold mb-4">Riwayat Pesanan</h3>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-200 text-left text-sm">
                            <th class="py-2 px-4">Nomor Pesanan</th>
                            <th class="py-2 px-4">Tanggal</th>
                            <th class="py-2 px-4">Total</th>
                            <th class="py-2 px-4">Status</th>
                            <th class="py-2 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customer->orders as $order)
                        <tr class="border-b">
                            <td class="py-2 px-4">{{ $order->order_number }}</td>
                            <td class="py-2 px-4">{{ $order->created_at->format('d/m/Y') }}</td>
                            <td class="py-2 px-4">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="py-2 px-4">{{ $order->status }}</td>
                            <td class="py-2 px-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-500 hover:underline">Lihat</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Pelanggan ini belum memiliki pesanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
