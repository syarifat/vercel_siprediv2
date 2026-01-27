@extends('layouts.app')

@section('content')
@php
    $isGuru = auth()->user()->role === 'guru';
@endphp

<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Siswa</h2>
            <p class="text-sm text-gray-500">Kelola data siswa, NIS, dan status akademik.</p>
        </div>
        
        @if(!$isGuru)
        <a href="{{ route('siswa.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Siswa
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <form action="{{ route('siswa.index') }}" method="GET" class="relative max-w-md flex gap-2">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm transition duration-150 ease-in-out"
                        placeholder="Cari Nama atau NIS..."
                    >
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 text-sm font-medium transition">
                    Cari
                </button>
                @if(request('search'))
                    <a href="{{ route('siswa.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium transition flex items-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-orange-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">Info Siswa</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">L/P</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">No HP Ortu</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">Status</th>
                        @if(!$isGuru)
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($siswa as $i => $row)
                    <tr class="hover:bg-orange-50/50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ $siswa->firstItem() + $i }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-medium text-gray-900">{{ $row->nama }}</span>
                                <span class="text-xs text-gray-500">NIS: {{ $row->nis }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $row->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ $row->jenis_kelamin }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ $row->no_hp_ortu ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($row->status == 'aktif')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                            @elseif($row->status == 'lulus')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Lulus</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Keluar</span>
                            @endif
                        </td>
                        @if(!$isGuru)
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('siswa.edit', $row->id) }}" class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 p-1.5 rounded-md transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('siswa.destroy', $row->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data siswa ini?');">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $isGuru ? 5 : 6 }}" class="px-6 py-10 text-center text-gray-500 bg-gray-50">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                <span class="font-medium">Data tidak ditemukan</span>
                                <p class="text-xs mt-1">Coba kata kunci lain atau tambahkan siswa baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($siswa->hasPages())
        <div class="px-4 py-3 border-t border-gray-200 bg-gray-50">
            {{ $siswa->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection