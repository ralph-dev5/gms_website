<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        return view('auth.register');
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'The username field is required.',
            'email.unique'   => 'That username has already been taken.',
            'email.max'      => 'The username may not be greater than 255 characters.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')->with('status', 'Account created! Please sign in.');
    }
}