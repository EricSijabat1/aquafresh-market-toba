{{-- resources/views/admin/orders/partials/status-modal.blade.php --}}

<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-lg">
                <h3 class="text-lg font-semibold text-white">Update Status Pesanan</h3>
            </div>
            
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="p-6">
                    <div class="mb-4">
                        <label for="order_info" class="block text-sm font-medium text-gray-700 mb-2">
                            Pesanan
                        </label>
                        <div id="order_info" class="bg-gray-50 rounded-md p-3">
                            <div class="font-medium text-gray-900" id="order_number"></div>
                            <div class="text-sm text-gray-500" id="customer_name"></div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="status_select" class="block text-sm font-medium text-gray-700 mb-2">
                            Status Baru
                        </label>
                        <select name="status" id="status_select" 
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @foreach([
                                'pending' => 'Menunggu',
                                'confirmed' => 'Dikonfirmasi', 
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'delivered' => 'Diterima',
                                'cancelled' => 'Dibatalkan'
                            ] as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="status_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="notes" id="status_notes" rows="3" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Tambahkan catatan untuk perubahan status..."></textarea>
                    </div>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeStatusModal()"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Batal
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-save mr-1"></i>
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentOrderId = null;

function openStatusModal(orderId) {
    currentOrderId = orderId;
    const modal = document.getElementById('statusModal');
    
    // Fetch order data (you can pass this data from the backend or make an AJAX call)
    // For now, let's get it from the table row
    const orderRow = document.querySelector(`[data-order-id="${orderId}"]`);
    if (orderRow) {
        const orderNumber = orderRow.querySelector('.order-number').textContent;
        const customerName = orderRow.querySelector('.customer-name').textContent;
        const currentStatus = orderRow.querySelector('.order-status').dataset.status;
        
        document.getElementById('order_number').textContent = orderNumber;
        document.getElementById('customer_name').textContent = customerName;
        document.getElementById('status_select').value = currentStatus;
    }
    
    // Update form action
    const form = document.getElementById('statusForm');
    form.action = `/admin/orders/${orderId}/update-status`;
    
    modal.classList.remove('hidden');
}

function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    modal.classList.add('hidden');
    currentOrderId = null;
    
    // Reset form
    document.getElementById('statusForm').reset();
    document.getElementById('status_notes').value = '';
}

// Handle form submission
document.getElementById('statusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    
    // Show loading state
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Updating...';
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showToast('Status berhasil diupdate!', 'success');
            
            // Update the table row without page reload
            updateTableRow(currentOrderId, data.order);
            
            // Close modal
            closeStatusModal();
        } else {
            showToast('Gagal mengupdate status!', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Terjadi kesalahan!', 'error');
    })
    .finally(() => {
        // Reset button state
        submitButton.disabled = false;
        submitButton.innerHTML = '<i class="fas fa-save mr-1"></i> Update Status';
    });
});

// Update table row after status change
function updateTableRow(orderId, orderData) {
    const row = document.querySelector(`[data-order-id="${orderId}"]`);
    if (row) {
        const statusCell = row.querySelector('.status-badge');
        // Update status badge with new status
        // You'll need to implement this based on your status badge component
    }
}

// Simple toast notification function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-4 py-2 rounded-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 'bg-blue-500'
    }`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// Close modal when clicking outside
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStatusModal();
    }
});
</script>
@endpush