<!-- Footer Section -->
<footer class="bg-gradient-to-br from-gray-50 to-white border-t border-gray-200 mt-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 md:py-12">
    <!-- Brand Logo at Top -->
    <div class="mb-6 sm:mb-8 pb-4 sm:pb-6 border-b border-gray-200">
      <section aria-labelledby="footer-brand">
        <h2 id="footer-brand" class="sr-only">Tentang Koperasi PSM</h2>
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 group w-fit">
          <img
            src="{{ asset('logo.svg') }}"
            alt="Logo Koperasi PSM"
            loading="lazy"
            class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg shadow-sm transition-transform duration-300 group-hover:scale-105 group-hover:shadow-md">
          <span class="text-lg sm:text-xl md:text-2xl font-bold tracking-tight text-gray-900">
            Koperasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-600">PSM</span>
          </span>
        </a>
      </section>
    </div>

    <!-- Footer Content Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 md:gap-10">
      <!-- Quick Links -->
      <nav aria-labelledby="footer-links" class="text-left pb-4 sm:pb-0 border-b sm:border-b-0 border-gray-200">
        <h2 id="footer-links" class="text-gray-900 font-bold text-sm sm:text-base mb-3 sm:mb-4">Tautan Cepat</h2>
        <ul class="space-y-2 sm:space-y-3 text-xs sm:text-sm">
          <li>
            <a href="{{ route('home') }}"
               class="inline-flex items-center gap-2 text-gray-600 hover:text-green-600 hover:translate-x-1 transition-all duration-200">
              <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
              <span>Beranda</span>
            </a>
          </li>
          <li>
            <a href="{{ route('about-us') }}"
               class="inline-flex items-center gap-2 text-gray-600 hover:text-green-600 hover:translate-x-1 transition-all duration-200">
              <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
              <span>Tentang Kami</span>
            </a>
          </li>
          <li>
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-2 text-gray-600 hover:text-green-600 hover:translate-x-1 transition-all duration-200">
              <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
              </svg>
              <span>Produk</span>
            </a>
          </li>
        </ul>
      </nav>

      <!-- Contact -->
      <section aria-labelledby="footer-contact" class="text-left pb-4 sm:pb-0 border-b sm:border-b-0 border-gray-200">
        <h2 id="footer-contact" class="text-gray-900 font-bold text-sm sm:text-base mb-3 sm:mb-4">Hubungi Kami</h2>
        <ul class="space-y-2.5 sm:space-y-3 text-xs sm:text-sm">
          <!-- Alamat -->
          <li class="flex items-start justify-start gap-2.5 sm:gap-3 group">
            <div class="w-8 h-8 sm:w-9 sm:h-9 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-green-100 transition-colors">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
            @php $address = config('app.address', 'PSMI KPM Mart'); @endphp
            <a href="https://www.google.com/maps?q={{ urlencode($address) }}"
               target="_blank" rel="noopener"
               class="text-gray-600 hover:text-green-600 transition-colors text-left flex-1 leading-relaxed">
              {{ $address }}
            </a>
          </li>

          <!-- Telepon -->
          <li class="flex items-start justify-start gap-2.5 sm:gap-3 group">
            <div class="w-8 h-8 sm:w-9 sm:h-9 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-green-100 transition-colors">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
              </svg>
            </div>
            @php $phone = config('app.phone', '+6282285978581'); @endphp
            <a href="tel:{{ $phone }}"
               class="text-gray-600 hover:text-green-600 transition-colors leading-relaxed">
              {{ $phone }}
            </a>
          </li>

          <!-- Email -->
          <li class="flex items-start justify-start gap-2.5 sm:gap-3 group">
            <div class="w-8 h-8 sm:w-9 sm:h-9 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:bg-green-100 transition-colors">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
              </svg>
            </div>
            @php $email = config('app.email', 'koperasipsm@gmail.com'); @endphp
            <a href="mailto:{{ $email }}"
               class="text-gray-600 hover:text-green-600 transition-colors break-all leading-relaxed">
              {{ $email }}
            </a>
          </li>
        </ul>
      </section>

      <!-- Social -->
      <section aria-labelledby="footer-social" class="text-left">
        <h2 id="footer-social" class="text-gray-900 font-bold text-sm sm:text-base mb-3 sm:mb-4">Ikuti Kami</h2>
        <div class="flex flex-wrap justify-start gap-2.5 sm:gap-3">
          <a href="{{ config('app.instagram_url', 'https://www.instagram.com/') }}"
             class="group w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center bg-gradient-to-br from-purple-600 via-pink-600 to-orange-500
                    hover:shadow-lg hover:scale-110 transition-all duration-300"
             target="_blank" rel="noopener" aria-label="Instagram">
            <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 5.838A6.162 6.162 0 1012 18.162 6.162 6.162 0 0012 5.838zm6.406-1.345c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44 1.439-.645 1.439-1.44-.644-1.44-1.439-1.44z"/>
            </svg>
          </a>

          <a href="{{ config('app.facebook_url', 'https://www.facebook.com/kopkarpsmi?locale=id_ID/') }}"
             class="group w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center bg-gradient-to-br from-blue-600 to-blue-700
                    hover:shadow-lg hover:scale-110 transition-all duration-300"
             target="_blank" rel="noopener" aria-label="Facebook">
            <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 7.94 9.8v-6.93H7.1v-2.87h2.84V9.84c0-2.8 1.67-4.34 4.22-4.34 1.22 0 2.5.22 2.5.22v2.75h-1.41c-1.39 0-1.82.87-1.82 1.76v2.12h3.1l-.5 2.87h-2.6v6.93C18.56 20.87 22 16.84 22 12z"/>
            </svg>
          </a>

          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('app.phone', '6282285978581')) }}"
             class="group w-10 h-10 sm:w-11 sm:h-11 rounded-xl flex items-center justify-center bg-gradient-to-br from-green-500 to-green-600
                    hover:shadow-lg hover:scale-110 transition-all duration-300"
             target="_blank" rel="noopener" aria-label="WhatsApp">
            <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
          </a>
        </div>
      </section>

    </div>
  </div>

  <!-- Copyright -->
  <div class="relative bg-gradient-to-r from-green-600 via-emerald-600 to-green-600 overflow-hidden mt-4 sm:mt-5 rounded-t-2xl sm:rounded-t-3xl">
    <!-- Decorative Pattern -->
    <div class="absolute inset-0 opacity-10">
      <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 sm:py-3">
      <div class="flex flex-col sm:flex-row items-center justify-center sm:justify-between gap-1 sm:gap-2">
        <p class="text-white text-[10px] sm:text-xs font-medium text-center sm:text-left">
          © {{ date('Y') }} <span class="font-bold">{{ config('app.name', 'Koperasi PSM') }}</span>
        </p>
        <p class="text-white/80 text-[10px] sm:text-xs text-center sm:text-right">
          Semua hak cipta dilindungi
        </p>
      </div>
    </div>
  </div>
</footer>
