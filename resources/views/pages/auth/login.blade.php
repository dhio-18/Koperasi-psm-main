@extends('layouts.auth-layout')

@section('title')
    <title>Login</title>
@endsection('title')


@section('main')
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
        <!-- Logo -->
        <div class="flex justify-center items-center text-center mb-6">
            <a href="{{ route('home') }}">
                <img src="/logo.svg" alt="logo" width="100" height="100" class="hover:scale-105 transition-transform duration-200">
            </a>
        </div>

        <!-- Title -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Halo, Selamat Datang</h1>
            <div class="text-sm text-gray-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-green-600 hover:underline">Daftar Sekarang</a>
            </div>
        </div>

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Field -->
            <div>
                <input type="email" name="email" id="email" placeholder="Masukkan email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-200"
                    required>
            </div>

            <!-- Password Field -->
            <div>
                <input type="password" name="password" id="password" placeholder="Password"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-200"
                    required>
            </div>

            <!-- Show Password forgot password -->
            <div class="flex items-center justify-between">
                <div>
                    <input type="checkbox" id="show-password" class="mr-2" onclick="togglePassword()">
                    <label for="show-password" class="text-sm text-gray-600">Tampilkan Password</label>
                </div>

                @if (Route::has('password.request'))
                    <div>
                        <a href="{{ route('password.request') }}" class="text-green-500 hover:text-green-600">Lupa Password?</a>
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Masuk
                </button>
            </div>
        </form>

        <!-- Divider -->
        <div class="relative my-2">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">atau</span>
            </div>
        </div>

        <!-- Google Login Button -->
        <div x-data="{ isGoogleLoading: false }">
            <a href="{{ route('google.login') }}" @click="isGoogleLoading = true" :disabled="isGoogleLoading"
                class="w-full border border-gray-300 hover:border-gray-400 bg-white hover:bg-gray-50 text-gray-700 font-medium py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center space-x-3 disabled:opacity-50">
                <svg class="ms-1 w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285f4"
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34a853"
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#fbbc05"
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                    <path fill="#ea4335"
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                </svg>
                <span x-text="isGoogleLoading ? 'Memproses...' : 'Masuk dengan Google'"></span>
            </a>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById('password');
            var showPasswordCheckbox = document.getElementById('show-password');
            if (showPasswordCheckbox.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }

        // Custom validation messages in Indonesian
        document.addEventListener('invalid', function(e) {
            if (e.target.tagName.toLowerCase() !== 'input') return;

            const input = e.target;
            if (input.type === 'email' && input.validity.typeMismatch) {
                input.setCustomValidity('Silahkan masukkan email yang valid');
            } else if (input.validity.valueMissing) {
                input.setCustomValidity('Silahkan isi bidang ini');
            } else {
                input.setCustomValidity('');
            }
        }, true);

        document.querySelectorAll('input[required]').forEach(input => {
            input.addEventListener('input', function() {
                this.setCustomValidity('');
            });
        });
    </script>
@endsection
