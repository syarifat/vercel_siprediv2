@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tahun Ajaran</h2>
            <p class="text-sm text-gray-500">Kelola periode akademik dan status aktif.</p>
        </div>
        
        <a href="{{ route('tahun_ajaran.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Tahun Ajaran
        </a>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r shadow-sm flex items-start">
        <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
        </svg>
        <div>
            <h4 class="text-sm font-bold text-blue-800">Catatan Penting:</h4>
            <p class="text-sm text-blue-700">Pastikan hanya ada <strong>satu</strong> Tahun Ajaran yang berstatus <strong>Aktif</strong>. Lakukan perubahan status hanya saat pergantian semester untuk menjaga konsistensi data presensi.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-orange-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider w-16">No</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-orange-800 uppercase tracking-wider">Nama Tahun Ajaran</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">Semester</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-orange-800 uppercase tracking-wider w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tahunAjaran as $i => $row)
                    <tr class="hover:bg-orange-50/50 transition duration-150 {{ $row->aktif ? 'bg-orange-50/30' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $row->nama }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                            {{ $row->semester }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($row->aktif)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-500">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('tahun_ajaran.edit', $row) }}" class="text-orange-600 hover:text-orange-900 bg-orange-50 hover:bg-orange-100 p-1.5 rounded-md transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                
                                @if(!$row->aktif)
                                <form action="{{ route('tahun_ajaran.destroy', $row->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tahun ajaran ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 rounded-md transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            Belum ada data Tahun Ajaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection