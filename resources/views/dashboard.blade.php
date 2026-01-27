@extends('layouts.app')

@section('content')
<div x-data="{ selected: null, activeView: 'siswa' }" class="max-w-7xl mx-auto space-y-8">
    
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-orange-100 relative overflow-hidden text-center">
        <div class="absolute top-0 right-0 w-32 h-32 bg-orange-100/50 rounded-full blur-3xl -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-amber-100/50 rounded-full blur-3xl -ml-16 -mb-16"></div>
        
        <div class="relative z-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang, <span class="text-orange-600">{{ ucfirst(Auth::user()->role ?? '') }}</span> 👋</h2>
            <p class="text-gray-500 mb-6">
                Tahun Ajaran: 
                <span class="font-semibold text-gray-700 bg-orange-50 px-3 py-1 rounded-full border border-orange-100">
                    {{ $tahunAjaran ? $tahunAjaran->nama . ' - ' . $tahunAjaran->semester : 'Belum dipilih' }}
                </span>
            </p>

            <div class="inline-flex bg-gray-100 p-1 rounded-xl shadow-inner relative">
                <button @click="activeView = 'siswa'; selected = null" 
                        class="px-6 py-2 rounded-lg text-sm font-bold transition-all duration-300 ease-out z-10 relative"
                        :class="activeView === 'siswa' ? 'bg-white text-orange-600 shadow-md transform scale-105' : 'text-gray-500 hover:text-gray-700'">
                    Data Siswa
                </button>
                <button @click="activeView = 'guru'; selected = null"
                        class="px-6 py-2 rounded-lg text-sm font-bold transition-all duration-300 ease-out z-10 relative"
                        :class="activeView === 'guru' ? 'bg-white text-orange-600 shadow-md transform scale-105' : 'text-gray-500 hover:text-gray-700'">
                    Data Guru
                </button>
            </div>
        </div>
    </div>

    <div x-show="activeView === 'guru'" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4" 
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-6">
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $cards = [
                    ['id' => 'guru_total', 'label' => 'Total Guru', 'val' => $totalGuru ?? 0, 'color' => 'blue', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['id' => 'guru_hadir', 'label' => 'Hadir', 'val' => $guruHadir ?? 0, 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['id' => 'guru_sakit', 'label' => 'Sakit', 'val' => $guruSakit ?? 0, 'color' => 'red', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['id' => 'guru_izin', 'label' => 'Izin', 'val' => $guruIzin ?? 0, 'color' => 'yellow', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['id' => 'guru_alpha', 'label' => 'Tanpa Ket.', 'val' => $guruTanpaKet ?? 0, 'color' => 'pink', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['id' => 'guru_belum', 'label' => 'Belum Hadir', 'val' => $guruBelumHadir ?? 0, 'color' => 'gray', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'clickable' => false],
                ];
            @endphp

            @foreach($cards as $card)
                <div @if(($card['clickable'] ?? true)) @click="selected = selected === '{{ $card['id'] }}' ? null : '{{ $card['id'] }}'" @endif
                     class="relative bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col items-center justify-center text-center transition-all duration-200 
                            {{ ($card['clickable'] ?? true) ? 'cursor-pointer hover:shadow-md hover:border-orange-200 group' : '' }}
                            "
                     :class="selected === '{{ $card['id'] }}' ? 'ring-2 ring-orange-400 border-transparent' : ''">
                    
                    <div class="mb-3 p-3 rounded-full bg-{{ $card['color'] }}-50 text-{{ $card['color'] }}-600 group-hover:bg-orange-50 group-hover:text-orange-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-800">{{ $card['val'] }}</div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $card['label'] }}</div>
                    
                    @if(($card['clickable'] ?? true))
                        <div x-show="selected === '{{ $card['id'] }}'" class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-4 h-4 bg-orange-400 rotate-45"></div>
                    @endif
                </div>
            @endforeach
        </div>

        <div x-show="selected === 'guru_total'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-blue-50 px-6 py-3 border-b border-blue-100 font-bold text-blue-700">Detail Semua Guru</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">NIP</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">No HP</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dataAllGuru as $g)
                        <tr><td class="px-6 py-3 text-sm text-gray-900">{{ $g->nama }}</td><td class="px-6 py-3 text-center text-sm text-gray-500">{{ $g->nip ?? '-' }}</td><td class="px-6 py-3 text-center text-sm text-gray-500">{{ $g->no_hp ?? '-' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="selected === 'guru_hadir'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-green-50 px-6 py-3 border-b border-green-100 font-bold text-green-700">Detail Guru Hadir</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Jam Masuk</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dataGuruHadir as $g)
                        <tr><td class="px-6 py-3 text-sm text-gray-900">{{ $g->guru->nama }}</td><td class="px-6 py-3 text-center text-sm font-mono text-gray-600">{{ $g->jam_masuk }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="selected === 'guru_sakit'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-red-50 px-6 py-3 border-b border-red-100 font-bold text-red-700">Detail Guru Sakit</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Keterangan</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dataGuruSakit as $g)
                        <tr><td class="px-6 py-3 text-sm text-gray-900">{{ $g->guru->nama }}</td><td class="px-6 py-3 text-center text-sm text-gray-500">{{ $g->keterangan ?? '-' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="selected === 'guru_izin'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-yellow-50 px-6 py-3 border-b border-yellow-100 font-bold text-yellow-700">Detail Guru Izin</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Keterangan</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dataGuruIzin as $g)
                        <tr><td class="px-6 py-3 text-sm text-gray-900">{{ $g->guru->nama }}</td><td class="px-6 py-3 text-center text-sm text-gray-500">{{ $g->keterangan ?? '-' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="selected === 'guru_alpha'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-pink-50 px-6 py-3 border-b border-pink-100 font-bold text-pink-700">Detail Guru Tanpa Keterangan</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dataGuruTanpaKet as $g)
                        <tr><td class="px-6 py-3 text-sm text-gray-900">{{ $g->guru->nama }}</td><td class="px-6 py-3 text-center text-sm text-red-500 font-bold">Alpha</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div x-show="activeView === 'siswa'" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 translate-y-4" 
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-6">
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @php
                $cardsSiswa = [
                    ['id' => 'siswa', 'label' => 'Total Siswa', 'val' => $jumlahSiswa ?? 0, 'color' => 'blue', 'icon' => 'M17 20h5v-2a4 4 0 00-3-3.87M9 20h6M3 20h5v-2a4 4 0 013-3.87M16 7a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['id' => 'hadir', 'label' => 'Hadir', 'val' => $jumlahHadir ?? 0, 'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['id' => 'sakit', 'label' => 'Sakit', 'val' => $jumlahSakit ?? 0, 'color' => 'red', 'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['id' => 'izin', 'label' => 'Izin', 'val' => $jumlahIzin ?? 0, 'color' => 'yellow', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['id' => 'tanpaket', 'label' => 'Tanpa Ket.', 'val' => $jumlahTanpaKeterangan ?? 0, 'color' => 'pink', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['id' => 'belum', 'label' => 'Belum Hadir', 'val' => $jumlahBelumHadir ?? 0, 'color' => 'gray', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'clickable' => false],
                ];
            @endphp

            @foreach($cardsSiswa as $card)
                <div @if(($card['clickable'] ?? true)) @click="selected = selected === '{{ $card['id'] }}' ? null : '{{ $card['id'] }}'" @endif
                     class="relative bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col items-center justify-center text-center transition-all duration-200 
                            {{ ($card['clickable'] ?? true) ? 'cursor-pointer hover:shadow-md hover:border-orange-200 group' : '' }}
                            "
                     :class="selected === '{{ $card['id'] }}' ? 'ring-2 ring-orange-400 border-transparent' : ''">
                    
                    <div class="mb-3 p-3 rounded-full bg-{{ $card['color'] }}-50 text-{{ $card['color'] }}-600 group-hover:bg-orange-50 group-hover:text-orange-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
                    </div>
                    <div class="text-2xl font-bold text-gray-800">{{ $card['val'] }}</div>
                    <div class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $card['label'] }}</div>
                    
                    @if(($card['clickable'] ?? true))
                        <div x-show="selected === '{{ $card['id'] }}'" class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-4 h-4 bg-orange-400 rotate-45"></div>
                    @endif
                </div>
            @endforeach
        </div>

        <div x-show="selected === 'siswa'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-blue-50 px-6 py-3 border-b border-blue-100 font-bold text-blue-700">Detail Semua Siswa</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">NIS</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Kelas</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dataSiswaAktif as $s)
                        <tr><td class="px-6 py-3 text-sm text-gray-900">{{ $s->siswa->nama }}</td><td class="px-6 py-3 text-center text-sm text-gray-500">{{ $s->siswa->nis ?? '-' }}</td><td class="px-6 py-3 text-center text-sm text-gray-500">{{ $s->kelas->nama ?? '-' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="selected === 'hadir'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-green-50 px-6 py-3 border-b border-green-100 font-bold text-green-700">Detail Siswa Hadir</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Kelas</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Jam Masuk</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dataHadir as $s)
                        <tr><td class="px-6 py-3 text-sm text-gray-900">{{ $s->rombel->siswa->nama }}</td><td class="px-6 py-3 text-center text-sm text-gray-500">{{ $s->rombel->kelas->nama }}</td><td class="px-6 py-3 text-center text-sm font-mono text-gray-600">{{ $s->jam_masuk }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="selected === 'sakit'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-red-50 px-6 py-3 border-b border-red-100 font-bold text-red-700">Detail Siswa Sakit</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Kelas</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Keterangan</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dataSakit as $s)
                        <tr><td class="px-6 py-3 text-sm text-gray-900">{{ $s->rombel->siswa->nama }}</td><td class="px-6 py-3 text-center text-sm text-gray-500">{{ $s->rombel->kelas->nama }}</td><td class="px-6 py-3 text-center text-sm text-gray-500">{{ $s->keterangan ?? '-' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="selected === 'izin'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-yellow-50 px-6 py-3 border-b border-yellow-100 font-bold text-yellow-700">Detail Siswa Izin</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Kelas</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Keterangan</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dataIzin as $s)
                        <tr><td class="px-6 py-3 text-sm text-gray-900">{{ $s->rombel->siswa->nama }}</td><td class="px-6 py-3 text-center text-sm text-gray-500">{{ $s->rombel->kelas->nama }}</td><td class="px-6 py-3 text-center text-sm text-gray-500">{{ $s->keterangan ?? '-' }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div x-show="selected === 'tanpaket'" x-transition class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-pink-50 px-6 py-3 border-b border-pink-100 font-bold text-pink-700">Detail Siswa Tanpa Keterangan</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nama</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Kelas</th><th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase">Status</th></tr></thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($dataTanpaKeterangan as $s)
                        <tr><td class="px-6 py-3 text-sm text-gray-900">{{ $s->rombel->siswa->nama }}</td><td class="px-6 py-3 text-center text-sm text-gray-500">{{ $s->rombel->kelas->nama }}</td><td class="px-6 py-3 text-center text-sm font-bold text-red-500">Alpha</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection