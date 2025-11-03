@props(['title', 'categories', 'filters', 'buttonLabel'])

<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <h1 class="text-3xl font-bold text-gray-900">{{ $title }}</h1>
</div>

<!-- Search and Filters -->
<div class="flex flex-col md:flex-row gap-4 mb-6">
    {{-- Search Input --}}
    <div class="flex-1 relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        <input type="text" x-model="searchQuery" @input="filterProducts" placeholder="Cari produk..."
            class="shadow-lg w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-colors">
    </div>

    {{-- Category Filter --}}
    <div class="relative">
        <select x-model="categoryFilter" @change="filterProducts"
            class="shadow-lg appearance-none bg-white border border-gray-300 rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-colors min-w-48">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
            @endforeach
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </div>

    {{-- Sort Filter --}}
    <div class="relative">
        <select x-model="sortFilter" @change="filterProducts"
            class="shadow-lg appearance-none bg-white border border-gray-300 rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-colors min-w-32">
            @foreach ($filters as $filter)
                <option value="{{ $filter }}">{{ ucfirst(str_replace('_', ' ', $filter)) }}</option>
            @endforeach
        </select>
        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </div>
    </div>

    {{-- Add Button --}}
    <button
        class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-2 shadow-lg rounded-lg flex items-center gap-2 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
            </path>
        </svg>
        {{ $buttonLabel }}
    </button>
</div>
