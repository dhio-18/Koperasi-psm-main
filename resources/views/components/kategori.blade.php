@props(['name', 'image', 'slug'])

<a href="{{ route('products.category', ['categorySlug' => $slug]) }}"
    class="flex flex-col items-center w-20 py-2 px-6 rounded-lg shadow-[0_0_5px_rgb(48,48,48,0.7)] cursor-pointer">
    <img src="{{ asset(''). $image }}" alt="{{ $name }}" class="w-8 h-8 rounded-full object-cover">
    <p class="text-sm text-center font-semibold mt-2">{{ $name }}</p>
    {{ $slot ?? '' }}
</a>
