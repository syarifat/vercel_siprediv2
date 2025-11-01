@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Data Tahun Ajaran</h2>
        <a href="{{ route('tahun_ajaran.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">Tambah Tahun Ajaran</a>
    </div>

    <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4 flex items-center">
        <svg class="w-6 h-6 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
        </svg>
        <span>
            <strong>Catatan:</strong> Lakukan perubahan tahun ajaran <u>hanya saat pergantian semester</u> untuk menjaga konsistensi data.
        </span>
    </div>

    <div class="overflow-x-auto rounded-lg">
        <table class="w-[500px] mx-auto border-2 border-orange-400 rounded-lg overflow-hidden border-collapse bg-white shadow">
            <thead>
                <tr class="bg-orange-500 text-white border-b-2 border-orange-400 rounded-none">
                    <th class="px-2 py-2 text-center font-semibold">Tahun Ajaran</th>
                    <th class="px-2 py-2 text-center font-semibold">Semester</th>
                    <th class="px-2 py-2 text-center font-semibold">Status</th>
                    <th class="px-2 py-2 text-center font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tahunAjaran as $i => $row)
                <tr class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-100' }} border-b border-orange-200 hover:bg-orange-50">
                    <td class="px-2 py-2 text-center">{{ $row->nama }} - {{ $row->semester }}</td>
                    <td class="px-2 py-2 text-center">{{ $row->semester }}</td>
                    <td class="px-2 py-2 text-center">
                        @if($row->aktif)
                            <span class="text-white bg-green-600 px-2 py-1 rounded text-sm">Aktif</span>
                        @else
                            <span class="text-gray-700 bg-gray-200 px-2 py-1 rounded text-sm">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-2 py-2 text-center">
                        <a href="{{ route('tahun_ajaran.edit', $row) }}" class="text-blue-600">Edit</a>
                        @if(!$row->aktif)
                        <form action="{{ route('tahun_ajaran.destroy', $row->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tahun ajaran ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 ml-2">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
