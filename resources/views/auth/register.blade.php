<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-blue-200 to-blue-300">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-sm p-4">
            <div class="flex flex-col items-center mb-4">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 mb-1">
                <span class="text-xl font-bold text-cyan-700 text-center leading-tight">
                    siPredi <span class="text-yellow-500">SMP ISLAM</span> TULUNGAGUNG
                </span>
                <div class="mt-1 text-base font-semibold text-gray-700 text-center">
                    <span class="text-gray-700 font-bold">Sistem Presensi Digital</span>
                </div>
            </div>
            <div class="mb-4 text-center">
                <span class="text-lg font-bold text-gray-700">Register</span>
                <div class="text-xs text-gray-500 mt-1">Silakan isi data untuk membuat akun siPredi.</div>
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-2">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-cyan-500 focus:border-cyan-500 text-sm mt-1 bg-white text-gray-900"
                        type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1" />
                </div>

                <!-- Username (ganti Email) -->
                <div class="mb-2">
                    <x-input-label for="username" :value="__('Username')" />
                    <x-text-input id="username" class="block w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-cyan-500 focus:border-cyan-500 text-sm mt-1"
                        type="text" name="username" :value="old('username')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-1" />
                </div>

                <!-- Password -->
                <div class="mb-2">
                    <x-input-label for="password" :value="__('Password')" />
                    <div class="relative">
                        <x-text-input id="password" class="block w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-cyan-500 focus:border-cyan-500 text-sm mt-1"
                            type="password" name="password" required autocomplete="new-password" />
                        <button type="button" onclick="togglePassword('password', 'eye1')" class="absolute inset-y-0 right-0 px-2 flex items-center text-gray-400">
                            <svg id="eye1" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-2">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <div class="relative">
                        <x-text-input id="password_confirmation" class="block w-full px-3 py-1.5 border border-gray-300 rounded-md focus:ring-cyan-500 focus:border-cyan-500 text-sm mt-1"
                            type="password" name="password_confirmation" required autocomplete="new-password" />
                        <button type="button" onclick="togglePassword('password_confirmation', 'eye2')" class="absolute inset-y-0 right-0 px-2 flex items-center text-gray-400">
                            <svg id="eye2" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                </div>

                <div class="flex items-center justify-end mt-3">
                    <a class="underline text-xs text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>
                    <x-primary-button class="ms-2 py-1.5 px-4 text-sm">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>
            <div class="mt-4 text-xs text-gray-700 text-center leading-tight">
                <span class="font-bold text-cyan-700">SMK Islam Tulungagung</span><br>
                Jl. Patah Jali, No. 34, Batangsaren, Kauman<br>
                Kabupaten Tulungagung, Kode Pos 66261<br>
                Telepon: 087842 (949212)<br>
                Website: <a href="https://www.sat-project.me" class="text-blue-600 underline">https://sat-project.me</a>
            </div>
            <div class="mt-2 text-xs text-gray-500 text-center">
                Copyright &copy; 2025 <span class="font-bold text-yellow-500">SAT Project</span>.
            </div>
        </div>
    </div>
    <script>
        function togglePassword(inputId, eyeId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            if (input.type === 'password') {
                input.type = 'text';
                eye.style.opacity = 0.5;
            } else {
                input.type = 'password';
                eye.style.opacity = 1;
            }
        }
    </script>
</x-guest-layout>
