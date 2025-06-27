<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $judul = "Daftar Pengguna";
        $users = User::with('roles')->get();
        return view('dashboard.users.index', compact('users', 'judul'));
    }
}
