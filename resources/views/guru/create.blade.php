@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Tambah Guru</h2>
    <form method="POST" action="{{ route('guru.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block">Nama</label>
            <input type="text" name="nama" class="border rounded px-2 py-1 w-full" required>
        </div>
        <div>
            <label class="block">NIP</label>
            <input type="text" name="nip" class="border rounded px-2 py-1 w-full">
        </div>
        <div>
            <label class="block">RFID (opsional)</label>
            <input type="text" name="rfid" class="border rounded px-2 py-1 w-full">
        </div>
        <div>
            <label class="block">No HP</label>
            <input type="text" name="no_hp" class="border rounded px-2 py-1 w-full">
        </div>
        <div>
            <label class="block">Status</label>
            <select name="status" class="border rounded px-2 py-1 w-full" required>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
@endsection
