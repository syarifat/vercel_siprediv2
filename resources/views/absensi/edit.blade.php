@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Edit Absensi</h2>
    <form action="{{ route('absensi.update', $absensi->id) }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Status</label>
            <select name="status" class="w-full border rounded px-3 py-2" required>
                <option value="hadir" {{ $absensi->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                <option value="izin" {{ $absensi->status == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ $absensi->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                <option value="alpha" {{ $absensi->status == 'alpha' ? 'selected' : '' }}>Alpha</option>
            </select>
            @error('status')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Keterangan</label>
            <input type="text" name="keterangan" class="w-full border rounded px-3 py-2" value="{{ old('keterangan', $absensi->keterangan) }}">
            @error('keterangan')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
            <p class="text-sm text-gray-600 mt-1">
                <span class="italic">*Isi keterangan dengan nama anda (yang mengubah).</span>
            </p>
        </div>
        <div class="flex justify-end mt-4">
            <a href="{{ route('absensi.index') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded mr-2">Kembali</a>
            <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded font-bold shadow">Update</button>
        </div>
    </form>
</div>
@endsection
