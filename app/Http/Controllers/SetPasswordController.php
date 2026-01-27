<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SetPasswordController extends Controller
{
    public function create(Request $request)
    {
        // Ambil email dari query param jika ada
        $email = $request->query('email');
        return view('auth.set-password', compact('email'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email', // Pastikan kolom email ada di tabel user
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Cek user berdasarkan email (Jika tabel user ada kolom email)
        // Jika pakai username, sesuaikan logikanya
        $user = User::where('email', $request->email)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('login')->with('success', 'Password berhasil diatur. Silakan login.');
        }

        return back()->with('error', 'User tidak ditemukan.');
    }
}