@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Edit Tahun Ajaran</h2>
    <form action="{{ route('tahun_ajaran.update', $tahunAjaran->id) }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama Tahun Ajaran</label>
            <input type="text" name="nama" class="w-full border rounded px-3 py-2" required value="{{ old('nama', $tahunAjaran->nama) }}">
            @error('nama')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Semester</label>
            <select name="semester" class="w-full border rounded px-3 py-2" required>
                <option value="Ganjil" {{ old('semester', $tahunAjaran->semester)=='Ganjil' ? 'selected' : '' }}>Ganjil</option>
                <option value="Genap" {{ old('semester', $tahunAjaran->semester)=='Genap' ? 'selected' : '' }}>Genap</option>
            </select>
            @error('semester')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="aktif" value="1" {{ old('aktif', $tahunAjaran->aktif) ? 'checked' : '' }} class="mr-2">
                <span class="font-medium">Jadikan aktif</span>
            </label>
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded font-bold shadow">Update</button>
        </div>
    </form>
</div>
@endsection
