<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CafeProfile;
use Illuminate\Http\Request;

class CafeProfileController extends Controller
{
    public function edit()
    {
        $profile = CafeProfile::firstOrCreate(
            ['id' => 1],
            [
                'brand_name' => 'Cafe A',
                'legal_name' => 'Cafe A Indonesia',
                'slogan' => 'Scan, Order, Enjoy',
                'description' => 'Cafe A adalah cafe lokal dengan sistem pemesanan digital berbasis QR meja.',
                'primary_color' => '#6F4E37',
                'secondary_color' => '#F5E6D3',
                'accent_color' => '#2F4F4F',
            ]
        );

        return view('admin.cafe-profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = CafeProfile::firstOrCreate(['id' => 1]);

        $validated = $request->validate([
            'brand_name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'primary_color' => ['required', 'string', 'max:20'],
            'secondary_color' => ['required', 'string', 'max:20'],
            'accent_color' => ['required', 'string', 'max:20'],
            'whatsapp_number' => ['nullable', 'string', 'max:50'],
            'instagram_url' => ['nullable', 'string', 'max:255'],
            'tiktok_url' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'google_maps_url' => ['nullable', 'string'],
        ]);

        $profile->update($validated);

        return redirect()
            ->route('admin.cafe-profile.edit')
            ->with('success', 'Profil Cafe A berhasil diperbarui.');
    }
}