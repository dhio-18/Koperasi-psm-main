@extends('layouts.auth-layout')

@section('title')
    <title>Reset Password</title>
@endsection('title')

@section('main')
    <div class="bg-white rounded-2xl shadow-lg p-8 w-full max-w-md">

        <!-- Logo -->
        <div class="flex justify-center items-center text-center mb-6">
            <a href="{{ route('home') }}">
                <img src="/logo.svg" alt="logo" width="100" height="100"
                    class="hover:scale-105 transition-transform duration-200">
            </a>
        </div>

        <!-- Title -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Reset Password</h1>
            <div class="text-sm text-gray-500">
                Silahkan buat password baru anda
            </div>
        </div>

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Field -->
            <div>
                <input type="email" name="email" id="email" placeholder="Email"
                    value="{{ old('email', $request->email) }}"
                    class="w-full px-4 py-3 border rounded-lg bg-gray-100 cursor-not-allowed text-gray-600"
                    readonly required>
            </div>

            <!-- Password Field -->
            <div>
                <input type="password" name="password" id="password" placeholder="Password Baru"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-200"
                    required>
            </div>

            <!-- Confirm Password Field -->
            <div>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password"
                    class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-200"
                    required>
            </div>

            <!-- Show Password -->
            <div class="flex items-center justify-between">
                <div>
                    <input type="checkbox" id="show-password" class="mr-2" onclick="togglePassword()">
                    <label for="show-password" class="text-sm text-gray-600">Tampilkan Password</label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-[1.02] focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Reset Password
                </button>
            </div>
        </form>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mt-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <script>
        function togglePassword() {
            var p1 = document.getElementById('password');
            var p2 = document.getElementById('password_confirmation');
            var checkbox = document.getElementById('show-password');

            if (checkbox.checked) {
                p1.type = 'text';
                p2.type = 'text';
            } else {
                p1.type = 'password';
                p2.type = 'password';
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
