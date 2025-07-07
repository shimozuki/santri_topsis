<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Objek;

class SantriRegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'jenjang' => 'required|in:SMP,SMA',
            'nisn' => 'required|string|max:20|unique:objek,nisn',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Buat user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Tambahkan role santri
        \DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => 6, // id role santri
        ]);

        // Tambahkan ke tabel objek
        Objek::create([
            'nama' => $request->name,
            'jenjang' => $request->jenjang,
            'nisn' => $request->nisn
        ]);

        return redirect()->route('login')->with('status', 'Registrasi berhasil. Silakan login.');
    }
}
