@props(['data'])

<div x-show="{{ $data }} === null" class="text-center py-12">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2m-14 0h2">
        </path>
    </svg>
    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada Data ditemukan</h3>
    <p class="mt-1 text-sm text-gray-500">Coba ubah kata kunci pencarian atau filter yang digunakan.</p>
</div>
