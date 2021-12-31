<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;

class AuthenticatedUserController extends Controller
{

    public function edit()
    {
        return view('auth.edit');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'website' => ['nullable', 'url', 'max:2047'],
            'bio' => ['nullable', 'string', 'max:65535'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'gender' => [Rule::in(['prefer not to say', 'male', 'female'])]
        ]);

        $user->update($request->all());

        return back();
    }

    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'profile_image' => ['required', 'image'],
        ]);

        Auth::user()->addProfileImage($request->file('profile_image'));

        return back();
    }

    public function destroyProfileImage()
    {
        Auth::user()->removeProfileImage();

        return back();
    }
}
