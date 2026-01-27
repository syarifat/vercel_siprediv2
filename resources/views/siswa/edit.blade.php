@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('siswa.index') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600">Siswa</a>
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
        <div class="px-6 py-4 border-b border-gray-100 bg-orange-50 flex justify-between items-center">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Edit Siswa</h2>
                <p class="text-xs text-gray-500">{{ $siswa->nama }} ({{ $siswa->nis }})</p>
            </div>
        </div>

        <div class="p-6">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('siswa.update', $siswa) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIS <span class="text-red-500">*</span></label>
                        <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                        @error('nis') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama" value="{{ old('nama', $siswa->nama) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                        @error('nama') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <div class="flex items-center gap-6">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin)=='L' ? 'checked' : '' }} required class="text-orange-600 focus:ring-orange-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Laki-laki</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin)=='P' ? 'checked' : '' }} class="text-orange-600 focus:ring-orange-500 border-gray-300">
                            <span class="ml-2 text-sm text-gray-700">Perempuan</span>
                        </label>
                    </div>
                    @error('jenis_kelamin') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. HP Orang Tua</label>
                        <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu', $siswa->no_hp_ortu) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                        @error('no_hp_ortu') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" required class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                            <option value="aktif" {{ old('status', $siswa->status)=='aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="lulus" {{ old('status', $siswa->status)=='lulus' ? 'selected' : '' }}>Lulus</option>
                            <option value="keluar" {{ old('status', $siswa->status)=='keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                        @error('status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID Kartu RFID (Opsional)</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <input type="text" name="rfid" value="{{ old('rfid', $siswa->rfid) }}" 
                            class="block w-full rounded-lg border-gray-300 pl-10 focus:border-orange-500 focus:ring-orange-500 sm:text-sm" 
                            placeholder="Tempel kartu pada reader...">
                    </div>
                    @error('rfid') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('siswa.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-orange-600 text-white text-sm font-medium hover:bg-orange-700 shadow-md hover:shadow-lg transition duration-200">
                        Update Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection