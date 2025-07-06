{{-- resources/views/admin/orders/partials/action-buttons.blade.php --}}

{{-- Tombol Detail --}}
<a href="{{ route('admin.orders.show', $order) }}" 
   class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 hover:bg-blue-200 text-blue-600 hover:text-blue-700 transition-colors group"
   title="Lihat Detail">
    <i class="fas fa-eye text-sm group-hover:scale-110 transition-transform"></i>
</a>

{{-- Tombol Edit/Update Status --}}
<button type="button" 
        onclick="openStatusModal({{ $order->id }})"
        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 hover:bg-green-200 text-green-600 hover:text-green-700 transition-colors group"
        title="Update Status">
    <i class="fas fa-edit text-sm group-hover:scale-110 transition-transform"></i>
</button>

{{-- Tombol Print Invoice --}}
<a href="{{ route('admin.orders.invoice', $order) }}" 
   target="_blank"
   class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-purple-100 hover:bg-purple-200 text-purple-600 hover:text-purple-700 transition-colors group"
   title="Print Invoice">
    <i class="fas fa-print text-sm group-hover:scale-110 transition-transform"></i>
</a>

{{-- Tombol More Actions (Dropdown) --}}
<div class="relative inline-block text-left">
    <button type="button" 
            onclick="toggleDropdown({{ $order->id }})"
            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-700 transition-colors group"
            title="More Actions">
        <i class="fas fa-ellipsis-v text-sm group-hover:scale-110 transition-transform"></i>
    </button>
    
    <div id="dropdown-{{ $order->id }}" 
         class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-10 hidden">
        <div class="py-1">
            <a href="{{ route('admin.orders.show', $order) }}" 
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <i class="fas fa-eye mr-2"></i>
                Lihat Detail
            </a>
            <a href="{{ route('admin.orders.edit', $order) }}" 
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <i class="fas fa-edit mr-2"></i>
                Edit Pesanan
            </a>
            <a href="{{ route('admin.orders.invoice', $order) }}" 
               target="_blank"
               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                <i class="fas fa-file-invoice mr-2"></i>
                Download Invoice
            </a>
            <div class="border-t border-gray-100"></div>
            @if($order->status !== 'cancelled')
            <button onclick="cancelOrder({{ $order->id }})"
                    class="flex items-center w-full px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                <i class="fas fa-times-circle mr-2"></i>
                Batalkan Pesanan
            </button>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
// Toggle dropdown menu
function toggleDropdown(orderId) {
    const dropdown = document.getElementById(`dropdown-${orderId}`);
    const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
    
    // Close all other dropdowns
    allDropdowns.forEach(d => {
        if (d.id !== `dropdown-${orderId}`) {
            d.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const isDropdownButton = event.target.closest('[onclick*="toggleDropdown"]');
    if (!isDropdownButton) {
        const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
        allDropdowns.forEach(d => d.classList.add('hidden'));
    }
});

// Open status modal
function openStatusModal(orderId) {
    // Implementasi modal untuk update status
    console.log('Opening status modal for order:', orderId);
}

// Cancel order
function cancelOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')) {
        // Implementasi pembatalan pesanan
        console.log('Cancelling order:', orderId);
    }
}
</script>
@endpush