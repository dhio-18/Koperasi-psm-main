<!-- Footer Section -->
<footer>
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
