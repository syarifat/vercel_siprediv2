@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Edit Siswa</h2>

        @if(session('success'))
            <div class="mb-4 text-green-700 bg-green-100 p-2 rounded">{{ session('success') }}</div>
        @endif

        <form action="{{ route('siswa.update', $siswa) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Nama</label>
                <input type="text" name="nama" value="{{ old('nama', $siswa->nama) }}" required
                    class="w-full border rounded px-3 py-2">
                @error('nama') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">NIS</label>
                <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}" required
                    class="w-full border rounded px-3 py-2">
                @error('nis') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Jenis Kelamin</label>
                <div class="flex items-center gap-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin)=='L' ? 'checked' : '' }} required>
                        <span class="ms-2">Laki-laki</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin)=='P' ? 'checked' : '' }}>
                        <span class="ms-2">Perempuan</span>
                    </label>
                </div>
                @error('jenis_kelamin') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">No. HP Orang Tua</label>
                <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu', $siswa->no_hp_ortu) }}"
                    class="w-full border rounded px-3 py-2">
                @error('no_hp_ortu') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">RFID (opsional)</label>
                <input type="text" name="rfid" value="{{ old('rfid', $siswa->rfid) }}"
                    class="w-full border rounded px-3 py-2" placeholder="Kosongkan jika belum ada">
                @error('rfid') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" required class="w-full border rounded px-3 py-2">
                    <option value="aktif" {{ old('status', $siswa->status)=='aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="lulus" {{ old('status', $siswa->status)=='lulus' ? 'selected' : '' }}>Lulus</option>
                    <option value="keluar" {{ old('status', $siswa->status)=='keluar' ? 'selected' : '' }}>Keluar</option>
                </select>
                @error('status') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('siswa.index') }}" class="px-4 py-2 border rounded text-sm">Batal</a>
                <button type="submit" class="px-4 py-2 bg-cyan-600 text-white rounded text-sm">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection