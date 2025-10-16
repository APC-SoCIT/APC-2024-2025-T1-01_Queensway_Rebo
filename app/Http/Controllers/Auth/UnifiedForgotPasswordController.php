<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Models\Admin;

class UnifiedForgotPasswordController extends Controller
{
    /**
     * Show the "Forgot Password" form.
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email'); // single view
    }

    /**
     * Send reset link email to either user or admin.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        // Determine if email exists in users or admins table
        if (User::where('email', $email)->exists()) {
            $broker = 'users';
        } elseif (Admin::where('email', $email)->exists()) {
            $broker = 'admins';
        } else {
            return back()->withErrors(['email' => 'Email not found in our records.']);
        }

        $status = Password::broker($broker)->sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }
}
