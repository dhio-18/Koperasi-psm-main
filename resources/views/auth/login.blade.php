<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" id="login-form">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input
                id="password"
                class="block mt-1 w-full"
                type="password"
                name="password"
                required
                autocomplete="current-password"
            />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    name="remember"
                >
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3" id="submit-btn">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Error Alerts (displayed below form) -->
    @if ($errors->has('email'))
        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        @if (str_contains($errors->first('email'), 'terkunci'))
                            <!-- Lock Icon -->
                            <path fill-rule="evenodd" d="M13.477 14.89A6 6 0 015.11 2.526a6 6 0 8.757 8.364H20a1 1 0 110 2h-6.5a1 1 0 01-1-1V7.5a1 1 0 112 0v4.389z" clip-rule="evenodd" />
                        @else
                            <!-- Error Icon -->
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        @endif
                    </svg>
                </div>
                <div class="ms-3 flex-1">
                    <div class="text-sm font-medium text-red-800">
                        {{ $errors->first('email') }}
                    </div>
                    <div id="lockout-timer" class="text-sm text-red-700 mt-2 font-bold" style="display: none;">
                        ⏱️ Coba lagi dalam: <span id="countdown">00:00</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const emailError = @json($errors->first('email') ?? '');
                const lockoutTimer = document.getElementById('lockout-timer');
                const countdownEl = document.getElementById('countdown');
                const submitBtn = document.getElementById('submit-btn');
                const passwordInput = document.getElementById('password');
                const emailInput = document.getElementById('email');

                if (emailError && emailError.includes('terkunci')) {
                    const minuteMatch = emailError.match(/(\d+)\s*menit/);

                    if (minuteMatch) {
                        let totalSeconds = parseInt(minuteMatch[1]) * 60;
                        lockoutTimer.style.display = 'block';

                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        passwordInput.disabled = true;
                        emailInput.disabled = true;

                        const countdownInterval = setInterval(function() {
                            const minutes = Math.floor(totalSeconds / 60);
                            const seconds = totalSeconds % 60;
                            countdownEl.textContent =
                                String(minutes).padStart(2, '0') + ':' +
                                String(seconds).padStart(2, '0');

                            if (totalSeconds <= 0) {
                                clearInterval(countdownInterval);
                                window.location.reload();
                            }
                            totalSeconds--;
                        }, 1000);
                    }
                }
            });
        </script>
    @endpush
</x-guest-layout>
