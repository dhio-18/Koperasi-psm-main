@extends('layouts.auth-layout')

@section('title')
    <title>Register</title>
@endsection('title')

@section('main')

    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">
        <!-- Logo -->
        <div class="flex justify-center items-center text-center mb-6">

            <img src="/logo.svg" alt="logo" width="100" height="100">

        </div>

        <!-- Title -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Daftar Sekarang</h1>
            <div class="text-sm text-gray-500">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-green-600 hover:underline">Masuk Sekarang</a>
            </div>
        </div>

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name Field  -->
            <div>
                <input type="text" name="name" id="name" placeholder="Nama" value="{{ old('name') }}"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-200 @error('name') border-red-500 @enderror"
                    required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Phone Field-->
            <div>
                <input type="text" name="phone" id="phone" placeholder="Nomor Handphone" value="{{ old('phone') }}"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-200 @error('phone') border-red-500 @enderror"
                    oninput="this.value = this.value.replace(/[^0-9]/g,'')" inputmode="numeric" required>
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div>
                <input type="email" name="email" id="email" placeholder="Masukkan email" value="{{ old('email') }}"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-200 @error('email') border-red-500 @enderror"
                    required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div x-data="{ show: false }" class="relative">
                <input :type="show ? 'text': 'password'" name="password" id="password" placeholder="Password"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-200 @error('password') border-red-500 @enderror"
                    required>

                <!-- eye icon -->
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19
                                                                                                            c-4.477 0-8.268-2.943-9.542-7
                                                                                                            a9.956 9.956 0 012.642-3.826M9.88 9.88
                                                                                                            a3 3 0 104.24 4.24M6.228 6.228
                                                                                                            A9.956 9.956 0 002.458 12
                                                                                                            C3.732 16.057 7.523 19 12 19
                                                                                                            c2.03 0 3.918-.607 5.472-1.646M17.772 17.772
                                                                                                            A9.956 9.956 0 0021.542 12
                                                                                                            C20.268 7.943 16.477 5 12 5
                                                                                                            c-1.537 0-2.987.348-4.272.968" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                    </svg>

                </button>
            </div>

            <!-- Confirm Password Field -->
            <div x-data="{ show: false }" class="relative">
                <div> <input :type="show ? 'text':'password'" name="password_confirmation" id="password_confirmation"
                        placeholder="Konfirmasi Password"
                        class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-200"
                        required>

                    <!-- eye icon -->
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-500">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19
                                                                                                                        c-4.477 0-8.268-2.943-9.542-7
                                                                                                                        a9.956 9.956 0 012.642-3.826M9.88 9.88
                                                                                                                        a3 3 0 104.24 4.24M6.228 6.228
                                                                                                                        A9.956 9.956 0 002.458 12
                                                                                                                        C3.732 16.057 7.523 19 12 19
                                                                                                                        c2.03 0 3.918-.607 5.472-1.646M17.772 17.772
                                                                                                                        A9.956 9.956 0 0021.542 12
                                                                                                                        C20.268 7.943 16.477 5 12 5
                                                                                                                        c-1.537 0-2.987.348-4.272.968" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                        </svg>
                    </button>
                </div>

                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

            </div>


            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Daftar
                </button>
            </div>
        </form>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mt-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="text-sm list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <script>
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
