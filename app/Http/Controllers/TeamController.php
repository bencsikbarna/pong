<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeamController extends Controller
{
    public function dashboard()
    {
        $team = Auth::guard('team')->user();
        $team->load('registrations.groupTeam');
        $groupTeams = $team->registrations->map->groupTeam->filter();
        $team->dyn_wins          = $groupTeams->sum('wins');
        $team->dyn_losses        = $groupTeams->sum('losses');
        $team->dyn_draws         = $groupTeams->sum('draws');
        $team->dyn_cups_scored   = $groupTeams->sum('cups_scored');
        $team->dyn_cups_conceded = $groupTeams->sum('cups_conceded');
        $team->dyn_cup_diff      = $team->dyn_cups_scored - $team->dyn_cups_conceded;
        $registrations = $team->registrations()->with('event')->latest()->get();
        return view('team.dashboard', compact('team', 'registrations'));
    }

    public function stats()
    {
        $teams = Team::with(['registrations.groupTeam'])->get()->map(function ($team) {
            $groupTeams = $team->registrations->map->groupTeam->filter();
            $team->dyn_events       = $team->registrations->count();
            $team->dyn_wins         = $groupTeams->sum('wins');
            $team->dyn_losses       = $groupTeams->sum('losses');
            $team->dyn_draws        = $groupTeams->sum('draws');
            $team->dyn_cups_scored  = $groupTeams->sum('cups_scored');
            $team->dyn_cups_conceded = $groupTeams->sum('cups_conceded');
            $team->dyn_cup_diff     = $team->dyn_cups_scored - $team->dyn_cups_conceded;
            return $team;
        })->sortByDesc('dyn_wins')->sortByDesc('dyn_cups_scored')->values();

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

    public function showChangePassword()
    {
        $team = Auth::guard('team')->user();
        return view('team.change-password', compact('team'));
    }

    public function changePassword(Request $request)
    {
        $team = Auth::guard('team')->user();

        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ], [
            'password.min'       => 'Az új jelszónak legalább 6 karakter hosszúnak kell lennie.',
            'password.confirmed' => 'A két jelszó nem egyezik.',
        ]);

        if (!Hash::check($request->current_password, $team->password)) {
            return back()->withErrors(['current_password' => 'A jelenlegi jelszó helytelen.']);
        }

        $team->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Jelszó sikeresen megváltoztatva!');
    }
}
