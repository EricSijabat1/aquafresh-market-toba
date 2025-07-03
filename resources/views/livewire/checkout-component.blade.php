<!-- resources/views/livewire/checkout-component.blade.php -->
<div>
    <!-- Checkout Modal -->
    <div x-data="{ showCheckout: @entangle('showCheckout') }" 
         x-show="showCheckout" 
         x-transition 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         @open-checkout.window="showCheckout = true"
         @keydown.escape.window="showCheckout = false">
        
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 max-h-screen overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold">Checkout</h2>