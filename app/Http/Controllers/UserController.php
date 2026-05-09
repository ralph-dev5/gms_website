<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Show the user settings page.
     */
    public function settings()
    {
        $user = auth()->user();

        return view('user.settings', compact('user'));
    }

    /**
     * Update profile info.
     * - Google users: name and email can be updated
     * - Regular users: name and email are fixed, only profile photo can change
     */
    public function updateProfile(Request $request)
    {
        $user     = auth()->user();
        $isGoogle = !empty($user->google_id);

        $request->validate([
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        // Only upload profile photo — name and email are readonly for all users
        if ($request->hasFile('profile_photo')) {
            $cloudinary = new \Cloudinary\Cloudinary([
                'cloud' => [
                    'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                    'api_key'    => env('CLOUDINARY_API_KEY'),
                    'api_secret' => env('CLOUDINARY_API_SECRET'),
                ],
            ]);

            $result = $cloudinary->uploadApi()->upload(
                $request->file('profile_photo')->getRealPath(),
                ['folder' => 'profile-photos']
            );

            $user->profile_photo_path = $result['secure_url'];
        }

        $user->save();

        return back()->with('success', 'Profile photo updated successfully.');
    }

    /**
     * Update password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }
}