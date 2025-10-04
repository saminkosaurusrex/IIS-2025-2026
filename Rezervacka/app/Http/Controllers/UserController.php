<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index(){
        $users = User::with('roles')->get();
        $users->map(function ($user) {
            $user->role = $user->roles->pluck('name')->join(', ') ?: 'user';
            return $user;
        });

        return Inertia::render('admin/users/Index', compact('users'));
    }
}
