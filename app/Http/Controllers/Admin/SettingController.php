<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name'   => 'nullable|string|max:255',
            'phone'       => 'nullable|string|max:255',
            'email'       => 'nullable|email|max:255',
            'address'     => 'nullable|string',
            'footer_text' => 'nullable|string',
            'facebook'    => 'nullable|string|max:255',
            'instagram'   => 'nullable|string|max:255',
            'twitter'     => 'nullable|string|max:255',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $setting = Setting::firstOrCreate([]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time().'_'.$file->getClientOriginalName();

            $file->move(public_path('uploads/settings'), $filename);

            $data['logo'] = $filename;
        }

        $setting->update($data);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }
}