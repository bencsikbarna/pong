<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamController extends Controller
{
    public function dashboard()
    {
        $team = Auth::guard('team')->user();
        $registrations = $team->registrations()->with('event')->latest()->get();
        return view('team.dashboard', compact('team', 'registrations'));
    }

    public function stats()
    {
        $teams = Team::orderByDesc('total_wins')
            ->orderByDesc('total_cups_scored')
            ->get();
        return view('team.stats', compact('teams'));
    }

    public function profile()
    {
        $team = Auth::guard('team')->user();
        return view('team.profile', compact('team'));
    }

    public function updateProfile(Request $request)
    {
        $team = Auth::guard('team')->user();

        $request->validate([
            'contact_name' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:50',
        ]);

        $team->update([
            'contact_name' => $request->contact_name,
            'contact_phone' => $request->contact_phone,
        ]);

        return back()->with('success', 'Profil frissítve.');
    }
}
