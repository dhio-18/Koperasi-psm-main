<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Lupa kata sandi Anda? Tidak masalah. Cukup beri tahu kami alamat email Anda, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi sehingga Anda dapat membuat yang baru.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Tombol kirim tautan -->
        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Kirim Tautan Reset Kata Sandi') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-4 flex items-center justify-start gap-2">
        <span class="text-sm text-gray-500">
            Sudah ingat password?
        </span>
        <a href="{{ route('login') }}"
           class="text-sm text-green-600 hover:text-green-700 font-semibold transition-colors duration-200">
            Kembali ke Halaman Login
        </a>
    </div>

    <!-- Pesan tambahan -->
    <p class="mt-6 text-xs text-gray-500 text-center">
        Pastikan Anda memeriksa folder <span class="font-medium text-gray-700">Spam</span> atau
        <span class="font-medium text-gray-700">Junk</span> jika email tidak muncul dalam beberapa menit.
    </p>
</x-guest-layout>
