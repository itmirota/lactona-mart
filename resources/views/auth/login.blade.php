<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" value="Email" />
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
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" value="Kata Sandi" />

            <div class="relative mt-1">
                <x-text-input
                    id="password"
                    class="block w-full pe-11"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                />

                <!-- Toggle Password Visibility -->
                <button
                    type="button"
                    id="togglePassword"
                    class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-brand-dark focus:outline-none"
                    aria-label="Tampilkan kata sandi"
                >
                    <!-- Icon Eye -->
                    <svg
                        id="eyeIcon"
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    class="rounded border-gray-300 text-brand-dark shadow-sm focus:ring-brand-dark"
                    name="remember"
                >
                <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a
                    class="rounded-md text-sm text-brand-dark underline hover:text-brand-accent focus:outline-none focus:ring-2 focus:ring-brand-dark focus:ring-offset-2"
                    href="{{ route('password.request') }}"
                >
                    Lupa kata sandi?
                </a>
            @endif

            <x-primary-button class="ms-3">
                Masuk
            </x-primary-button>
        </div>
    </form>

    <!-- Password Visibility Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const togglePassword = document.getElementById('togglePassword');
            const eyeIcon = document.getElementById('eyeIcon');

            togglePassword.addEventListener('click', function () {
                const isPassword = passwordInput.type === 'password';

                passwordInput.type = isPassword ? 'text' : 'password';

                togglePassword.setAttribute(
                    'aria-label',
                    isPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'
                );

                eyeIcon.innerHTML = isPassword
                    ? `<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 002.036 12.322a1.012 1.012 0 000 .639C3.423 17.49 7.36 20.5 12 20.5c1.842 0 3.555-.48 5.042-1.32M9.88 9.88a3 3 0 104.24 4.24M6.1 6.1l11.8 11.8M3 3l18 18"/>`
                    : `<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>`;
            });
        });
    </script>
</x-guest-layout>