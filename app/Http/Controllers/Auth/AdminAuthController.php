<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_admin' => true], $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Hibás email cím vagy jelszó.'])->withInput();
        }

        $request->session()->regenerate();

        return redirect()->route('admin.events.index')->with('success', 'Sikeres admin bejelentkezés!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
