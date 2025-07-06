{{-- resources/views/admin/orders/partials/status-badge.blade.php --}}
@php
$statusConfig = [
    'pending' => [
        'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'icon' => 'fas fa-clock',
        'label' => 'Menunggu'
    ],
    'confirmed' => [
        'class' => 'bg-blue-100 text-blue-800 border-blue-200',
        'icon' => 'fas fa-check-circle',
        'label' => 'Dikonfirmasi'
    ],
    'processing' => [
        'class' => 'bg-purple-100 text-purple-800 border-purple-200',
        'icon' => 'fas fa-cogs',
        'label' => 'Diproses'
    ],
    'shipped' => [
        'class' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
        'icon' => 'fas fa-truck',
        'label' => 'Dikirim'
    ],
    'delivered' => [
        'class' => 'bg-green-100 text-green-800 border-green-200',
        'icon' => 'fas fa-check-double',
        'label' => 'Diterima'
    ],
    'cancelled' => [
        'class' => 'bg-red-100 text-red-800 border-red-200',
        'icon' => 'fas fa-times-circle',
        'label' => 'Dibatalkan'
    ]
];

$config = $statusConfig[$status] ?? $statusConfig['pending'];
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $config['class'] }}">
    <i class="{{ $config['icon'] }} mr-1"></i>
    {{ $config['label'] }}
</span>