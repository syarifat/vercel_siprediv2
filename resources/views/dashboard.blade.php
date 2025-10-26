@extends('layouts.app')

@section('content')
<div x-data="{ selected: null }" class="max-w-5xl mx-auto mt-10">
    <div class="bg-white shadow rounded-lg p-8 text-center mb-6">
        <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <!-- Total Siswa -->
        <div @click="selected = 'siswa'" class="cursor-pointer bg-blue-100 shadow rounded-lg p-6 text-center flex flex-col items-center hover:ring-2 hover:ring-blue-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <div class="text-3xl font-bold text-blue-700">{{ $jumlahSiswa ?? 0 }}</div>
            <div class="mt-2 text-blue-800">Total Siswa</div>
        </div>
        <!-- Hadir -->
        <div @click="selected = 'hadir'" class="cursor-pointer bg-green-100 shadow rounded-lg p-6 text-center flex flex-col items-center hover:ring-2 hover:ring-green-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <div class="text-3xl font-bold text-green-700">{{ $jumlahHadir ?? 0 }}</div>
            <div class="mt-2 text-green-800">Hadir</div>
        </div>
        <!-- Sakit/Izin -->
        <div @click="selected = 'sakitizin'" class="cursor-pointer bg-yellow-100 shadow rounded-lg p-6 text-center flex flex-col items-center hover:ring-2 hover:ring-yellow-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
            </svg>
            <div class="text-3xl font-bold text-yellow-700">{{ $jumlahSakitIzin ?? 0 }}</div>
            <div class="mt-2 text-yellow-800">Sakit/Izin</div>
        </div>
        <!-- Tanpa Keterangan -->
        <div @click="selected = 'tanpaket'" class="cursor-pointer bg-red-100 shadow rounded-lg p-6 text-center flex flex-col items-center hover:ring-2 hover:ring-red-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <div class="text-3xl font-bold text-red-700">{{ $jumlahTanpaKeterangan ?? 0 }}</div>
            <div class="mt-2 text-red-800">Tanpa Keterangan</div>
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

    <!-- Tabel Data Dinamis -->
    <div class="mt-8" x-show="selected !== null" x-transition>
        <template x-if="selected === 'siswa'">
            <div>
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
        </template>
        <template x-if="selected === 'hadir'">
            <div>
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
                            <td class="px-4 py-2 text-center">{{ $row->jam }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </template>
        <template x-if="selected === 'sakitizin'">
            <div>
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
                            <td class="px-4 py-2 text-center">{{ $row->jam }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </template>
        <template x-if="selected === 'tanpaket'">
            <div>
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
                            <td class="px-4 py-2 text-center">{{ $row->jam }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </template>
        <template x-if="selected === 'belumhadir'">
            <div>
                <h3 class="text-lg font-bold mb-2 text-gray-700">Data Belum Hadir</h3>
                <table class="min-w-full border-2 border-gray-400 rounded-lg overflow-hidden shadow border-collapse">
                    <thead>
                        <tr class="bg-gray-500 text-white border-b-2 border-gray-400">
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2 text-center">NIS</th>
                            <th class="px-4 py-2 text-center">Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataBelumHadir as $row)
                        <tr class="bg-white border-b border-gray-200">
                            <td class="px-4 py-2">{{ $row->nama ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->nis ?? '-' }}</td>
                            <td class="px-4 py-2 text-center">{{ $row->kelas->nama ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </template>
    </div>
</div>
@endsection

