@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('tahun_ajaran.index') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600">Tahun Ajaran</a>
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
            <h2 class="text-lg font-bold text-gray-800">Edit Tahun Ajaran</h2>
            <p class="text-xs text-gray-500">{{ $tahunAjaran->nama }} - {{ $tahunAjaran->semester }}</p>
        </div>

        <div class="p-6">
            <form action="{{ route('tahun_ajaran.update', $tahunAjaran->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tahun Ajaran <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition" required value="{{ old('nama', $tahunAjaran->nama) }}">
                    @error('nama')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Semester <span class="text-red-500">*</span></label>
                    <select name="semester" class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition" required>
                        <option value="Ganjil" {{ old('semester', $tahunAjaran->semester)=='Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ old('semester', $tahunAjaran->semester)=='Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                    @error('semester')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-8">
                    <div class="flex items-start">
                        <div class="flex h-5 items-center">
                            <input id="aktif" name="aktif" type="checkbox" value="1" {{ old('aktif', $tahunAjaran->aktif) ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                        </div>
                        <div class="ml-3 text-sm">
                            <label for="aktif" class="font-medium text-gray-700">Jadikan Aktif</label>
                            <p class="text-gray-500">Centang untuk menjadikan ini tahun ajaran yang sedang berjalan.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('tahun_ajaran.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-orange-600 text-white text-sm font-medium hover:bg-orange-700 shadow-md hover:shadow-lg transition duration-200">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection