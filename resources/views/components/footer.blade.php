<!-- Footer Section · modern, compact, accessible -->
<footer class="bg-white border-t border-gray-200 mt-10">
  <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-8 md:py-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">

      <!-- Brand -->
      <section aria-labelledby="footer-brand" class="flex flex-col items-center lg:items-start gap-3">
        <h2 id="footer-brand" class="sr-only">Tentang Koperasi PSM</h2>
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
          <img
            src="{{ asset('logo.svg') }}"
            alt="Logo Koperasi PSM"
            loading="lazy"
            class="w-10 h-10 rounded transition-transform duration-200 group-hover:scale-[1.04] motion-reduce:transition-none">
          <span class="text-lg md:text-xl font-bold tracking-tight text-gray-800">
            Koperasi <span class="text-green-600">PSM</span>
          </span>
        </a>
        <p class="text-gray-500 text-sm leading-relaxed text-center lg:text-left">
          Bersama menuju kesejahteraan anggota melalui layanan koperasi yang modern dan transparan.
        </p>
      </section>

      <!-- Quick Links -->
      <nav aria-labelledby="footer-links" class="text-center lg:text-left">
        <h2 id="footer-links" class="text-gray-900 font-semibold text-base md:text-lg mb-2.5">Tautan</h2>
        <ul class="space-y-1.5 text-sm">
          <li>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 text-gray-600 hover:text-green-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 rounded px-1 transition-colors">
              <span>Beranda</span>
            </a>
          </li>
          <li>
            <a href="{{ route('about-us') }}"
               class="inline-flex items-center gap-2 text-gray-600 hover:text-green-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 rounded px-1 transition-colors">
              <span>Tentang Kami</span>
            </a>
          </li>
          <li>
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-2 text-gray-600 hover:text-green-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 rounded px-1 transition-colors">
              <span>Produk</span>
            </a>
          </li>
        </ul>
      </nav>

      <!-- Contact -->
      <section aria-labelledby="footer-contact" class="text-center lg:text-left">
        <h2 id="footer-contact" class="text-gray-900 font-semibold text-base md:text-lg mb-2.5">Kontak</h2>
        <ul class="space-y-2.5 text-sm text-gray-700">
          <!-- Alamat -->
          <li class="flex items-start justify-center lg:justify-start gap-2.5">
            <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            @php $address = config('app.address', 'PSMI KPM Mart'); @endphp
            <a href="https://www.google.com/maps?q={{ urlencode($address) }}"
               target="_blank" rel="noopener"
               class="underline underline-offset-2 decoration-green-500 hover:text-green-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 rounded px-0.5 transition-colors">
              {{ $address }}
              <span class="sr-only">(Buka di Google Maps)</span>
            </a>
          </li>

          <!-- Telepon -->
          <li class="flex items-start justify-center lg:justify-start gap-2.5">
            <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
            @php $phone = config('app.phone', '+6282285978581'); @endphp
            <a href="tel:{{ $phone }}"
               class="hover:text-green-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 rounded px-0.5 transition-colors">
              {{ $phone }}
              <span class="sr-only">(Klik untuk menelepon)</span>
            </a>
          </li>

          <!-- Email -->
          <li class="flex items-start justify-center lg:justify-start gap-2.5">
            <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            @php $email = config('app.email', 'koperasipsm@gmail.com'); @endphp
            <a href="mailto:{{ $email }}"
               class="underline underline-offset-2 decoration-green-500 hover:text-green-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 rounded px-0.5 transition-colors">
              {{ $email }}
              <span class="sr-only">(Kirim email)</span>
            </a>
          </li>
        </ul>
      </section>

      <!-- Social -->
      <section aria-labelledby="footer-social" class="text-center lg:text-left">
        <h2 id="footer-social" class="text-gray-900 font-semibold text-base md:text-lg mb-2.5">Ikuti Kami</h2>
        <div class="flex flex-wrap justify-center lg:justify-start gap-3">
          <a href="{{ config('app.instagram_url', 'https://www.instagram.com/') }}"
             class="w-9 h-9 rounded-full flex items-center justify-center bg-gradient-to-tr from-pink-500 to-pink-500
                    hover:scale-105 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 transition-transform"
             target="_blank" rel="noopener" aria-label="Instagram">
            <svg class="w-4.5 h-4.5 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 5.838A6.162 6.162 0 1012 18.162 6.162 6.162 0 0012 5.838zm6.406-1.345c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44 1.439-.645 1.439-1.44-.644-1.44-1.439-1.44z"/>
            </svg>
          </a>

          <a href="{{ config('app.facebook_url', 'https://www.facebook.com/kopkarpsmi?locale=id_ID/') }}"
             class="w-9 h-9 rounded-full flex items-center justify-center bg-blue-600
                    hover:scale-105 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-500 transition-transform"
             target="_blank" rel="noopener" aria-label="Facebook">
            <svg class="w-4.5 h-4.5 text-white" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 7.94 9.8v-6.93H7.1v-2.87h2.84V9.84c0-2.8 1.67-4.34 4.22-4.34 1.22 0 2.5.22 2.5.22v2.75h-1.41c-1.39 0-1.82.87-1.82 1.76v2.12h3.1l-.5 2.87h-2.6v6.93C18.56 20.87 22 16.84 22 12z"/>
            </svg>
          </a>
        </div>
      </section>

    </div>
  </div>

  <!-- Copyright -->
  <div class="bg-gradient-to-r from-green-500 via-green-600 to-green-700 rounded-t-2xl shadow-inner">
    <div class="max-w-7xl mx-auto px-4 md:px-6 lg:px-8 py-3 md:py-4">
      <p class="text-center text-white text-xs md:text-sm font-medium tracking-wide">
        © {{ date('Y') }} {{ config('app.name', 'Koperasi PSM') }}. Semua hak cipta dilindungi.
      </p>
    </div>
  </div>
</footer>
