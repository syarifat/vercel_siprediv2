<x-guest-layout>
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 border border-orange-100 relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-24 h-24 bg-orange-100 rounded-bl-full -mr-4 -mt-4 opacity-50"></div>
        <div class="absolute bottom-0 left-0 w-20 h-20 bg-amber-100 rounded-tr-full -ml-4 -mb-4 opacity-50"></div>

        <div class="flex flex-col items-center mb-8 relative z-10">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-16 mb-3 drop-shadow-sm">
            <div class="text-center">
                <h1 class="text-2xl font-extrabold text-orange-700 leading-tight tracking-tight">
                    <span class="text-amber-500">SMPI AL-HIDAYAH</span> SAMIR
                </h1>
                <p class="text-sm font-medium text-gray-500 mt-1 tracking-widest uppercase">Sistem Presensi Digital</p>
            </div>
        </div>

        <div class="mb-6 relative z-10">
            <h2 class="text-2xl font-bold text-gray-800">Sign<span class="text-orange-600">In</span></h2>
            <p class="text-sm text-gray-500 mt-1">Masuk untuk mengelola presensi siswa & guru.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="relative z-10">
            @csrf

            <div class="mb-5">
                <label for="username" class="block text-xs font-semibold text-gray-700 uppercase mb-1 ml-1">Username</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <input id="username" type="text" name="username" placeholder="Masukkan Username" required autofocus autocomplete="username"
                        class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition text-sm shadow-sm" />
                </div>
                <x-input-error :messages="$errors->get('username')" class="mt-1" />
            </div>

            <div class="mb-5">
                <label for="password" class="block text-xs font-semibold text-gray-700 uppercase mb-1 ml-1">Password</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <input id="password" type="password" name="password" placeholder="Masukkan Password" required autocomplete="current-password"
                        class="block w-full pl-10 pr-10 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition text-sm shadow-sm" />
                    
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-orange-500 transition focus:outline-none">
                        <svg id="eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="flex items-center justify-between mb-6">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">Ingat Saya</span>
                </label>
                </div>

            <button type="submit" class="w-full py-3 rounded-lg bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold text-sm uppercase tracking-wider shadow-lg hover:from-orange-600 hover:to-orange-700 focus:ring-4 focus:ring-orange-300 transition transform hover:-translate-y-0.5">
                Masuk Sekarang
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-dashed border-gray-200 text-center relative z-10">
            <div class="bg-orange-50 rounded-lg p-3 inline-block w-full">
                <p class="text-xs text-gray-600">
                    Guru baru? Belum punya password? <br>
                    <a href="{{ route('set-password.create') }}" class="text-orange-700 font-bold hover:underline flex items-center justify-center gap-1 mt-1">
                        Atur Password Guru Disini
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </p>
            </div>
        </div>

        <div class="mt-8 text-center relative z-10">
            <p class="text-[10px] text-gray-400">
                &copy; 2025 <span class="font-bold text-orange-500">siPredi</span> SMPI Al-Hidayah Samir.<br>
                Developed by <a href="#" class="hover:text-orange-500 transition">SAT Project</a>.
            </p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eye = document.getElementById('eye');
            if (input.type === 'password') {
                input.type = 'text';
                eye.style.stroke = '#f97316'; // orange-500
            } else {
                input.type = 'password';
                eye.style.stroke = 'currentColor';
            }
        }
    </script>
</x-guest-layout>