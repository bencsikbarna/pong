<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TeamPasswordReset;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TeamPasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('auth.team-forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $team = Team::where('email', $request->email)->first();

        // Biztonsági okokból mindig ugyanazt üzenjük
        if (!$team) {
            return back()->with('success', 'Ha létezik fiók ezzel az email-lel, elküldtük a visszaállító linket.');
        }

        // Régi tokenek törlése
        DB::table('team_password_resets')->where('email', $request->email)->delete();

        $token = Str::random(64);

        DB::table('team_password_resets')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => now(),
        ]);

        $resetUrl = route('team.password.reset.form', ['token' => $token, 'email' => $request->email]);

        Mail::to($request->email)->send(new TeamPasswordReset($resetUrl, $team->name));

        return back()->with('success', 'Ha létezik fiók ezzel az email-lel, elküldtük a visszaállító linket.');
    }

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.team-reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.min'       => 'A jelszónak legalább 6 karakter hosszúnak kell lennie.',
            'password.confirmed' => 'A két jelszó nem egyezik.',
        ]);

        $record = DB::table('team_password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Érvénytelen vagy lejárt visszaállító link.']);
        }

        // 60 perc lejárat
        if (now()->diffInMinutes($record->created_at) > 60) {
            DB::table('team_password_resets')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'A visszaállító link lejárt. Kérj újat.']);
        }

        $team = Team::where('email', $request->email)->first();
        if (!$team) {
            return back()->withErrors(['email' => 'Nem található csapat ezzel az email-lel.']);
        }

        $team->update(['password' => Hash::make($request->password)]);
        DB::table('team_password_resets')->where('email', $request->email)->delete();

        return redirect()->route('team.login')->with('success', 'Jelszó sikeresen megváltoztatva! Most már bejelentkezhetsz.');
    }
}
