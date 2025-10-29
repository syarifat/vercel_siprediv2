@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Tambah User</h2>
    <form action="{{ route('user.store') }}" method="POST" class="bg-white shadow rounded-lg p-6">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Username</label>
            <input type="text" name="username" class="w-full border rounded px-3 py-2" required value="{{ old('username') }}">
            @error('username')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            @error('password')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Role</label>
            <select name="role" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
            </select>
            @error('role')<div class="text-red-500 text-sm">{{ $message }}</div>@enderror
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-bold shadow">Simpan</button>
        </div>
    </form>
</div>
@endsection
