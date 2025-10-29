@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-8">
    <h2 class="text-xl font-bold mb-4">Daftar User</h2>
    <a href="{{ route('user.create') }}" class="bg-green-400 hover:bg-green-500 text-white px-4 py-2 rounded-lg shadow mb-4 inline-block font-semibold transition duration-200">Tambah User</a>
    <table class="min-w-full border-2 border-orange-400 rounded-lg overflow-hidden shadow border-collapse">
        <thead>
            <tr class="bg-orange-500 text-white border-b-2 border-orange-400 rounded-none">
                <th class="px-4 py-2 text-left font-semibold">Username</th>
                <th class="px-4 py-2 text-center font-semibold">Role</th>
                <th class="px-4 py-2 text-center font-semibold">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="{{ $loop->index % 2 == 0 ? 'bg-white' : 'bg-gray-100' }} border-b border-orange-200 hover:bg-orange-50">
                <td class="px-4 py-2 text-left">{{ $user->username }}</td>
                <td class="px-4 py-2 text-center">{{ ucfirst($user->role) }}</td>
                <td class="px-4 py-2 text-center">
                    <a href="{{ route('user.edit', $user) }}" class="text-blue-600">Edit</a>
                    <form action="{{ route('user.destroy', $user) }}" method="POST" class="inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-pink-600 ml-2" onclick="return confirm('Hapus user ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
