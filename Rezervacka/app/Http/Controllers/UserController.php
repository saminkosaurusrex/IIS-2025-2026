<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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

    public function create(){
        return Inertia::render('admin/users/Create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|array',
            'role.*' => 'string|in:admin,cashier,editor,user',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);
        foreach($validated['role'] as $role){
            if($role === 'user') continue;
            $user->assignRole($role);
        }
        return redirect('/users')->with('success', 'Používateľ bol vytvorený');

    }

    public function edit($id){
        $user = User::with('roles')->findOrFail($id);
        $user->role = $user->roles->pluck('name')->toArray() ?: ['user'];
        return Inertia::render('admin/users/Edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::with('roles')->findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','string','email','max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|array',
            'role.*' => 'string|in:admin,cashier,editor,user',
        ]);
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Sync roles, ignorujeme 'user'
        $roles = array_filter($validated['role'], fn($r) => $r !== 'user');
        $user->syncRoles($roles);

        return redirect('/users')->with('success', 'Používateľ bol upravený');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('/users/delete')->with('success', 'Používateľ bol zmazaný');
    }
}
