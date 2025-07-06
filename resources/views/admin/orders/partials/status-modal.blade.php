{{-- resources/views/admin/orders/partials/status-modal.blade.php --}}
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 transition-opacity duration-300" onclick="closeStatusModal()">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all duration-300 scale-95" onclick="event.stopPropagation();">
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-2">Ubah Status Pesanan</h3>
                    <p class="text-sm text-gray-500 mb-4">Untuk pesanan <strong id="modal_order_number"></strong></p>

                    <label for="status_select" class="block text-sm font-medium text-gray-700 mb-2">Pilih Status Baru</label>
                    <select name="status" id="status_select" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @foreach(['pending' => 'Menunggu', 'confirmed' => 'Dikonfirmasi', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'delivered' => 'Diterima', 'cancelled' => 'Dibatalkan'] as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="bg-gray-50 px-6 py-4 rounded-b-lg flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 font-semibold text-sm">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold text-sm">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openStatusModal(orderId) {
    const modal = document.getElementById('statusModal');
    const form = document.getElementById('statusForm');
    const orderRow = document.querySelector(`tr[data-order-id='${orderId}']`);

    if (orderRow) {
        const orderNumber = orderRow.querySelector('a').textContent;
        const currentStatus = orderRow.querySelector('.order-status').dataset.status;

        document.getElementById('modal_order_number').textContent = orderNumber;
        document.getElementById('status_select').value = currentStatus;
        form.action = `/admin/orders/${orderId}/status`;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.style.opacity = '1';
            modal.querySelector('div > div').style.transform = 'scale(1)';
        }, 10);
    }
}

function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    modal.style.opacity = '0';
    modal.querySelector('div > div').style.transform = 'scale(0.95)';
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}
</script>
@endpush