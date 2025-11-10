<x-guest-layout>
    <!-- Logo -->
    <div class="flex justify-center items-center text-center mb-6">
        <a href="{{ route('home') }}">
            <img src="/logo.svg" alt="logo" width="80" height="80" class="hover:scale-105 transition-transform duration-200">
        </a>
    </div>

    <!-- Title -->
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Lupa Password?</h1>
        <div class="text-sm text-gray-500">
            Cukup beri tahu kami alamat email Anda, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi
        </div>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('status') }}
        </div>
    @endif

    <!-- Forgot Password Form -->
    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Field -->
        <div>
            <input type="email" name="email" id="email" placeholder="Masukkan email Anda" value="{{ old('email') }}"
                class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-200"
                required autofocus>
        </div>

        <!-- Error Messages -->
        @if($errors->has('email'))
            <div class="p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="text-sm">
                    @foreach($errors->get('email') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Submit Button -->
        <div class="pt-4">
            <button type="submit"
                class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Kirim Tautan Reset Password
            </button>
        </div>
    </form>

    <!-- Back to Login Link -->
    <div class="mt-6 text-center">
        <span class="text-sm text-gray-500">
            Sudah ingat password?
        </span>
        <a href="{{ route('login') }}"
            class="block text-sm text-green-600 hover:text-green-700 font-semibold transition-colors duration-200 mt-1">
            Kembali ke Halaman Login
        </a>
    </div>

    <!-- Info Message -->
    <div class="mt-6 p-3 bg-blue-50 border border-blue-200 rounded-lg">
        <p class="text-xs text-blue-700 text-center">
            <strong>Tips:</strong> Pastikan Anda memeriksa folder <span class="font-medium">Spam</span> atau
            <span class="font-medium">Junk</span> jika email tidak muncul dalam beberapa menit.
        </p>
    </div>
</x-guest-layout>
