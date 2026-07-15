<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
        public function update(ProfileUpdateRequest $request): RedirectResponse
        {
            $user = $request->user();

            $user->fill($request->validated());

            if ($request->hasFile('profile_photo')) {

                $request->validate([
                    'profile_photo' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
                ]);

                if ($user->profile_photo) {
                    $oldPhoto = public_path('uploads/profile/' . $user->profile_photo);

                    if (file_exists($oldPhoto)) {
                        unlink($oldPhoto);
                    }
                }

                $photo = $request->file('profile_photo');

                $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();

                $photo->move(public_path('uploads/profile'), $photoName);

                $user->profile_photo = $photoName;
            }

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
