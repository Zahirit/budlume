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

        /*
        |--------------------------------------------------------------------------
        | 1. Store original email and phone
        |--------------------------------------------------------------------------
        | We need these values so verification is reset ONLY when
        | the customer actually changes their email or phone number.
        */
        $oldEmail = $user->email;
        $oldPhone = $user->phone;

        /*
        |--------------------------------------------------------------------------
        | 2. Update validated profile fields
        |--------------------------------------------------------------------------
        */
        $validated = $request->validated();

        unset($validated['profile_photo']);

        $user->fill($validated);

        /*
        |--------------------------------------------------------------------------
        | 3. Profile Photo Upload
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('profile_photo')) {

            /*
             * Delete old profile photo.
             */
            if ($user->profile_photo) {

                $oldPhoto = public_path(
                    'uploads/profile/' . $user->profile_photo
                );

                if (file_exists($oldPhoto)) {
                    unlink($oldPhoto);
                }
            }

            /*
             * Make sure upload directory exists.
             */
            $uploadPath = public_path('uploads/profile');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            /*
             * Upload new photo.
             */
            $photo = $request->file('profile_photo');

            $photoName =
                time() .
                '_' .
                uniqid() .
                '.' .
                $photo->getClientOriginalExtension();

            $photo->move(
                $uploadPath,
                $photoName
            );

            $user->profile_photo = $photoName;
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Reset Email Verification If Email Changed
        |--------------------------------------------------------------------------
        */
        if ($oldEmail !== $user->email) {
            $user->email_verified_at = null;
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Reset Phone Verification If Phone Changed
        |--------------------------------------------------------------------------
        */
        if ($oldPhone !== $user->phone) {
            $user->phone_verified_at = null;
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Save User
        |--------------------------------------------------------------------------
        */
        $user->save();

        return Redirect::route('profile.edit')
            ->with(
                'status',
                'profile-updated'
            );
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag(
            'userDeletion',
            [
                'password' => [
                    'required',
                    'current_password',
                ],
            ]
        );

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}