<!-- Modern Sidebar dengan Shadcn UI Style -->
<div class="w-72 bg-white rounded-xl shadow-sm border border-gray-200/60 overflow-hidden">
    <!-- Header Profile -->
    <div class="p-6 border-b border-gray-100">
        <div class="flex items-center space-x-3">
            <div
                class="me-1 w-8 h-8 rounded-full border-4 border-gray-200 bg-gray-100 overflow-hidden relative shadow-md">
                <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Profile Picture"
                    class="w-full h-full object-cover">
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 text-sm">{{ Auth::user()->name }}</h3>
                <p class="text-xs text-gray-500">Member sejak {{ Auth::user()->created_at->format('Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="p-4">
        <nav class="space-y-1">
            <!-- Profile Menu -->
            <a href="{{ route('user.profile.edit') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('user.profile.edit') ? 'bg-green-50 text-green-700 border-r-2 border-green-600 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <div
                    class="flex items-center justify-center w-5 h-5 mr-3 {{ request()->routeIs('user.profile.edit') ? 'text-green-600' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span>Profile</span>
                @if(request()->routeIs('user.profile.edit'))
                    <div class="ml-auto w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                @endif
            </a>

            <!-- Pesanan Menu -->
            <a href="{{ route('user.profile.orders') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('user.profile.orders') ? 'bg-green-50 text-green-700 border-r-2 border-green-600 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <div
                    class="flex items-center justify-center w-5 h-5 mr-3 {{ request()->routeIs('user.profile.orders') ? 'text-green-600' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M8 11v6h8v-6M8 11H6a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2v-6a2 2 0 00-2-2h-2">
                        </path>
                    </svg>
                </div>
                <span>Pesanan</span>
                @if(request()->routeIs('user.profile.orders'))
                    <div class="ml-auto w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                @endif
            </a>

            <!-- Alamat Menu -->
            <a href="{{ route('user.profile.address') }}"
                class="group flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('user.profile.address') ? 'bg-green-50 text-green-700 border-r-2 border-green-600 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <div
                    class="flex items-center justify-center w-5 h-5 mr-3 {{ request()->routeIs('user.profile.address') ? 'text-green-600' : 'text-gray-400 group-hover:text-gray-600' }}">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <span>Alamat</span>
                @if(request()->routeIs('user.profile.address'))
                    <div class="ml-auto w-1.5 h-1.5 bg-green-600 rounded-full"></div>
                @endif
            </a>
        </nav>

        <!-- Logout Button -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="group w-full flex items-center px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 text-red-600 hover:bg-red-50">
                    <div class="flex items-center justify-center w-5 h-5 mr-3 text-red-500">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                    </div>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
</div>
