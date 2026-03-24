<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeamAuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.team-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'email' => 'required|email|unique:teams,email',
            'password' => 'required|string|min:6|confirmed',
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
        ], [
            'name.unique' => 'Ez a csapatnév már foglalt.',
            'email.unique' => 'Ez az email cím már regisztrált.',
            'password.min' => 'A jelszónak legalább 6 karakter hosszúnak kell lennie.',
            'password.confirmed' => 'A két jelszó nem egyezik.',
        ]);

        $team = Team::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact_name' => $request->contact_name,
            'contact_phone' => $request->contact_phone,
        ]);

        Auth::guard('team')->login($team);

        return redirect()->route('events.index')->with('success', 'Sikeres regisztráció! Üdvözlünk, ' . $team->name . '!');
    }

    public function showLogin()
    {
        return view('auth.team-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $team = Team::where('email', $request->email)->first();

        if (!$team || !Hash::check($request->password, $team->password)) {
            return back()->withErrors(['email' => 'Hibás email cím vagy jelszó.'])->withInput();
        }

        Auth::guard('team')->login($team, $request->boolean('remember'));

        return redirect()->intended(route('events.index'))->with('success', 'Sikeres bejelentkezés!');
    }

    public function logout(Request $request)
    {
        Auth::guard('team')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('events.index');
    }
}
