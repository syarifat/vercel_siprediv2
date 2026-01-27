@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('absensi.index') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600">Absensi Siswa</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                    <span class="ml-1 text-sm font-medium text-gray-700 md:ml-2">Edit Data</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-orange-50">
            <h2 class="text-lg font-bold text-gray-800">Edit Status Absensi</h2>
            <p class="text-xs text-gray-500">Perbarui status kehadiran siswa.</p>
        </div>

        <div class="p-6">
            <form action="{{ route('absensi.update', $absensi->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4 p-3 bg-gray-50 rounded border border-gray-200">
                    <p class="text-xs text-gray-500 uppercase font-bold">Siswa</p>
                    <p class="text-sm font-medium text-gray-800">{{ $absensi->siswa->nama ?? '-' }} ({{ $absensi->siswa->nis ?? '-' }})</p>
                    <p class="text-xs text-gray-500 mt-1">Tanggal: {{ \Carbon\Carbon::parse($absensi->tanggal)->translatedFormat('d F Y') }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Kehadiran</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition" required>
                        <option value="hadir" {{ $absensi->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                        <option value="izin" {{ $absensi->status == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ $absensi->status == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        <option value="alpha" {{ $absensi->status == 'alpha' ? 'selected' : '' }}>Alpha</option>
                    </select>
                    @error('status')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <input type="text" name="keterangan" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition" 
                        value="{{ old('keterangan', $absensi->keterangan) }}" placeholder="Contoh: Izin acara keluarga">
                    @error('keterangan')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-500 mt-1 italic">
                        *Disarankan mencantumkan nama pengubah di keterangan (Opsional).
                    </p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('absensi.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-orange-600 text-white text-sm font-medium hover:bg-orange-700 shadow-md hover:shadow-lg transition duration-200">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection