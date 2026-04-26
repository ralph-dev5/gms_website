<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{
    // Show OTP login page
    public function showOtpLogin()
    {
        return view('auth.otp-login');
    }

    // Send OTP to phone number
    public function sendOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $phone = $request->phone;

        $user = User::where('phone', $phone)->first();

        if (!$user) {
            return back()->withErrors(['phone' => 'No account found with this phone number.']);
        }

        $otp = rand(100000, 999999);

        session([
            'otp' => $otp,
            'otp_phone' => $phone,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        $response = Http::post('https://api.semaphore.co/api/v4/messages', [
            'apikey' => env('SEMAPHORE_API_KEY'),
            'number' => $phone,
            'message' => "Your GMS login OTP is: {$otp}. Valid for 5 minutes. Do not share this code.",
            'sendername' => env('SEMAPHORE_SENDER_NAME', 'SEMAPHORE'),
        ]);

        if ($response->successful()) {
            return redirect()->route('otp.verify.form')->with('phone', $phone);
        }

        return back()->withErrors(['phone' => 'Failed to send OTP. Please try again.']);
    }

    // Show OTP verification page
    public function showVerifyForm()
    {
        return view('auth.otp-verify');
    }

    // Verify OTP and login
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $sessionOtp = session('otp');
        $sessionPhone = session('otp_phone');
        $expiresAt = session('otp_expires_at');

        if (now()->isAfter($expiresAt)) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        if ($request->otp != $sessionOtp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        $user = User::where('phone', $sessionPhone)->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'User not found.']);
        }

        session()->forget(['otp', 'otp_phone', 'otp_expires_at']);

        Auth::login($user, true);
        $request->session()->regenerate();

        if ($user->is_admin == 1) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('dashboard');
    }

    // Show phone register form
    public function showPhoneRegister()
    {
        return view('auth.register-phone');
    }

    // Send OTP for registration
    public function sendRegisterOtp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone',
        ]);

        if (User::where('phone', $request->phone)->exists()) {
            return back()->withErrors(['phone' => 'This phone number is already registered.']);
        }

        $otp = rand(100000, 999999);

        session([
            'register_otp' => $otp,
            'register_name' => $request->name,
            'register_phone' => $request->phone,
            'register_otp_expires_at' => now()->addMinutes(5),
        ]);

        // TEMPORARY debug - remove in production!
        return redirect()->route('otp.register.verify.form')
            ->with('debug_otp', $otp);
    }

    // Show OTP verification for registration
    public function showRegisterVerifyForm()
    {
        return view('auth.register-phone-verify');
    }

    // Verify OTP and create account
    public function verifyRegisterOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $sessionOtp = session('register_otp');
        $expiresAt = session('register_otp_expires_at');

        if (now()->isAfter($expiresAt)) {
            return back()->withErrors(['otp' => 'OTP has expired. Please try again.']);
        }

        if ($request->otp != $sessionOtp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        $user = User::create([
            'name' => session('register_name'),
            'phone' => session('register_phone'),
            'email' => null,
            'password' => bcrypt(\Illuminate\Support\Str::random(24)),
        ]);

        session()->forget(['register_otp', 'register_name', 'register_phone', 'register_otp_expires_at']);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}