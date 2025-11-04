<!-- Header -->
<header class="bg-white relative z-40">
    <div
        class="flex flex-wrap items-center justify-between h-auto md:h-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 md:py-0">
        {{-- Logo --}}
        <div class="flex-shrink-0 flex items-center mb-2 md:mb-0">
            <a class="flex items-center">
                <img src="{{ asset('logo.svg') }}" alt="Koperasi PSM" class="w-10 h-10">
            </a>
        </div>

        {{-- Right area --}}
        <div class="flex-shrink-0">
            @guest
                <a href="{{ route('login') }}"
                    class="inline-flex items-center px-5 md:px-6 py-2 border border-green-600 text-green-600 hover:bg-green-600 hover:text-white rounded-full font-medium text-sm md:text-base transition-all">
                    Login
                </a>
            @else
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="inline-flex items-center px-3 md:px-4 py-2 text-gray-700 hover:text-green-600 font-medium transition-colors text-sm md:text-base">
                        <div
                            class="me-1 w-8 h-8 rounded-full border-4 border-gray-200 bg-gray-100 overflow-hidden relative">
                            <img src="{{ asset(Auth::user()->profile_photo_path) }}" alt="Profile Picture"
                                class="w-full h-full object-cover">
                        </div>
                        {{ Auth::user()->name }}
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" x-transition @click.outside="open=false"
                        class="absolute right-0 mt-2 w-44 sm:w-48 bg-white rounded-md shadow-lg py-1 border border-gray-200 z-[120]">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100 transition-colors">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</header>

<!-- Navigation Menu (Hamburger ready, gradient style) -->
<nav x-data="{ open: false }"
    class="sticky top-0 z-20
              bg-gradient-to-r from-green-500 via-green-600 to-green-700
              text-white shadow-md rounded-b-2xl transition-all duration-300
              supports-[padding:max(0px,env(safe-area-inset-top))]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Top bar --}}
        <div class="h-12 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}"
                class="font-semibold tracking-wide text-white text-sm md:text-base">
                Admin Panel
            </a>

            {{-- Desktop links --}}
            <div class="hidden lg:flex items-center gap-x-2">
                <a href="{{ route('admin.dashboard') }}"
                    class="px-3 py-2 rounded-lg transition-colors hover:bg-green-700/60 {{ request()->routeIs('admin.dashboard') ? 'bg-green-700/80' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.carousel.index') }}"
                    class="px-3 py-2 rounded-lg transition-colors hover:bg-green-700/60 {{ request()->routeIs('admin.carousel.index') ? 'bg-green-700/80' : '' }}">
                    Carousel
                </a>
                <a href="{{ route('admin.category') }}"
                    class="px-3 py-2 rounded-lg transition-colors hover:bg-green-700/60 {{ request()->routeIs('admin.category') ? 'bg-green-700/80' : '' }}">
                    Kategori
                </a>
                <a href="{{ route('admin.products') }}"
                    class="px-3 py-2 rounded-lg transition-colors hover:bg-green-700/60 {{ request()->routeIs('admin.products') ? 'bg-green-700/80' : '' }}">
                    Produk
                </a>
                <a href="{{ route('admin.orders') }}"
                    class="px-3 py-2 rounded-lg transition-colors hover:bg-green-700/60 {{ request()->routeIs('admin.orders') ? 'bg-green-700/80' : '' }}">
                    Pesanan
                </a>
                <a href="{{ route('admin.return') }}"
                    class="px-3 py-2 rounded-lg transition-colors hover:bg-green-700/60 {{ request()->routeIs('admin.return') ? 'bg-green-700/80' : '' }}">
                    Pengembalian
                </a>

                @if (Auth::user()->role === 'super_admin')
                    <a href="{{ route('superadmin.manage-users.index') }}"
                        class="px-3 py-2 rounded-lg transition-colors hover:bg-green-700/60 {{ request()->routeIs('superadmin.manage-users.index') ? 'bg-green-700/80' : '' }}">
                        Kelola Admin
                    </a>
                    <a href="{{ route('superadmin.payment-accounts.index') }}"
                        class="px-3 py-2 rounded-lg transition-colors hover:bg-green-700/60 {{ request()->routeIs('superadmin.payment-accounts.index') ? 'bg-green-700/80' : '' }}">
                        Akun Bank
                    </a>
                @endif
            </div>

            {{-- Hamburger (mobile & tablet) --}}
            <button @click="open = !open" @keydown.escape.window="open=false" :aria-expanded="open.toString()"
                aria-controls="admin-mobile-menu" aria-label="Toggle navigation"
                class="lg:hidden inline-flex items-center justify-center p-2 rounded-lg hover:bg-green-700/60 focus:outline-none focus:ring-2 focus:ring-white/50">
                <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Mobile & Tablet panel --}}
        <div id="admin-mobile-menu" x-show="open" x-transition.origin.top @click.outside="open=false"
            class="lg:hidden pb-3 border-t border-white/20 mt-1 space-y-1 sm:space-y-2">
            <a href="{{ route('admin.dashboard') }}"
                class="block px-3 py-2 rounded-lg text-sm sm:text-base hover:bg-green-700/60 transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-green-700/80' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.carousel.index') }}"
                class="block px-3 py-2 rounded-lg text-sm sm:text-base hover:bg-green-700/60 transition-colors {{ request()->routeIs('admin.carousel.index') ? 'bg-green-700/80' : '' }}">
                Carousel
            </a>
            <a href="{{ route('admin.category') }}"
                class="block px-3 py-2 rounded-lg text-sm sm:text-base hover:bg-green-700/60 transition-colors {{ request()->routeIs('admin.category') ? 'bg-green-700/80' : '' }}">
                Kategori
            </a>
            <a href="{{ route('admin.products') }}"
                class="block px-3 py-2 rounded-lg text-sm sm:text-base hover:bg-green-700/60 transition-colors {{ request()->routeIs('admin.products') ? 'bg-green-700/80' : '' }}">
                Produk
            </a>
            <a href="{{ route('admin.orders') }}"
                class="block px-3 py-2 rounded-lg text-sm sm:text-base hover:bg-green-700/60 transition-colors {{ request()->routeIs('admin.orders') ? 'bg-green-700/80' : '' }}">
                Pesanan
            </a>
            <a href="{{ route('admin.return') }}"
                class="block px-3 py-2 rounded-lg text-sm sm:text-base hover:bg-green-700/60 transition-colors {{ request()->routeIs('admin.return') ? 'bg-green-700/80' : '' }}">
                Pengembalian
            </a>

            @if (Auth::user()->role === 'super_admin')
                <a href="{{ route('superadmin.manage-users.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm sm:text-base hover:bg-green-700/60 transition-colors {{ request()->routeIs('superadmin.manage-users.index') ? 'bg-green-700/80' : '' }}">
                    Kelola Admin
                </a>
                <a href="{{ route('superadmin.payment-accounts.index') }}"
                    class="block px-3 py-2 rounded-lg text-sm sm:text-base hover:bg-green-700/60 transition-colors {{ request()->routeIs('superadmin.payment-accounts.index') ? 'bg-green-700/80' : '' }}">
                    Akun Bank
                </a>
            @endif
        </div>
    </div>
</nav>
