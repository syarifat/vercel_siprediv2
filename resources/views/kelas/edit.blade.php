@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-8">
    <h2 class="text-lg font-semibold mb-4">Edit Kelas</h2>

    @if($errors->any())
        <div class="mb-3 p-2 bg-red-50 text-red-700 rounded">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('kelas.update', $kelas->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="block text-sm font-medium">Nama Kelas</label>
            <input type="text" name="nama" value="{{ old('nama', $kelas->nama) }}" required class="w-full border rounded p-2">
        </div>
        <div class="flex gap-2">
            <a href="{{ route('kelas.index') }}" class="px-3 py-1 border rounded">Batal</a>
            <button class="px-3 py-1 bg-cyan-600 text-white rounded">Update</button>
        </div>
    </form>
</div>
@endsection
