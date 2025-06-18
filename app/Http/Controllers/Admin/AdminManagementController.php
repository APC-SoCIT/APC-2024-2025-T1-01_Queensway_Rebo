<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\AdminCredentialsMail;

class AdminManagementController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Only super admins can access this.');
        }

        $admins = Admin::orderBy('created_at', 'desc')->get();
        return view('admin.admin_management.index', compact('admins'));
    }

    public function create()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Only super admins can access this.');
        }

        return view('admin.admin_management.create-admin');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Only super admins can perform this action.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:admins,email',
        ]);

        $randomPassword = Str::random(10);

        $admin = Admin::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($randomPassword),
            'role'       => 'admin',
        ]);

        Mail::to($admin->email)->send(new AdminCredentialsMail($admin, $randomPassword));

        return redirect()->back()->with('success', 'Admin created and credentials sent!');
    }

    public function destroy(Admin $admin)
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403, 'Only super admins can perform this action.');
        }

        if (auth()->id() === $admin->id) {
            return back()->withErrors(['You cannot delete your own account.']);
        }

        $admin->delete();

        return back()->with('success', 'Admin deleted successfully.');
    }
}
