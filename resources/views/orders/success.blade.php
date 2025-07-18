@extends('layouts.app')

@section('title', 'Status Pesanan - AquaFresh Market')

{{-- Menambahkan script Midtrans Snap.js di <head> --}}
@push('scripts')
    @if ($order->payment_method === 'qris' && $order->snap_token)
        <script type="text/javascript"
            src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif
@endpush


@section('content')
    <div class="min-h-screen bg-gray-50 py-12 md:py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">

                <div class="p-6 md:p-8">
                    {{-- KONDISI UNTUK COD --}}
                    @if ($order->payment_method === 'cod')
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-box text-3xl text-black"></i>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">Pesanan COD Berhasil Dibuat!</h1>
                            <p class="text-gray-600">Pesanan Anda akan segera kami siapkan untuk pengiriman.</p>
                        </div>

                        {{-- KONDISI UNTUK QRIS PENDING --}}
                    @elseif ($order->payment_method === 'qris' && $order->payment_status === 'pending')
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-qrcode text-3xl text-teal-600"></i>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">Satu Langkah Lagi!</h1>
                            <p class="text-gray-600">Pesanan Anda telah kami catat. Silakan selesaikan pembayaran.</p>
                        </div>

                        {{-- KONDISI SUKSES UMUM --}}
                    @else
                        <div class="text-center mb-6">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check text-3xl text-green-600"></i>
                            </div>
                            <h1 class="text-3xl font-bold text-gray-800 mb-2">Pesanan Berhasil!</h1>
                            <p class="text-gray-600">Terima kasih, pesanan Anda akan segera kami proses.</p>
                        </div>
                    @endif

                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Ringkasan Pesanan</h2>
                        <div class="space-y-2">
                            <div class="flex justify-between"><span class="text-gray-600">Nomor Pesanan</span><span
                                    class="font-medium">{{ $order->order_number }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Tanggal</span><span
                                    class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-600">Metode</span><span
                                    class="font-medium uppercase">{{ $order->payment_method }}</span></div>
                            <div class="border-t my-2"></div>
                            <div class="flex justify-between text-lg"><span class="font-bold">Total</span><span
                                    class="font-bold text-green-600">Rp
                                    {{ number_format($order->total_amount, 0, ',', '.') }}</span></div>
                        </div>
                    </div>

                    {{-- TOMBOL BAYAR UNTUK QRIS PENDING --}}
                    @if ($order->payment_method === 'qris' && $order->payment_status === 'pending')
                        <div class="text-center">
                            <p class="text-gray-600 mb-4">Klik tombol di bawah untuk menampilkan QRIS dan menyelesaikan
                                pembayaran.</p>
                            <button id="pay-button"
                                class="w-full bg-teal-600 text-white font-bold py-3 rounded-lg hover:bg-teal-700 transition-colors">
                                BAYAR SEKARANG
                            </button>
                        </div>
                        {{-- TAMPILAN QR CODE UNTUK COD --}}
                    @elseif ($order->payment_method === 'cod' && isset($qrCode))
                        <div class="text-center bg-blue-50 p-6 rounded-lg">
                            <h3 class="font-bold text-gray-800">Kode Pesanan Anda</h3>
                            <p class="text-gray-600 mt-2 mb-4">Simpan dan tunjukkan QR Code ini kepada kurir kami saat
                                pengantaran untuk verifikasi dan pembayaran tunai.</p>
                            <div class="flex justify-center">
                                {{-- Tanda {!! !!} digunakan agar Laravel merender tag SVG, bukan menampilkannya sebagai teks biasa --}}
                                {!! $qrCode !!}
                            </div>
                        </div>
                        {{-- TOMBOL KEMBALI KE BERANDA UNTUK KONDISI LAIN --}}
                    @else
                        <div class="text-center">
                            <p class="text-gray-600 mb-4">Tim kami akan segera menghubungi Anda via WhatsApp untuk
                                konfirmasi pengiriman.</p>
                            <a href="{{ route('home') }}"
                                class="w-full md:w-auto inline-block bg-gray-600 text-white py-3 px-8 rounded-lg font-medium hover:bg-gray-700 transition-colors">
                                Kembali ke Beranda
                            </a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection

{{-- Menambahkan script untuk trigger Midtrans Snap --}}
@push('scripts')
    @if ($order->payment_method === 'qris' && $order->snap_token)
        <script type="text/javascript">
            var payButton = document.getElementById('pay-button');
            payButton.addEventListener('click', function() {
                window.snap.pay('{{ $order->snap_token }}', {
                    onSuccess: function(result) {
                        alert("Pembayaran sukses!");
                        console.log(result);
                        window.location.reload();
                    },
                    onPending: function(result) {
                        alert("Menunggu pembayaran Anda!");
                        console.log(result);
                    },
                    onError: function(result) {
                        alert("Pembayaran gagal!");
                        console.log(result);
                    },
                    onClose: function() {
                        alert('Anda menutup pop-up tanpa menyelesaikan pembayaran');
                    }
                });
            });
        </script>
    @endif
@endpush
