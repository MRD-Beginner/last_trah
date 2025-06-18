<?php

namespace App\Http\Controllers;

use App\Models\trah;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function route(){
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }if ($user->role === 'user') {
            return redirect()->route('user.keluarga');
        }
    }

    //dashboard
    public function index(){
        return redirect()->route('admin.keluarga');
    }

    public function keluarga(){
        $user = auth()->user();
        $trah = trah::all();
        return view('admin.data.keluarga', compact('user', 'trah'));
    }
}
