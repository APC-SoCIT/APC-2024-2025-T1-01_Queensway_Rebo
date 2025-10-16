<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class UnifiedAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // single login view
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Try admin first
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // Try normal user
        if (Auth::guard('web')->attempt($credentials, $request->filled('remember'))) {
            $user = Auth::guard('web')->user();

            if (!$user->hasVerifiedEmail()) {
                Auth::guard('web')->logout();
                return back()->withInput()
                    ->with('showResend', true)
                    ->withErrors(['email' => 'Please verify your email before logging in.']);
            }

            $request->session()->regenerate();

            // ======= MERGE CART =======
            $dbCart = Cart::firstOrCreate(
                ['user_id' => $user->id],
                ['items' => []]
            );

            $sessionCart = session('cart', []);

            foreach ($sessionCart as $productId => $item) {
                if (isset($dbCart->items[$productId])) {
                    $dbCart->items[$productId]['quantity'] += $item['quantity'];
                } else {
                    $dbCart->items[$productId] = $item;
                }
            }

            $dbCart->save();

            // Update session cart with DB cart after login
            session(['cart' => $dbCart->items]);

            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        // logout both guards
        Auth::guard('admin')->logout();
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
