<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Display the form to request a password reset link.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle a request to reset the user password.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->with('status', __('If an account with that email exists, we have sent a password reset link.'));
        }

        // Delete existing password reset tokens
        DB::table('password_resets')->where('email', $user->email)->delete();

        // Create new password reset token
        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Send reset email
        try {
            Mail::send('auth.emails.password-reset', ['user' => $user, 'token' => $token], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Reset Your Password');
            });
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send reset email: '.$e->getMessage());
        }

        return back()->with('status', __('If an account with that email exists, we have sent a password reset link.'));
    }
}
