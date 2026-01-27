@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('absensi_guru.index') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600">Absensi Guru</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                    <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Detail</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-orange-50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-gray-800">Detail Kehadiran</h2>
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase 
                {{ $absensi->status == 'hadir' ? 'bg-green-100 text-green-700' : 
                  ($absensi->status == 'sakit' ? 'bg-red-100 text-red-700' : 
                  ($absensi->status == 'izin' ? 'bg-yellow-100 text-yellow-700' : 'bg-pink-100 text-pink-700')) }}">
                {{ $absensi->status }}
            </span>
        </div>

        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4 border-b border-gray-100 pb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase">Nama Guru</label>
                    <p class="text-base font-semibold text-gray-800 mt-1">{{ $absensi->guru->nama ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase">NIP</label>
                    <p class="text-base text-gray-800 mt-1">{{ $absensi->guru->nip ?? '-' }}</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 border-b border-gray-100 pb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase">Tanggal</label>
                    <p class="text-sm text-gray-800 mt-1">{{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('l, d F Y') }}</p>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 uppercase">Jam Kerja</label>
                    <p class="text-sm font-mono text-gray-800 mt-1">
                        {{ $absensi->jam_masuk ?? '--:--' }} s/d {{ $absensi->jam_pulang ?? '--:--' }}
                    </p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase">Keterangan / Catatan</label>
                <p class="text-sm text-gray-800 mt-1 italic">{{ $absensi->keterangan ?? 'Tidak ada keterangan' }}</p>
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
            <a href="{{ route('absensi_guru.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-600 hover:bg-white transition">Kembali</a>
            <a href="{{ route('absensi_guru.edit', $absensi) }}" class="px-4 py-2 bg-orange-600 rounded-lg text-sm font-medium text-white hover:bg-orange-700 transition shadow-sm">Edit Data</a>
        </div>
    </div>
</div>
@endsection