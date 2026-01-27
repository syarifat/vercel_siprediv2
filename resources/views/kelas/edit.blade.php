@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    <nav class="flex mb-4" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('kelas.index') }}" class="text-sm font-medium text-gray-500 hover:text-orange-600">Kelas</a>
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
            <h2 class="text-lg font-bold text-gray-800">Edit Kelas</h2>
            <p class="text-xs text-gray-500">Ubah nama kelas: {{ $kelas->nama }}</p>
        </div>

        <div class="p-6">
            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 text-sm text-red-700">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas <span class="text-red-500">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama', $kelas->nama) }}" required 
                        class="w-full rounded-lg border-gray-300 focus:border-orange-500 focus:ring-orange-500 shadow-sm transition">
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('kelas.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm font-medium hover:bg-gray-50 transition">
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