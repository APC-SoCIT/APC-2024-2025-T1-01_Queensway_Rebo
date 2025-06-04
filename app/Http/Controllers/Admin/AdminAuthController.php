<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Controller;



class AdminAuthController extends Controller
{
    // Show login form for admins
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // Handle admin login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $admin = Auth::guard('admin')->user();

            if (!$admin->hasVerifiedEmail()) {
                Auth::guard('admin')->logout();
                return back()->withInput($request->only('email'))
                    ->with('showResend', true)
                    ->withErrors(['email' => 'You must verify your email address.']);
            }

            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');

        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }


    // Admin registration form
    public function showRegistrationForm()
    {
        return view('admin.register');
    }

    // Handle admin registration
    public function register(Request $request)
    {

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($admin));

        return redirect()->route('admin.login')->with('message', 'Registration successful. Please check your email to verify your account before logging in.');
    }

    // Handle admin logout
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
