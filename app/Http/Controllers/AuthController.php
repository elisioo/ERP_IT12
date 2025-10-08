<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('menu');
        }
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:admin_users,username',
            'password' => 'required|min:6|confirmed'
        ]);

        AdminUser::create([
            'username' => $request->username,
            'password' => $request->password
        ]);

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $admin = AdminUser::where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {
            session([
                'admin_logged_in' => true, 
                'admin_id' => $admin->id, 
                'admin_username' => $admin->username,
                'admin_profile_picture' => $admin->profile_picture
            ]);
            return redirect()->route('menu');
        }

        return back()->withErrors(['credentials' => 'Invalid credentials']);
    }

    public function profile()
    {
        $admin = AdminUser::find(session('admin_id'));
        return view('auth.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:admin_users,username,' . session('admin_id'),
            'password' => 'nullable|min:6|confirmed',
            'profile_picture' => 'nullable|image|max:2048'
        ]);

        $admin = AdminUser::find(session('admin_id'));
        $admin->username = $request->username;
        
        if ($request->password) {
            $admin->password = $request->password;
        }
        
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $admin->profile_picture = $path;
        }
        
        $admin->save();
        
        session([
            'admin_username' => $admin->username,
            'admin_profile_picture' => $admin->profile_picture
        ]);
        
        return response()->json(['success' => true]);
    }

    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_id', 'admin_username', 'admin_profile_picture']);
        return redirect()->route('login');
    }
}