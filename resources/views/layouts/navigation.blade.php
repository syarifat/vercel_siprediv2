@php
    $role = Auth::user()->role ?? '';
    if ($role === 'superadmin') { $role = 'admin'; }

    // Helper classes
    $linkClass = "text-gray-600 hover:text-orange-600 hover:bg-orange-50 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out";
    $mobileLinkClass = "block px-4 py-2 text-base font-medium text-gray-700 hover:text-orange-600 hover:bg-orange-50 rounded-md transition";
    
    // Logic Tahun Ajaran
    $navTahun = session('tahun_ajaran_id');
    if (!$navTahun) {
        $navTahun = \App\Models\TahunAjaran::where('aktif', true)->first()?->id ?? null;
    }
    $navTahunList = \App\Models\TahunAjaran::orderBy('nama','desc')->get();
    $currentTahunAjaran = $navTahunList->first(fn($ta) => (string)$ta->id === (string)$navTahun);
@endphp

<nav x-data="{ mobileMenuOpen: false, profileOpen: false, tahunAjaranOpen: false }" 
     class="bg-white border-b border-gray-200 fixed w-full z-50 top-0 shadow-sm h-16">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 mr-8">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="h-8 w-8">
                    <span class="font-bold text-lg text-gray-700 hidden md:block">siPredi</span>
                </a>

                <div class="hidden md:flex md:space-x-2">
                    @if($role === 'admin')
                        <a href="{{ route('dashboard') }}" class="{{ $linkClass }}">Dashboard</a>
                        
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="{{ $linkClass }} flex items-center gap-1">
                                Absensi
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-cloak class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-100 z-50">
                                <a href="{{ route('absensi.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">Siswa</a>
                                <a href="{{ route('absensi_guru.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">Guru</a>
                            </div>
                        </div>

                        <a href="{{ route('rombel_siswa.index') }}" class="{{ $linkClass }}">Rombel</a>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="{{ $linkClass }} flex items-center gap-1">
                                Master Data
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-cloak class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-100 z-50">
                                <a href="{{ route('siswa.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">Siswa</a>
                                <a href="{{ route('kelas.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">Kelas</a>
                                <a href="{{ route('guru.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">Guru</a>
                                <a href="{{ route('tahun_ajaran.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">Tahun Ajaran</a>
                                <a href="{{ route('user.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">User</a>
                            </div>
                        </div>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false" class="{{ $linkClass }} flex items-center gap-1">
                                WhatsApp
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" x-cloak class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-100 z-50">
                                <a href="{{ route('whatsapp.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">Broadcast</a>
                                <a href="{{ route('whatsapp.report') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-orange-50">Laporan</a>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('dashboard') }}" class="{{ $linkClass }}">Dashboard</a>
                        <a href="{{ route('absensi.index') }}" class="{{ $linkClass }}">Absensi Siswa</a>
                        <a href="{{ route('absensi_guru.index') }}" class="{{ $linkClass }}">Absensi Guru</a>
                        <a href="{{ route('rombel_siswa.index') }}" class="{{ $linkClass }}">Rombel</a>
                        <a href="{{ route('siswa.index') }}" class="{{ $linkClass }}">Data Siswa</a>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2">
                
                <div class="relative">
                    <button @click="tahunAjaranOpen = !tahunAjaranOpen" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100 transition">
                        <span class="bg-orange-100 text-orange-700 border border-orange-200 rounded px-2 py-1 text-xs font-bold whitespace-nowrap">
                            {{ $currentTahunAjaran ? "{$currentTahunAjaran->nama} - {$currentTahunAjaran->semester}" : "Pilih TA" }}
                        </span>
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 20 20"><path d="M5.5 8l4.5 4.5L14.5 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <div x-show="tahunAjaranOpen" @click.away="tahunAjaranOpen = false" x-cloak
                         class="absolute right-0 mt-2 w-64 bg-white border border-gray-200 rounded shadow-lg z-50 max-h-80 overflow-y-auto">
                        @foreach($navTahunList as $ta)
                            <button onclick="changeTahunAjaran('{{ $ta->id }}')" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 flex items-center justify-between">
                                <span>{{ $ta->nama }} - {{ $ta->semester }}</span>
                                @if($ta->aktif) <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded">Aktif</span> @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="relative hidden md:block">
                    <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 transition">
                        <div class="bg-orange-500 text-white rounded-full h-8 w-8 flex items-center justify-center font-bold text-sm">
                            {{ strtoupper(substr(Auth::user()->role ?? 'U',0,1)) }}
                        </div>
                        <span class="text-sm font-medium text-gray-700">{{ ucfirst(Auth::user()->role ?? '') }}</span>
                    </button>
                    <div x-show="profileOpen" @click.away="profileOpen = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg py-1 z-50">
                         <div class="px-4 py-2 border-b border-gray-100 bg-gray-50">
                             <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                             <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                         </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Log Out</button>
                        </form>
                    </div>
                </div>

                <button @click="mobileMenuOpen = true" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-orange-600 hover:bg-gray-100 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="mobileMenuOpen" class="relative z-50 md:hidden" aria-modal="true" x-cloak>
        
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm"></div>

        <div class="fixed inset-0 flex">
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 @click.away="mobileMenuOpen = false"
                 class="relative mr-16 flex w-full max-w-xs flex-1 flex-col bg-white pt-5 pb-4 shadow-xl">
                
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button @click="mobileMenuOpen = false" class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Close sidebar</span>
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="flex flex-shrink-0 items-center px-4 border-b border-gray-100 pb-4">
                    <img class="h-8 w-auto mr-2" src="{{ asset('logo.png') }}" alt="Logo">
                    <span class="font-bold text-lg text-gray-800">siPredi Mobile</span>
                </div>

                <div class="mt-5 h-full overflow-y-auto px-2 space-y-1">
                    @if($role === 'admin')
                        <a href="{{ route('dashboard') }}" class="{{ $mobileLinkClass }}">Dashboard</a>
                        
                        <div x-data="{ open: false }" class="space-y-1">
                            <button @click="open = !open" class="{{ $mobileLinkClass }} w-full flex justify-between items-center">
                                <span>Absensi</span>
                                <svg :class="open ? 'rotate-180' : ''" class="h-5 w-5 transform transition-transform text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" class="pl-4 space-y-1 bg-gray-50 rounded-md">
                                <a href="{{ route('absensi.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-orange-600">Siswa</a>
                                <a href="{{ route('absensi_guru.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-orange-600">Guru</a>
                            </div>
                        </div>

                        <a href="{{ route('rombel_siswa.index') }}" class="{{ $mobileLinkClass }}">Rombel Siswa</a>

                        <div x-data="{ open: false }" class="space-y-1">
                            <button @click="open = !open" class="{{ $mobileLinkClass }} w-full flex justify-between items-center">
                                <span>Master Data</span>
                                <svg :class="open ? 'rotate-180' : ''" class="h-5 w-5 transform transition-transform text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" class="pl-4 space-y-1 bg-gray-50 rounded-md">
                                <a href="{{ route('siswa.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-orange-600">Siswa</a>
                                <a href="{{ route('kelas.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-orange-600">Kelas</a>
                                <a href="{{ route('guru.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-orange-600">Guru</a>
                                <a href="{{ route('tahun_ajaran.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-orange-600">Tahun Ajaran</a>
                                <a href="{{ route('user.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-orange-600">User</a>
                            </div>
                        </div>

                        <div x-data="{ open: false }" class="space-y-1">
                            <button @click="open = !open" class="{{ $mobileLinkClass }} w-full flex justify-between items-center">
                                <span>WhatsApp</span>
                                <svg :class="open ? 'rotate-180' : ''" class="h-5 w-5 transform transition-transform text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="open" class="pl-4 space-y-1 bg-gray-50 rounded-md">
                                <a href="{{ route('whatsapp.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-orange-600">Broadcast</a>
                                <a href="{{ route('whatsapp.qr') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-orange-600">QR Scan</a>
                                <a href="{{ route('whatsapp.report') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-orange-600">Laporan</a>
                            </div>
                        </div>

                    @else
                        <a href="{{ route('dashboard') }}" class="{{ $mobileLinkClass }}">Dashboard</a>
                        <a href="{{ route('absensi.index') }}" class="{{ $mobileLinkClass }}">Absensi Siswa</a>
                        <a href="{{ route('absensi_guru.index') }}" class="{{ $mobileLinkClass }}">Absensi Guru</a>
                        <a href="{{ route('rombel_siswa.index') }}" class="{{ $mobileLinkClass }}">Rombel Siswa</a>
                        <a href="{{ route('siswa.index') }}" class="{{ $mobileLinkClass }}">Data Siswa</a>
                    @endif
                </div>

                <div class="border-t border-gray-200 p-4">
                    <div class="flex items-center">
                        <div class="h-10 w-10 flex-shrink-0 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(Auth::user()->role ?? 'U',0,1)) }}
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ ucfirst(Auth::user()->role ?? '') }}</div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                            Log Out
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
    
    <script>
        async function changeTahunAjaran(id) {
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (!tokenMeta) {
                alert('Error: CSRF token tidak ditemukan');
                return;
            }
            const token = tokenMeta.getAttribute('content');
            
            try {
                const response = await fetch("{{ route('tahun_ajaran.set') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({ id: id || null })
                });
                
                if (response.ok) {
                    window.location.reload();
                } else {
                    alert('Gagal mengubah tahun ajaran.');
                }
            } catch (err) {
                console.error(err);
                alert('Terjadi kesalahan koneksi.');
            }
        }
    </script>
</nav>