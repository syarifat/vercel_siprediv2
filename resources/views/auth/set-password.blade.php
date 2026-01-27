<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password Guru - siPredi</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>
</head>
<body class="bg-orange-50/50 min-h-screen flex items-center justify-center p-4 text-[#1b1b18]">
    
    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8 border border-orange-100 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-orange-400 to-amber-500"></div>

        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 bg-orange-100 text-orange-600 rounded-xl mb-4 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 14l-1 1-1 1H6v2H2v-2l4.257-4.257A6 6 0 1119.5 9z"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Aktivasi Akun Guru</h2>
            <p class="text-sm text-gray-500 mt-1">Buat password baru untuk akun yang didaftarkan Admin.</p>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-3 rounded flex items-start">
                <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm text-red-700">{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-3 rounded flex items-start">
                <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="text-sm text-green-700">{{ session('success') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('set-password.store') }}">
            @csrf
            
            <div class="mb-5">
                <label for="email" class="block text-xs font-bold text-gray-700 uppercase mb-1 ml-1">Email Sekolah / Pribadi</label>
                <input type="email" name="email" id="email" value="{{ old('email', $email ?? '') }}" required autofocus
                    placeholder="contoh@sekolah.id"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition text-sm shadow-sm">
                @error('email')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-5">
                <label for="password" class="block text-xs font-bold text-gray-700 uppercase mb-1 ml-1">Password Baru</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        placeholder="Minimal 8 karakter"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition text-sm shadow-sm">
                    <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-orange-500 transition">
                        <svg id="eye-password" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-8">
                <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase mb-1 ml-1">Ulangi Password</label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        placeholder="Ketik ulang password"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition text-sm shadow-sm">
                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-orange-500 transition">
                        <svg id="eye-password_confirmation" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 mb-4">
                Simpan Password Baru
            </button>
            
            <a href="{{ route('login') }}" class="flex items-center justify-center gap-2 w-full text-center bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-2.5 px-4 rounded-lg transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Login
            </a>
        </form>
    </div>

    <script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const eye = document.getElementById('eye-' + id);
        if (input.type === 'password') {
            input.type = 'text';
            eye.style.stroke = '#f97316'; // orange color
        } else {
            input.type = 'password';
            eye.style.stroke = 'currentColor';
        }
    }
    </script>
</body>
</html>