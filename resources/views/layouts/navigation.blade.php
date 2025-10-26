<div x-data="{ sidebar: true, profileOpen: false, whatsappOpen: false }" class="flex min-h-screen">
    <!-- Sidebar -->
    <aside
        x-show="sidebar"
        x-transition:enter="transition-all duration-300"
        x-transition:enter-start="opacity-0 -translate-x-10"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition-all duration-300"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 -translate-x-10"
        class="bg-white border-r border-gray-200 shadow-sm w-64 flex flex-col fixed inset-y-0 left-0 z-30"
        style="display: none;"
    >
        <!-- Logo & Judul -->
        <div class="flex items-center gap-2 px-6 py-4 border-b border-gray-100">
            <a href="{{ route('dashboard') }}">
                <img src="{{ asset('logo.svg') }}" alt="Logo" class="h-10 w-10">
            </a>
            <span class="font-bold text-lg text-gray-700">Absensi SMP</span>
        </div>
        <!-- Navigation Links -->
        @php
            $role = Auth::user()->role ?? '';
        @endphp

        <nav class="flex-1 py-6 px-4 flex flex-col gap-2">
            {{-- Menu untuk superadmin dan admin (full access) --}}
            @if($role === 'superadmin' || $role === 'admin')
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 font-medium px-3 py-2 rounded transition group">
                    <!-- Dashboard Icon -->
                    <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('absensi.index') }}"
                   class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 font-medium px-3 py-2 rounded transition group">
                    <!-- Absensi Icon -->
                    <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Absensi Siswa
                </a>
                <a href="{{ route('absensi_guru.index') }}"
                   class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 font-medium px-3 py-2 rounded transition group">
                    <!-- Absensi Guru Icon -->
                    <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6H4v12h16V6z" />
                    </svg>
                    Absensi Guru
                </a>
                <a href="{{ route('rombel_siswa.index') }}"
                   class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 font-medium px-3 py-2 rounded transition group">
                    <!-- Rombel Siswa Icon -->
                    <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Rombel Siswa
                </a>
                {{-- Master Data Group --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 font-medium px-3 py-2 rounded transition group w-full">
                        <!-- Master Data Icon -->
                        <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        Master Data
                        <svg class="h-4 w-4 ml-auto text-gray-400 group-hover:text-orange-500 transition" fill="none" viewBox="0 0 20 20">
                            <path d="M5.5 8l4.5 4.5L14.5 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-8 flex flex-col gap-1">
                        <a href="{{ route('siswa.index') }}"
                           class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 px-3 py-2 rounded transition">
                            <!-- Siswa Icon -->
                            <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" fill="none"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 21v-2a4 4 0 014-4h4a4 4 0 014 4v2" />
                            </svg>
                            Siswa
                        </a>
                        <a href="{{ route('kelas.index') }}"
                           class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 px-3 py-2 rounded transition">
                            <!-- Kelas Icon -->
                            <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            Kelas
                        </a>
                        <a href="{{ route('guru.index') }}"
                           class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 px-3 py-2 rounded transition">
                            <!-- Guru Icon -->
                            <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" fill="none"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 21v-2a4 4 0 014-4h4a4 4 0 014 4v2" />
                            </svg>
                            Guru
                        </a>
                        <a href="{{ route('tahun_ajaran.index') }}"
                           class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 px-3 py-2 rounded transition">
                            <!-- Tahun Ajaran Icon -->
                            <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="2" fill="none"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18" />
                            </svg>
                            Tahun Ajaran
                        </a>
                        <a href="{{ route('user.index') }}"
                           class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 px-3 py-2 rounded transition">
                            <!-- User Icon -->
                            <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" fill="none"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 21v-2a4 4 0 014-4h4a4 4 0 014 4v2" />
                            </svg>
                            User
                        </a>
                    </div>
                </div>
                {{-- Menu WhatsApp --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 font-medium px-3 py-2 rounded transition group w-full">
                        <!-- WhatsApp Icon (hitam) -->
                        <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.52 3.48A12 12 0 003.48 20.52a12 12 0 0017.04-17.04zm-8.52 17.04a9.52 9.52 0 119.52-9.52 9.52 9.52 0 01-9.52 9.52zm4.76-7.14c-.2-.1-1.18-.58-1.36-.64-.18-.06-.31-.1-.44.1-.13.2-.5.64-.62.78-.12.14-.23.16-.43.06-.2-.1-.84-.31-1.6-.99-.59-.52-.99-1.16-1.11-1.36-.12-.2-.01-.31.09-.41.09-.09.2-.23.3-.34.1-.11.13-.19.2-.32.07-.13.03-.25-.02-.35-.05-.1-.44-1.06-.6-1.45-.16-.39-.32-.34-.44-.35-.11-.01-.25-.01-.39-.01-.13 0-.34.05-.52.25-.18.2-.7.68-.7 1.66s.72 1.93.82 2.07c.1.14 1.41 2.16 3.42 2.95.48.17.85.27 1.14.34.48.1.92.09 1.27.06.39-.04 1.18-.48 1.35-.95.17-.47.17-.87.12-.95-.05-.08-.18-.13-.38-.23z"/>
                        </svg>
                        WhatsApp
                        <svg class="h-4 w-4 ml-auto text-gray-400 group-hover:text-orange-500 transition" fill="none" viewBox="0 0 20 20">
                            <path d="M5.5 8l4.5 4.5L14.5 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div x-show="open" x-transition class="pl-8 flex flex-col gap-1">
                        <a href="{{ route('whatsapp.index') }}"
                        class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 px-3 py-2 rounded transition">
                            <!-- Broadcast Icon (hitam) -->
                            <svg class="h-4 w-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.5 10a7.5 7.5 0 1115 0 7.5 7.5 0 01-15 0zm7.5-5a5 5 0 100 10 5 5 0 000-10zm0 2a3 3 0 110 6 3 3 0 010-6z"/>
                            </svg>
                            <span class="text-base">Broadcast</span>
                        </a>
                        <a href="{{ route('whatsapp.qr') }}"
                        class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 px-3 py-2 rounded transition">
                            <!-- QR Icon (hitam) -->
                            <svg class="h-4 w-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                                <rect x="3" y="3" width="4" height="4"/>
                                <rect x="13" y="3" width="4" height="4"/>
                                <rect x="3" y="13" width="4" height="4"/>
                                <rect x="13" y="13" width="4" height="4"/>
                            </svg>
                            <span class="text-base">QR</span>
                        </a>
                        <a href="{{ route('whatsapp.report') }}"
                        class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 px-3 py-2 rounded transition">
                            <!-- Report Icon (hitam) -->
                            <svg class="h-4 w-4 text-black" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 3h14v2H3V3zm0 4h14v2H3V7zm0 4h14v2H3v-2zm0 4h14v2H3v-2z"/>
                            </svg>
                            <span class="text-base">Report</span>
                        </a>
                    </div>
                </div>
            @else
            {{-- Menu untuk guru (hanya akses terbatas) --}}
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 font-medium px-3 py-2 rounded transition group">
                    <!-- Dashboard Icon -->
                    <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M13 5v6h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('absensi.index') }}"
                   class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 font-medium px-3 py-2 rounded transition group">
                    <!-- Absensi Icon -->
                    <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Absensi
                </a>
                <a href="{{ route('absensi_guru.index') }}"
                   class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 font-medium px-3 py-2 rounded transition group">
                    <!-- Absensi Guru Icon -->
                    <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 6H4v12h16V6z" />
                    </svg>
                    Absensi Guru
                </a>
                <a href="{{ route('rombel_siswa.index') }}"
                   class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 font-medium px-3 py-2 rounded transition group">
                    <!-- Rombel Siswa Icon -->
                    <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Rombel Siswa
                </a>
                <a href="{{ route('siswa.index') }}"
                   class="flex items-center gap-2 text-gray-700 hover:text-orange-500 hover:bg-orange-100/60 font-medium px-3 py-2 rounded transition group">
                    <!-- Siswa Icon -->
                    <svg class="h-5 w-5 text-black group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="7" r="4" stroke="currentColor" stroke-width="2" fill="none"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 21v-2a4 4 0 014-4h4a4 4 0 014 4v2" />
                    </svg>
                    Siswa
                </a>
            @endif
        </nav>
        <!-- Profile Section -->
        <!-- <div class="px-6 py-4 border-t border-gray-100 flex items-center gap-2">
            <span class="bg-orange-500 text-white rounded-full h-8 w-8 flex items-center justify-center font-bold">
                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
            </span>
            <div>
                <div class="text-gray-700 font-medium">{{ Auth::user()->name }}</div>
                <div class="text-xs text-gray-400">{{ Auth::user()->email }}</div>
            </div>
        </div> -->
    </aside>
    <!-- Main Content -->
    <div :class="sidebar ? 'ml-64' : 'ml-0'"
         class="flex-1 flex flex-col transition-all duration-300 ease-in-out">
        <!-- Topbar -->
        <header class="flex items-center justify-between bg-white border-b border-gray-200 px-4 h-14">
            <!-- Sidebar Toggle Button -->
            <button @click="sidebar = !sidebar" class="text-gray-700 hover:bg-gray-100 rounded p-2">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <!-- Profile Dropdown -->
            <div class="relative">
                <button @click="profileOpen = !profileOpen" class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-100 transition">
                    <span class="bg-orange-500 text-white rounded-full h-8 w-8 flex items-center justify-center font-bold">
                        {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                    </span>
                    <span class="text-gray-700 font-medium">{{ Auth::user()->name }}</span>
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 20 20">
                        <path d="M5.5 8l4.5 4.5L14.5 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <div x-show="profileOpen" x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.away="profileOpen = false"
                     class="absolute right-0 mt-2 w-40 bg-white border border-gray-200 rounded shadow-lg z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Log Out</button>
                    </form>
                </div>
            </div>
        </header>
        <!-- Page Content -->
        <main class="flex-1 bg-gray-50 p-4">
            @yield('content')
        </main>
    </div>
</div>
