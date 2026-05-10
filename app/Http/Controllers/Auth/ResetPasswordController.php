<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(string $token)
    {
        // Verify token exists and is not expired
        $passwordReset = DB::table('password_resets')
            ->where('token', $token)
            ->where('created_at', '>', now()->subHours(1))
            ->first();

        if (! $passwordReset) {
            return redirect()->route('login')->with('error', 'This password reset link has expired.');
        }

        $user = User::where('email', $passwordReset->email)->first();
        if ($user && ! empty($user->google_id)) {
            return redirect()->route('login')->with('error', 'Password reset is not available for Google sign-in users.');
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $passwordReset->email]);
    }

    /**
     * Handle the incoming password reset request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'token' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Verify token
        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->where('created_at', '>', now()->subHours(1))
            ->first();

        if (! $passwordReset) {
            return back()->with('error', 'This password reset link has expired or is invalid.');
        }

        // Find user and ensure the account is not a Google-only login.
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->with('error', 'User not found.');
        }

        if (! empty($user->google_id)) {
            return back()->with('error', 'Password reset is not available for Google sign-in users.');
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Delete the password reset token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Password reset successful. Please login with your new password.');
    }
}
