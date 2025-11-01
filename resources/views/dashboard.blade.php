@extends('layouts.app')

@section('content')
<div x-data="{ selected: null, activeView: 'siswa' }" class="max-w-5xl mx-auto mt-10">
    <div class="bg-white shadow rounded-lg p-8 text-center mb-6">
        <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ ucfirst(Auth::user()->role ?? '') }}</h2>
        <p class="text-lg text-gray-600">Tahun Ajaran: {{ $tahunAjaran ? $tahunAjaran->nama . ' - Semester ' . $tahunAjaran->semester : 'Belum dipilih' }}</p>
        
        <!-- Toggle Switch -->
        <div class="flex items-center justify-center mt-4 bg-gray-100 rounded-lg p-1 max-w-xs mx-auto">
            <button @click="activeView = 'siswa'" 
                    :class="{'bg-white shadow-sm': activeView === 'siswa', 'text-gray-600': activeView !== 'siswa'}"
                    class="flex-1 px-4 py-2 rounded-md text-sm font-medium transition-all duration-200">
                Data Siswa
            </button>
            <button @click="activeView = 'guru'"
                    :class="{'bg-white shadow-sm': activeView === 'guru', 'text-gray-600': activeView !== 'guru'}"
                    class="flex-1 px-4 py-2 rounded-md text-sm font-medium transition-all duration-200">
                Data Guru
            </button>
        </div>
    </div>

    <!-- Card untuk Guru -->
    <div x-show="activeView === 'guru'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <!-- Total Guru -->
            <div @click="selected = selected === 'guru_total' ? null : 'guru_total'"
                 :class="{'ring-2 ring-blue-400': selected === 'guru_total'}"
                 class="cursor-pointer bg-blue-100 shadow rounded-lg p-6 text-center flex flex-col items-center hover:ring-2 hover:ring-blue-400 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <div class="text-3xl font-bold text-blue-700">{{ $totalGuru ?? 0 }}</div>
                <div class="mt-2 text-blue-800">Total Guru</div>
            </div>

            <!-- Data Total Guru Table -->
            <div x-show="selected === 'guru_total'" x-transition class="col-span-5 mt-4">
                <h3 class="text-lg font-bold mb-2 text-blue-700">Data Semua Guru</h3>
                <table class="min-w-full border-2 border-blue-400 rounded-lg overflow-hidden shadow border-collapse">
                    <thead>
                        <tr class="bg-blue-500 text-white border-b-2 border-blue-400">
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2 text-center">NIP</th>
                            <th class="px-4 py-2 text-center">No. HP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataAllGuru as $guru)
                        <tr class="bg-white border-b border-blue-200">
                            <td class="px-4 py-2">{{ $guru->nama ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $guru->nip ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $guru->no_hp ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Guru Hadir -->
            <div @click="selected = selected === 'guru_hadir' ? null : 'guru_hadir'"
                 :class="{'ring-2 ring-green-400': selected === 'guru_hadir'}"
                 class="cursor-pointer bg-green-100 shadow rounded-lg p-6 text-center flex flex-col items-center hover:ring-2 hover:ring-green-400 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <div class="text-3xl font-bold text-green-700">{{ $guruHadir ?? 0 }}</div>
                <div class="mt-2 text-green-800">Guru Hadir</div>
            </div>

            <!-- Data Guru Hadir Table -->
            <div x-show="selected === 'guru_hadir'" x-transition class="col-span-5 mt-4">
                <h3 class="text-lg font-bold mb-2 text-green-700">Data Guru Hadir</h3>
                <table class="min-w-full border-2 border-green-400 rounded-lg overflow-hidden shadow border-collapse">
                    <thead>
                        <tr class="bg-green-500 text-white border-b-2 border-green-400">
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2 text-center">NIP</th>
                            <th class="px-4 py-2 text-center">Jam Masuk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataGuruHadir as $row)
                        <tr class="bg-white border-b border-green-200">
                            <td class="px-4 py-2">{{ $row->guru->nama ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->guru->nip ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->jam_masuk ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Guru Sakit/Izin -->
            <div @click="selected = selected === 'guru_sakit' ? null : 'guru_sakit'"
                 :class="{'ring-2 ring-yellow-400': selected === 'guru_sakit'}"
                 class="cursor-pointer bg-yellow-100 shadow rounded-lg p-6 text-center flex flex-col items-center hover:ring-2 hover:ring-yellow-400 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                </svg>
                <div class="text-3xl font-bold text-yellow-700">{{ $guruSakitIzin ?? 0 }}</div>
                <div class="mt-2 text-yellow-800">Guru Sakit/Izin</div>
            </div>

            <!-- Data Guru Sakit/Izin Table -->
            <div x-show="selected === 'guru_sakit'" x-transition class="col-span-5 mt-4">
                <h3 class="text-lg font-bold mb-2 text-yellow-700">Data Guru Sakit/Izin</h3>
                <table class="min-w-full border-2 border-yellow-400 rounded-lg overflow-hidden shadow border-collapse">
                    <thead>
                        <tr class="bg-yellow-500 text-white border-b-2 border-yellow-400">
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2 text-center">NIP</th>
                            <th class="px-4 py-2 text-center">Status</th>
                            <th class="px-4 py-2 text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataGuruSakitIzin as $row)
                        <tr class="bg-white border-b border-yellow-200">
                            <td class="px-4 py-2">{{ $row->guru->nama ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->guru->nip ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->status }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Guru Alpha -->
            <div @click="selected = selected === 'guru_alpha' ? null : 'guru_alpha'"
                 :class="{'ring-2 ring-red-400': selected === 'guru_alpha'}"
                 class="cursor-pointer bg-red-100 shadow rounded-lg p-6 text-center flex flex-col items-center hover:ring-2 hover:ring-red-400 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <div class="text-3xl font-bold text-red-700">{{ $guruTanpaKet ?? 0 }}</div>
                <div class="mt-2 text-red-800">Guru Tanpa Ket.</div>
            </div>

            <!-- Data Guru Alpha Table -->
            <div x-show="selected === 'guru_alpha'" x-transition class="col-span-5 mt-4">
                <h3 class="text-lg font-bold mb-2 text-red-700">Data Guru Tanpa Keterangan</h3>
                <table class="min-w-full border-2 border-red-400 rounded-lg overflow-hidden shadow border-collapse">
                    <thead>
                        <tr class="bg-red-500 text-white border-b-2 border-red-400">
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2 text-center">NIP</th>
                            <th class="px-4 py-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataGuruTanpaKet as $row)
                        <tr class="bg-white border-b border-red-200">
                            <td class="px-4 py-2">{{ $row->guru->nama ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->guru->nip ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Guru Belum Hadir -->
            <div class="bg-gray-100 shadow rounded-lg p-6 text-center flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-3xl font-bold text-gray-700">{{ $guruBelumHadir ?? 0 }}</div>
                <div class="mt-2 text-gray-800">Guru Belum Hadir</div>
            </div>
        </div>
    </div>

    <!-- Card untuk Siswa -->
    <div x-show="activeView === 'siswa'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <!-- Total Siswa -->
            <div @click="selected = selected === 'siswa' ? null : 'siswa'" 
                 :class="{'ring-2 ring-blue-400': selected === 'siswa'}"
                 class="cursor-pointer bg-blue-100 shadow rounded-lg p-6 text-center flex flex-col items-center hover:ring-2 hover:ring-blue-400 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <div class="text-3xl font-bold text-blue-700">{{ $jumlahSiswa ?? 0 }}</div>
                <div class="mt-2 text-blue-800">Total Siswa</div>
            </div>

            <!-- Data Siswa Table -->
            <div x-show="selected === 'siswa'" x-transition class="col-span-5 mt-4">
                <h3 class="text-lg font-bold mb-2 text-blue-700">Data Siswa Aktif</h3>
                <table class="min-w-full border-2 border-blue-400 rounded-lg overflow-hidden shadow border-collapse">
                    <thead>
                        <tr class="bg-blue-500 text-white border-b-2 border-blue-400">
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2 text-center">NIS</th>
                            <th class="px-4 py-2 text-center">Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataSiswaAktif as $row)
                        <tr class="bg-white border-b border-blue-200">
                            <td class="px-4 py-2">{{ $row->siswa->nama ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->siswa->nis ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->kelas->nama ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Hadir -->
            <div @click="selected = selected === 'hadir' ? null : 'hadir'"
                 :class="{'ring-2 ring-green-400': selected === 'hadir'}"
                 class="cursor-pointer bg-green-100 shadow rounded-lg p-6 text-center flex flex-col items-center hover:ring-2 hover:ring-green-400 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <div class="text-3xl font-bold text-green-700">{{ $jumlahHadir ?? 0 }}</div>
                <div class="mt-2 text-green-800">Hadir</div>
            </div>

            <!-- Data Hadir Table -->
            <div x-show="selected === 'hadir'" x-transition class="col-span-5 mt-4">
                <h3 class="text-lg font-bold mb-2 text-green-700">Data Hadir</h3>
                <table class="min-w-full border-2 border-green-400 rounded-lg overflow-hidden shadow border-collapse">
                    <thead>
                        <tr class="bg-green-500 text-white border-b-2 border-green-400">
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2 text-center">NIS</th>
                            <th class="px-4 py-2 text-center">Kelas</th>
                            <th class="px-4 py-2 text-center">Tanggal</th>
                            <th class="px-4 py-2 text-center">Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataHadir as $row)
                        <tr class="bg-white border-b border-green-200">
                            <td class="px-4 py-2">{{ $row->siswa->nama ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->siswa->nis ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->rombel && $row->rombel->kelas ? $row->rombel->kelas->nama : '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->tanggal ? \Carbon\Carbon::parse($row->tanggal)->toDateString() : '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->jam_masuk ?? ($row->jam_masuk ? $row->jam_masuk : '-') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Sakit/Izin -->
            <div @click="selected = selected === 'sakitizin' ? null : 'sakitizin'"
                 :class="{'ring-2 ring-yellow-400': selected === 'sakitizin'}"
                 class="cursor-pointer bg-yellow-100 shadow rounded-lg p-6 text-center flex flex-col items-center hover:ring-2 hover:ring-yellow-400 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                </svg>
                <div class="text-3xl font-bold text-yellow-700">{{ $jumlahSakitIzin ?? 0 }}</div>
                <div class="mt-2 text-yellow-800">Sakit/Izin</div>
            </div>

            <!-- Data Sakit/Izin Table -->
            <div x-show="selected === 'sakitizin'" x-transition class="col-span-5 mt-4">
                <h3 class="text-lg font-bold mb-2 text-yellow-700">Data Sakit/Izin</h3>
                <table class="min-w-full border-2 border-yellow-400 rounded-lg overflow-hidden shadow border-collapse">
                    <thead>
                        <tr class="bg-yellow-500 text-white border-b-2 border-yellow-400">
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2 text-center">NIS</th>
                            <th class="px-4 py-2 text-center">Kelas</th>
                            <th class="px-4 py-2 text-center">Tanggal</th>
                            <th class="px-4 py-2 text-center">Jam</th>
                            <th class="px-4 py-2 text-center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataSakitIzin as $row)
                        <tr class="bg-white border-b border-yellow-200">
                            <td class="px-4 py-2">{{ $row->siswa->nama ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->siswa->nis ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->siswa->rombel && $row->siswa->rombel->kelas ? $row->siswa->rombel->kelas->nama : '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->tanggal ? \Carbon\Carbon::parse($row->tanggal)->toDateString() : '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->jam_masuk ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Tanpa Keterangan -->
            <div @click="selected = selected === 'tanpaket' ? null : 'tanpaket'"
                 :class="{'ring-2 ring-red-400': selected === 'tanpaket'}"
                 class="cursor-pointer bg-red-100 shadow rounded-lg p-6 text-center flex flex-col items-center hover:ring-2 hover:ring-red-400 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <div class="text-3xl font-bold text-red-700">{{ $jumlahTanpaKeterangan ?? 0 }}</div>
                <div class="mt-2 text-red-800">Tanpa Keterangan</div>
            </div>

            <!-- Data Tanpa Keterangan Table -->
            <div x-show="selected === 'tanpaket'" x-transition class="col-span-5 mt-4">
                <h3 class="text-lg font-bold mb-2 text-red-700">Data Tanpa Keterangan</h3>
                <table class="min-w-full border-2 border-red-400 rounded-lg overflow-hidden shadow border-collapse">
                    <thead>
                        <tr class="bg-red-500 text-white border-b-2 border-red-400">
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2 text-center">NIS</th>
                            <th class="px-4 py-2 text-center">Kelas</th>
                            <th class="px-4 py-2 text-center">Tanggal</th>
                            <th class="px-4 py-2 text-center">Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataTanpaKeterangan as $row)
                        <tr class="bg-white border-b border-red-200">
                            <td class="px-4 py-2">{{ $row->siswa->nama ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->siswa->nis ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->siswa->rombel && $row->siswa->rombel->kelas ? $row->siswa->rombel->kelas->nama : '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->tanggal ? \Carbon\Carbon::parse($row->tanggal)->toDateString() : '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->jam_masuk ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Belum Hadir (tidak bisa diklik) -->
            <div class="bg-gray-100 shadow rounded-lg p-6 text-center flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-3xl font-bold text-gray-700">{{ $jumlahBelumHadir ?? 0 }}</div>
                <div class="mt-2 text-gray-800">Belum Hadir</div>
            </div>
        </div>
    </div>

</div>
@endsection

