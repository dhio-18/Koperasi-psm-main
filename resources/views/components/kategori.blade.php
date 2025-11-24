@props(['name', 'image', 'slug'])

<a href="{{ route('products.category', $slug) }}" 
   class="group flex flex-col items-center gap-2 p-3 rounded-xl bg-white hover:bg-green-50 border border-gray-100 hover:border-green-200 transition-all duration-200 hover:shadow-md">
    
    <!-- Image Container -->
    <div class="w-12 h-12 md:w-14 md:h-14 rounded-lg bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center overflow-hidden group-hover:scale-110 transition-transform duration-200">
        <!-- ✅ BENAR - Langsung pakai $image tanpa tambahan baseUrl -->
        <img 
            src="{{ $image }}" 
            alt="{{ $name }}"
            onerror="this.onerror=null; this.src='{{ asset('category/default.png') }}';"
            class="w-full h-full object-cover"
            loading="lazy"
        >
    </div>
    
    <!-- Category Name -->
    <span class="text-xs md:text-sm font-medium text-gray-700 group-hover:text-green-600 text-center line-clamp-2 transition-colors">
        {{ $name }}
    </span>
</a>