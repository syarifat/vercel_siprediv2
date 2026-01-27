<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>siPredi - Sistem Presensi Digital Sekolah</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    {{-- CSS Build Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Fallback Tailwind CDN (jika build belum ready) --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-['Instrument_Sans'] antialiased bg-orange-50/30 text-[#1b1b18]">

    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-orange-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center shadow-lg shadow-orange-500/30">
                         <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl text-orange-900 tracking-tight">siPredi</span>
                </div>
                <div>
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-orange-700 hover:text-orange-900 transition flex items-center gap-1">
                            Log In Admin
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0 pointer-events-none">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-orange-200/40 rounded-full blur-3xl opacity-50 mix-blend-multiply"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-amber-200/40 rounded-full blur-3xl opacity-50 mix-blend-multiply"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-50 border border-orange-200 text-orange-700 text-sm font-medium mb-8 shadow-sm">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-orange-500"></span>
                </span>
                Sistem Presensi Digital Berbasis IoT
            </div>
            
            <h1 class="text-5xl md:text-7xl font-bold text-slate-900 tracking-tight mb-6 leading-tight">
                Pantau Kehadiran Siswa <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-600">Real-Time & Otomatis</span>
            </h1>

            <p class="text-lg md:text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                siPredi mengintegrasikan perangkat IoT presensi dengan WhatsApp Gateway. 
                Notifikasi otomatis langsung ke orang tua saat siswa absen atau terlambat.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-orange-600 text-white font-semibold rounded-xl hover:bg-orange-700 hover:shadow-lg hover:shadow-orange-500/30 hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto">
                        Mulai Monitoring
                    </a>
                @endif
                <a href="#features" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 font-semibold rounded-xl hover:bg-orange-50 hover:border-orange-200 transition-colors w-full sm:w-auto">
                    Lihat Fitur
                </a>
            </div>
        </div>
    </section>

    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Keunggulan siPredi</h2>
                <div class="w-24 h-1.5 bg-orange-500 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group p-8 bg-slate-50/50 rounded-2xl border border-slate-100 hover:border-orange-200 hover:shadow-xl hover:shadow-orange-500/10 transition-all duration-300">
                    <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform shadow-sm">
                        🤖
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-orange-700 transition">Integrasi Perangkat IoT</h3>
                    <p class="text-slate-600 leading-relaxed">Mendukung berbagai perangkat absensi RFID, Fingerprint, atau Face Recognition yang terhubung langsung ke sistem awan.</p>
                </div>

                <div class="group p-8 bg-slate-50/50 rounded-2xl border border-slate-100 hover:border-orange-200 hover:shadow-xl hover:shadow-orange-500/10 transition-all duration-300">
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform shadow-sm">
                        💬
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-orange-700 transition">WhatsApp Gateway</h3>
                    <p class="text-slate-600 leading-relaxed">Notifikasi instan dikirim ke nomor WhatsApp orang tua saat siswa melakukan presensi masuk, pulang, atau melanggar aturan.</p>
                </div>

                <div class="group p-8 bg-slate-50/50 rounded-2xl border border-slate-100 hover:border-orange-200 hover:shadow-xl hover:shadow-orange-500/10 transition-all duration-300">
                    <div class="w-14 h-14 bg-amber-100 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform shadow-sm">
                        🖥️
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3 group-hover:text-orange-700 transition">Monitoring Dashboard</h3>
                    <p class="text-slate-600 leading-relaxed">Pantau statistik kehadiran harian, rekapitulasi bulanan, dan data pelanggaran siswa melalui dashboard web yang interaktif.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-400 py-8 text-center border-t border-slate-800">
        <p>&copy; {{ date('Y') }} <span class="text-orange-500 font-bold">siPredi</span> - Sistem Presensi Digital. All rights reserved.</p>
    </footer>

</body>
</html>