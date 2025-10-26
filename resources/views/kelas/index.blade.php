@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Daftar Kelas</h2>
        <a href="{{ route('kelas.create') }}" class="px-3 py-1 bg-green-500 text-white rounded">Tambah Kelas</a>
    </div>

    @if(session('success'))
        <div class="mb-3 p-2 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-3 p-2 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
    @endif

    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-center">No</th>
                <th class="p-2 text-left">Nama Kelas</th>
                <th class="p-2 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kelas as $i => $k)
            <tr class="{{ $i % 2 ? 'bg-white' : 'bg-gray-50' }}">
                <td class="p-2 text-center">{{ $loop->iteration }}</td>
                <td class="p-2">{{ $k->nama }}</td>
                <td class="p-2 text-center">
                    <a href="{{ route('kelas.edit', $k->id) }}" class="text-blue-600 mr-2">Edit</a>
                    <form action="{{ route('kelas.destroy', $k->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kelas ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
