@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class=" px-6 py-4">
        <div class="flex items-center justify-between">
  
            <span class="text-black text-sm">Total: {{ $orders->total() }} pesanan</span>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-3 px-6 font-semibold text-gray-600 text-sm uppercase">Nomor Pesanan</th>
                    <th class="text-left py-3 px-6 font-semibold text-gray-600 text-sm uppercase">Pelanggan</th>
                    <th class="text-left py-3 px-6 font-semibold text-gray-600 text-sm uppercase">Total</th>
                    <th class="text-center py-3 px-6 font-semibold text-gray-600 text-sm uppercase">Status</th>
                    <th class="text-left py-3 px-6 font-semibold text-gray-600 text-sm uppercase">Tanggal</th>
                    <th class="text-center py-3 px-6 font-semibold text-gray-600 text-sm uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors" data-order-id="{{ $order->id }}">
                    <td class="py-4 px-6">
                        <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-blue-600 hover:underline">#{{ $order->order_number }}</a>
                    </td>
                    <td class="py-4 px-6">
                        <div class="font-medium text-gray-900 customer-name">{{ $order->customer->name }}</div>
                        <div class="text-sm text-gray-500">{{ $order->customer->whatsapp }}</div>
                    </td>
                    <td class="py-4 px-6 font-semibold text-gray-800">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="py-4 px-6 text-center order-status" data-status="{{ $order->status }}">
                        @include('admin.orders.partials.status-badge', ['status' => $order->status])
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">
                        {{ $order->created_at->format('d M Y, H:i') }}
                    </td>
                    <td class="py-4 px-6 text-center">
                        @include('admin.orders.partials.action-buttons', ['order' => $order])
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center">
                        <div class="text-gray-400">
                            <i class="fas fa-box-open text-4xl mb-3"></i>
                            <p class="text-lg font-medium">Belum ada pesanan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        {{ $orders->links() }}
    </div>
    @endif
</div>

{{-- Modal untuk update status --}}
@include('admin.orders.partials.status-modal')

@endsection
