@props(['status', 'size' => 'md'])

@php
    // Definisi warna status yang konsisten untuk seluruh aplikasi
    $statusColors = [
        'pending' => 'bg-yellow-100 text-yellow-800 border border-yellow-300',
        'waiting' => 'bg-orange-100 text-orange-800 border border-orange-300',
        'verified' => 'bg-cyan-100 text-cyan-800 border border-cyan-300',
        'processing' => 'bg-blue-100 text-blue-800 border border-blue-300',
        'sending' => 'bg-indigo-100 text-indigo-800 border border-indigo-300',
        'shipped' => 'bg-purple-100 text-purple-800 border border-purple-300',
        'completed' => 'bg-green-100 text-green-800 border border-green-300',
        'delivered' => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
        'cancelled' => 'bg-red-100 text-red-800 border border-red-300',
        'rejected' => 'bg-red-100 text-red-800 border border-red-300',
        'returned' => 'bg-pink-100 text-pink-800 border border-pink-300',
    ];

    // Label status dalam Bahasa Indonesia
    $statusLabels = [
        'pending' => 'Menunggu',
        'waiting' => 'Menunggu Pembayaran',
        'verified' => 'Terverifikasi',
        'processing' => 'Diproses',
        'sending' => 'Sedang Dikirim',
        'shipped' => 'Dikirim',
        'completed' => 'Selesai',
        'delivered' => 'Diterima',
        'cancelled' => 'Dibatalkan',
        'rejected' => 'Ditolak',
        'returned' => 'Dikembalikan',
    ];

    // Size classes
    $sizeClasses = [
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-3 py-1.5 text-xs',
        'lg' => 'px-4 py-2 text-sm',
    ];

    $colorClass = $statusColors[$status] ?? 'bg-gray-100 text-gray-800 border border-gray-300';
    $label = $statusLabels[$status] ?? ucfirst($status);
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<span class="inline-flex items-center rounded-full font-semibold {{ $colorClass }} {{ $sizeClass }} {{ $attributes->get('class') }}">
    {{ $label }}
</span>
