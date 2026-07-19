<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            // Name
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            // Phone
            'phone' => [
                'required',
                'string',
                'max:30',
                Rule::unique(User::class, 'phone')
                    ->ignore($this->user()->id),
            ],

            // Email
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')
                    ->ignore($this->user()->id),
            ],

            // Profile Photo
            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            // Address
            'address_line_1' => [
                'required',
                'string',
                'max:255',
            ],

            'address_line_2' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'postal_code' => [
                'required',
                'string',
                'max:30',
            ],

            'country' => [
                'required',
                'string',
                'max:100',
            ],
        ];
    }
}