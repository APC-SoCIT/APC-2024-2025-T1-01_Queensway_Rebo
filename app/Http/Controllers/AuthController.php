<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    // Show login form for users
    public function showLoginForm()
    {
        return view('user.login');
    }

    // Handle user login
// Handle user login
public function login(Request $request)
{
    // Validate the credentials
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Attempt to log the user in
    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        // Check if the user's email is verified
        if (!$user->hasVerifiedEmail()) {
            // Log out the user immediately if email is not verified
            Auth::logout();

            // Redirect back with an error message
            return back()->withErrors(['email' => 'Please verify your email address before logging in.']);
        }

        // Regenerate the session to prevent session fixation
        $request->session()->regenerate();

        // Redirect to the intended dashboard (default user dashboard here)
        return redirect()->intended('user/dashboard');
    }

    // If login fails, return back with error
    return back()->withErrors(['email' => 'Invalid credentials']);
}

    

    // User registration form
    public function showRegistrationForm()
    {
        return view('user.register');
    }

    // Handle user registration
    public function register(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        
        event(new Registered($user));
        
        return redirect()->route('verification.notice');
        
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    
}
