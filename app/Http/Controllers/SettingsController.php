<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function update(Request $request)
    {
        $settings = [
            'theme' => $request->input('theme', 'light'),
            'font_size' => $request->input('font_size', 'medium'),
            'font_weight' => $request->input('font_weight', 'normal')
        ];

        session(['user_settings' => $settings]);

        return response()->json(['success' => true]);
    }
}