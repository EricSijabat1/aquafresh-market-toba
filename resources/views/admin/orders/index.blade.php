@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-white">Manajemen Pesanan</h2>
            <div class="flex items-center space-x-3">
                <span class="text-blue-100 text-sm">Total: {{ $orders->count() }} pesanan</span>
                <button class="bg-white/20 hover:bg-white/30 text-white px-3 py-1 rounded-md text-sm transition-colors">
                    <i class="fas fa-download mr-1"></i> Export
                </button>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">
                        Nomor Pesanan
                    </th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">
                        Pelanggan
                    </th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">
                        Total
                    </th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">
                        Status
                    </th>
                    <th class="text-left py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th class="text-center py-4 px-6 font-semibold text-gray-700 text-sm uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            <span class="font-medium text-gray-900">#{{ $order->order_number }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-3">
                                {{ strtoupper(substr($order->customer->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">{{ $order->customer->name }}</div>
                                <div class="text-sm text-gray-500">{{ $order->customer->whatsapp }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="font-semibold text-gray-900">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        @include('admin.orders.partials.status-badge', ['status' => $order->status])
                    </td>
                    <td class="py-4 px-6">
                        <div class="text-sm text-gray-900">{{ $order->created_at->format('d M Y') }}</div>
                        <div class="text-xs text-gray-500">{{ $order->created_at->format('H:i') }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <div class="flex items-center justify-center space-x-2">
                            @include('admin.orders.partials.action-buttons', ['order' => $order])
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-12 text-center">
                        <div class="text-gray-400">
                            <i class="fas fa-box-open text-4xl mb-4"></i>
                            <p class="text-lg font-medium">Tidak ada pesanan</p>
                            <p class="text-sm">Pesanan akan muncul di sini ketika ada pelanggan yang memesan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Section -->
    @if($orders->hasPages())
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        {{ $orders->links() }}
    </div>
    @endif
</div>

<!-- Modal untuk Quick Actions (Optional) -->
<div id="quickActionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <button class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-eye mr-2"></i> Lihat Detail
                    </button>
                    <button class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-edit mr-2"></i> Edit Status
                    </button>
                    <button class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded-md">
                        <i class="fas fa-print mr-2"></i> Print Invoice
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Script untuk modal quick actions
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('quickActionModal');
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
});
</script>
@endpush
@endsection