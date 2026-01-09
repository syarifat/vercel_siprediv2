<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SIPREDI - Sistem Presensi Digital</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* ... (Style Tailwind v4 Anda tetap di sini) ... */
                /* Pastikan variabel warna orange tetap digunakan */
            </style>
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            <div class="flex items-center justify-between">
                <div class="font-bold text-xl flex items-center gap-2">
                    <span class="text-[#f53003]">⚡</span> SIPREDI
                </div>
                
                @if (Route::has('login'))
                    <nav class="flex items-center justify-end gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                    <div class="mb-4 inline-flex items-center px-3 py-1 rounded-full bg-[#fff2f2] text-[#f53003] font-medium text-[11px] uppercase tracking-wider">
                        ● Sistem Presensi Sekolah #1
                    </div>
                    
                    <h1 class="mb-2 text-2xl lg:text-3xl font-bold leading-tight">
                        Kelola Presensi Sekolah <br>
                        <span class="text-[#f53003]">Lebih Cerdas & Akurat</span>
                    </h1>
                    
                    <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A] text-sm">
                        SIPREDI menghadirkan solusi presensi digital berbasis IoT. Pantau kehadiran siswa secara real-time dan kirim laporan otomatis ke orang tua dalam satu platform.
                    </p>

                    <ul class="flex flex-col mb-8 gap-4">
                        <li class="flex items-start gap-4">
                            <span class="flex shrink-0 items-center justify-center rounded-full bg-[#fff2f2] w-6 h-6 border border-[#f53003]">
                                <span class="text-[#f53003] text-[10px]">✔</span>
                            </span>
                            <div>
                                <h3 class="font-bold text-sm">Presensi IoT (RFID/Fingerprint)</h3>
                                <p class="text-[#706f6c] dark:text-[#A1A09A]">Data kehadiran tercatat otomatis melalui perangkat sensor tanpa antrean panjang.</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <span class="flex shrink-0 items-center justify-center rounded-full bg-[#fff2f2] w-6 h-6 border border-[#f53003]">
                                <span class="text-[#f53003] text-[10px]">✔</span>
                            </span>
                            <div>
                                <h3 class="font-bold text-sm">WhatsApp Gateway Otomatis</h3>
                                <p class="text-[#706f6c] dark:text-[#A1A09A]">Notifikasi kehadiran dikirim langsung ke nomor WhatsApp orang tua secara instan.</p>
                            </div>
                        </li>
                    </ul>

                    <div class="flex gap-3">
                        <a href="{{ route('register') }}" class="inline-block dark:bg-[#eeeeec] dark:border-[#eeeeec] dark:text-[#1C1C1A] dark:hover:bg-white dark:hover:border-white hover:bg-black hover:border-black px-8 py-2.5 bg-[#f53003] rounded-sm border border-[#f53003] text-white font-medium text-sm transition-colors">
                            Mulai Sekarang
                        </a>
                        <a href="#fitur" class="inline-block px-8 py-2.5 border border-[#19140035] dark:border-[#3E3E3A] rounded-sm text-sm font-medium hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                            Pelajari Fitur
                        </a>
                    </div>
                </div>

                <div class="bg-[#fff2f2] dark:bg-[#1D0002] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden flex items-center justify-center">
                    <div class="relative z-10 text-center p-8">
                        <div class="text-6xl mb-4">📱</div>
                        <div class="bg-white/80 backdrop-blur px-4 py-2 rounded-lg shadow-xl inline-block border border-[#f53003]/20">
                            <p class="text-[10px] font-bold text-[#f53003]">PRESENSI BERHASIL</p>
                            <p class="text-xs text-gray-600 font-medium">Siswa: Muhammad Aris</p>
                            <p class="text-[9px] text-gray-400">07:00 AM - Tepat Waktu</p>
                        </div>
                    </div>
                    
                    <div class="absolute inset-0 opacity-20">
                        {!! $placeholderSvg ?? '' !!} 
                    </div>

                    <div class="absolute inset-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"></div>
                </div>
            </main>
        </div>

        <footer class="mt-8 text-[11px] text-[#706f6c] dark:text-[#A1A09A]">
            &copy; {{ date('Y') }} SIPREDI - Sistem Presensi Digital Terintegrasi IoT.
        </footer>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>