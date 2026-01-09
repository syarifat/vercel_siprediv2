<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>siPredi - Sistem Presensi Digital Sekolah</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    {{-- CSS Build Assets --}}
    <link rel="stylesheet" href="/build/assets/app-RhMBbNUe.css">
    <script defer src="/build/assets/app-CQzja3Mz.js"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-['Instrument_Sans'] antialiased bg-slate-50 text-[#1b1b18]">

    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-cyan-600 rounded-lg flex items-center justify-center">
                         <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl text-cyan-900 tracking-tight">siPredi</span>
                </div>
                <div>
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-cyan-700 hover:text-cyan-900 transition">Log In Admin</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-28 overflow-hidden">
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full z-0">
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-cyan-100/50 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-blue-100/50 rounded-full blur-3xl opacity-50"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-50 border border-cyan-100 text-cyan-700 text-sm font-medium mb-8">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                </span>
                Sistem Presensi Digital Berbasis IoT
            </div>
            
            <h1 class="text-5xl md:text-7xl font-bold text-slate-900 tracking-tight mb-6 leading-tight">
                Pantau Kehadiran Siswa <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-blue-600">Real-Time & Otomatis</span>
            </h1>

            <p class="text-lg md:text-xl text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
                siPredi mengintegrasikan perangkat IoT presensi dengan WhatsApp Gateway. 
                Notifikasi otomatis langsung ke orang tua saat siswa absen atau terlambat.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-cyan-600 text-white font-semibold rounded-xl hover:bg-cyan-700 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 w-full sm:w-auto">
                        Mulai Monitoring
                    </a>
                @endif
                <a href="#features" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 font-semibold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-colors w-full sm:w-auto">
                    Lihat Cara Kerja
                </a>
            </div>
        </div>
    </section>

    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-4">Keunggulan siPredi</h2>
                <div class="w-20 h-1 bg-cyan-500 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group p-8 bg-slate-50 rounded-2xl border border-slate-100 hover:border-cyan-200 hover:shadow-xl hover:shadow-cyan-500/10 transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">🤖</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Integrasi Perangkat IoT</h3>
                    <p class="text-slate-600 leading-relaxed">Mendukung berbagai perangkat absensi RFID, Fingerprint, atau Face Recognition yang terhubung langsung ke sistem awan.</p>
                </div>
                <div class="group p-8 bg-slate-50 rounded-2xl border border-slate-100 hover:border-cyan-200 hover:shadow-xl hover:shadow-cyan-500/10 transition-all duration-300">
                    <div class="w-14 h-14 bg-cyan-100 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">💬</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">WhatsApp Gateway</h3>
                    <p class="text-slate-600 leading-relaxed">Notifikasi instan dikirim ke nomor WhatsApp orang tua saat siswa melakukan presensi masuk, pulang, atau melanggar aturan.</p>
                </div>
                <div class="group p-8 bg-slate-50 rounded-2xl border border-slate-100 hover:border-cyan-200 hover:shadow-xl hover:shadow-cyan-500/10 transition-all duration-300">
                    <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center text-3xl mb-6 group-hover:scale-110 transition-transform">🖥️</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Monitoring Dashboard</h3>
                    <p class="text-slate-600 leading-relaxed">Pantau statistik kehadiran harian, rekapitulasi bulanan, dan data pelanggaran siswa melalui dashboard web yang interaktif.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <span class="text-cyan-600 font-semibold tracking-wider uppercase text-sm">Di Balik Layar</span>
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-2 mb-4">Our Great Team</h2>
                <p class="text-slate-600 max-w-2xl mx-auto">Tim solid yang berdedikasi membangun sistem monitoring pendidikan terbaik.</p>
            </div>

            <div class="flex flex-wrap justify-center gap-6">
                
                <div class="group relative w-full sm:w-[calc(50%-1.5rem)] lg:w-[calc(20%-1.5rem)] min-w-[240px] h-[380px] rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer">
                    <img src="/img/member1.jpg" alt="Member 1" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.src='https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=800&q=80'">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
                    <div class="absolute bottom-0 left-0 w-full p-6 translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <span class="inline-block px-3 py-1 mb-2 text-xs font-bold text-white bg-cyan-600 rounded-full tracking-wide">LEADERSHIP</span>
                        <h4 class="text-2xl font-bold text-white mb-1">Syarif Ahsani Taqwim</h4>
                        <p class="text-cyan-200 font-medium">Project Manager</p>
                    </div>
                </div>

                <div class="group relative w-full sm:w-[calc(50%-1.5rem)] lg:w-[calc(20%-1.5rem)] min-w-[240px] h-[380px] rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer">
                    <img src="/img/member2.jpg" alt="Member 2" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.src='https://images.unsplash.com/photo-1573496359-136d475583dc?auto=format&fit=crop&w=800&q=80'">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
                    <div class="absolute bottom-0 left-0 w-full p-6 translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <div class="h-1 w-12 bg-cyan-500 mb-3 rounded-full"></div>
                        <h4 class="text-xl font-bold text-white mb-1">Ahmad Hakiki Nugroho</h4>
                        <p class="text-slate-300 font-medium text-sm">IoT Specialist</p>
                    </div>
                </div>

                <div class="group relative w-full sm:w-[calc(50%-1.5rem)] lg:w-[calc(20%-1.5rem)] min-w-[240px] h-[380px] rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer">
                    <img src="/img/member3.jpg" alt="Member 3" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.src='https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=800&q=80'">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
                    <div class="absolute bottom-0 left-0 w-full p-6 translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <div class="h-1 w-12 bg-cyan-500 mb-3 rounded-full"></div>
                        <h4 class="text-xl font-bold text-white mb-1">Andina Septiana Putri</h4>
                        <p class="text-slate-300 font-medium text-sm">Fullstack Developer</p>
                    </div>
                </div>

                <div class="group relative w-full sm:w-[calc(50%-1.5rem)] lg:w-[calc(20%-1.5rem)] min-w-[240px] h-[380px] rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer">
                    <img src="/img/member4.jpg" alt="Member 4" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.src='https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=800&q=80'">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
                    <div class="absolute bottom-0 left-0 w-full p-6 translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <div class="h-1 w-12 bg-cyan-500 mb-3 rounded-full"></div>
                        <h4 class="text-xl font-bold text-white mb-1">Widi Putri Audy</h4>
                        <p class="text-slate-300 font-medium text-sm">Data Analyst</p>
                    </div>
                </div>

                <div class="group relative w-full sm:w-[calc(50%-1.5rem)] lg:w-[calc(20%-1.5rem)] min-w-[240px] h-[380px] rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 cursor-pointer">
                    <img src="/img/member5.jpg" alt="Member 5" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.src='https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=800&q=80'">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
                    <div class="absolute bottom-0 left-0 w-full p-6 translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                        <div class="h-1 w-12 bg-cyan-500 mb-3 rounded-full"></div>
                        <h4 class="text-xl font-bold text-white mb-1">Yosanda Nurfriyandika</h4>
                        <p class="text-slate-300 font-medium text-sm">Tech Specialist</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <footer class="bg-slate-900 text-slate-400 py-8 text-center border-t border-slate-800">
        <p>&copy; {{ date('Y') }} siPredi - Sistem Presensi Digital. All rights reserved.</p>
    </footer>

</body>
</html>