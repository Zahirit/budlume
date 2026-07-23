<?php

namespace App\Http\Controllers\Delivery\Auth;

use App\Http\Controllers\Controller;
use App\Models\DeliveryProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class DeliveryRegisterController extends Controller
{
    /**
     * Show the delivery man registration form.
     */
    public function create()
    {
        return view('delivery.auth.register');
    }

    /**
     * Register a new delivery man.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
                'unique:users,phone',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],

            /*
            |--------------------------------------------------------------------------
            | Delivery Documents
            |--------------------------------------------------------------------------
            */

            'driving_license_number' => [
                'required',
                'string',
                'max:100',
                'unique:delivery_profiles,driving_license_number',
            ],

            'driving_license_photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],

            'sin_number' => [
                'required',
                'string',
                'max:50',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Store Driving License Photo
        |--------------------------------------------------------------------------
        */

        $licensePhotoPath = $request
            ->file('driving_license_photo')
            ->store(
                'delivery/licenses',
                'public'
            );

        try {

            $user = DB::transaction(
                function () use (
                    $validated,
                    $licensePhotoPath
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Create Delivery User
                    |--------------------------------------------------------------------------
                    */

                    $user = User::create([
                        'name' =>
                            $validated['name'],

                        'email' =>
                            $validated['email'],

                        'phone' =>
                            $validated['phone'],

                        'password' =>
                            Hash::make(
                                $validated['password']
                            ),

                        'role' =>
                            'delivery',
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Create Pending Delivery Profile
                    |--------------------------------------------------------------------------
                    */

                    DeliveryProfile::create([
                        'user_id' =>
                            $user->id,

                        'approval_status' =>
                            'pending',

                        'is_available' =>
                            false,

                        'driving_license_number' =>
                            $validated[
                                'driving_license_number'
                            ],

                        'driving_license_photo' =>
                            $licensePhotoPath,

                        /*
                        | DeliveryProfile model automatically
                        | encrypts this value before DB storage.
                        */
                        'sin_number' =>
                            $validated['sin_number'],
                    ]);

                    return $user;
                }
            );

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Remove uploaded photo if registration fails
            |--------------------------------------------------------------------------
            */

            if ($licensePhotoPath) {
                Storage::disk('public')
                    ->delete($licensePhotoPath);
            }

            throw $e;
        }

        /*
        |--------------------------------------------------------------------------
        | Store Delivery Registration User ID
        |--------------------------------------------------------------------------
        |
        | Keep the delivery user's ID in session so the next step
        | can verify the registered mobile number using OTP.
        |
        */

        session()->put(
            'delivery_registration_user_id',
            $user->id
        );

        return redirect()
            ->route(
                'delivery.phone.otp.show'
            );
    }
}