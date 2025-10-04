<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-blue-200 to-blue-300">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-8">
            <!-- Logo & Judul -->
            <div class="flex flex-col items-center mb-6">
                <img src="{{ asset('logo.svg') }}" alt="Logo" class="h-12 mb-2">
                <span class="text-3xl font-bold text-cyan-700 text-center leading-tight">
                    siPredi <span class="text-yellow-500">SMP ISLAM</span> TULUNGAGUNG
                </span>
                <div class="mt-2 text-lg font-semibold text-gray-700 text-center">
                    <span class="text-gray-700 text-2xl font-bold">Sistem Presensi Digital</span>
                </div>
            </div>
            <!-- Form Login -->
            <div class="mb-6">
                <span class="text-2xl font-bold text-gray-700">Sign<span class="text-cyan-700">In</span></span>
                <div class="text-sm text-gray-500 mt-1">Gunakan Username & Password <strong>siPredi</strong> Anda.</div>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-4">
                    <input id="email" type="text" name="email" placeholder="Username" required autofocus autocomplete="username"
                        class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-cyan-500 focus:border-cyan-500" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="mb-4">
                    <div class="relative">
                        <input id="password" type="password" name="password" placeholder="Password" required autocomplete="current-password"
                            class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-cyan-500 focus:border-cyan-500" />
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400">
                            <svg id="eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="flex items-center justify-between mb-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-cyan-600 shadow-sm focus:ring-cyan-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-cyan-600 hover:underline" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                    
                </div>
                <button type="submit" class="w-full py-2 rounded-md bg-cyan-600 text-white font-bold shadow hover:bg-cyan-700 transition">Sign In</button>
            </form>
            <!-- Info untuk guru yang didaftarkan admin -->
            <div class="mt-6 text-center">
                <span class="text-sm text-gray-600">
                    Guru yang didaftarkan oleh admin dan belum memiliki password, silakan <a href="{{ route('set-password.create') }}" class="text-cyan-700 font-bold underline">atur password di sini</a>.
                </span>
            </div>
            <!-- Info Sekolah -->
            <div class="mt-8 text-xs text-gray-700 text-center">
                <span class="font-bold text-cyan-700">SMK Islam Tulungagung</span><br>
                Jl. Patah Jali, No. 34, Batangsaren, Kauman<br>
                Kabupaten Tulungagung, Kode Pos 66261<br>
                Telepon: 087842 (949212)<br>
                Website: <a href="https://www.sat-project.me" class="text-blue-600 underline">https://sat-project.me</a>
            </div>
            <div class="mt-4 text-xs text-gray-500 text-center">
                Copyright &copy; 2025 <span class="font-bold text-yellow-500">SAT Project</span>.
            </div>
        </div>
    </div>
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eye = document.getElementById('eye');
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
