<?php

namespace App\Http\Controllers;

use App\Mail\EventRegistrationConfirmation;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::withCount(['registrations as confirmed_registrations_count' => function ($q) {
            $q->where('status', 'confirmed');
        }])->orderByDesc('event_date')->paginate(10);
        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        $event->load(['groups.groupTeams.registration', 'groups.rounds.matches.homeRegistration', 'groups.rounds.matches.awayRegistration', 'knockoutMatches.homeRegistration', 'knockoutMatches.awayRegistration', 'knockoutMatches.winner']);
        $registrations = $event->confirmedRegistrations()->with('team')->get();
        $teamRegistration = null;

        if (Auth::guard('team')->check()) {
            $teamRegistration = $event->registrations()->where('team_id', Auth::guard('team')->id())->first();
        }

        return view('events.show', compact('event', 'registrations', 'teamRegistration'));
    }

    public function registerForm(Event $event)
    {
        if (!$event->isRegistrationOpen()) {
            return back()->with('error', 'A nevezés már lezárult ennél az eseménynél.');
        }
        if ($event->isFull()) {
            return back()->with('error', 'Az esemény már betelt.');
        }

        return view('events.register', compact('event'));
    }

    public function register(Request $request, Event $event)
    {
        if (!$event->isRegistrationOpen()) {
            return back()->with('error', 'A nevezés már lezárult.');
        }
        if ($event->isFull()) {
            return back()->with('error', 'Az esemény már betelt.');
        }

        $team = Auth::guard('team')->user();

        if ($team) {
            // Regisztrált csapat
            $existing = $event->registrations()->where('team_id', $team->id)->first();
            if ($existing) {
                return back()->with('error', 'A csapatod már nevezve van erre az eseményre.');
            }

            $drinkPref = in_array($request->drink_preference, ['sor', 'froccs']) ? $request->drink_preference : 'sor';

            $registration = EventRegistration::create([
                'event_id' => $event->id,
                'team_id' => $team->id,
                'status' => 'confirmed',
                'drink_preference' => $drinkPref,
            ]);

            try {
                Mail::to($team->email)->send(new EventRegistrationConfirmation($registration->load('event')));
            } catch (\Exception $e) {
                // Email küldési hiba nem akadályozza a nevezést
            }

            return redirect()->route('events.show', $event)->with('success', 'Sikeres nevezés! Visszaigazoló emailt küldtünk.');
        }

        // Vendég nevezés
        $request->validate([
            'guest_team_name' => 'required|string|max:255',
            'guest_contact_name' => 'required|string|max:255',
            'guest_contact_email' => 'required|email|max:255',
            'guest_contact_phone' => 'nullable|string|max:50',
            'drink_preference' => 'nullable|in:sor,froccs',
        ], [
            'guest_team_name.required' => 'A csapat neve kötelező.',
            'guest_contact_name.required' => 'A kapcsolattartó neve kötelező.',
            'guest_contact_email.required' => 'Az email cím kötelező.',
        ]);

        // Ellenőrzés: van-e már ilyen nevű csapat ennél az eseménynél
        $existing = $event->registrations()->where('guest_team_name', $request->guest_team_name)->first();
        if ($existing) {
            return back()->withErrors(['guest_team_name' => 'Ez a csapatnév már foglalt ennél az eseménynél.'])->withInput();
        }

        $drinkPref = in_array($request->drink_preference, ['sor', 'froccs']) ? $request->drink_preference : 'sor';

        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'guest_team_name' => $request->guest_team_name,
            'guest_contact_name' => $request->guest_contact_name,
            'guest_contact_email' => $request->guest_contact_email,
            'guest_contact_phone' => $request->guest_contact_phone,
            'status' => 'confirmed',
            'drink_preference' => $drinkPref,
        ]);

        try {
            Mail::to($request->guest_contact_email)->send(new EventRegistrationConfirmation($registration->load('event')));
        } catch (\Exception $e) {
            // Email küldési hiba nem akadályozza a nevezést
        }

        return redirect()->route('events.show', $event)->with('success', 'Sikeres nevezés! Visszaigazoló emailt küldtünk.');
    }

    public function cancelRegistration(Event $event)
    {
        if (!Auth::guard('team')->check()) {
            return back()->with('error', 'Bejelentkezés szükséges.');
        }

        if (!$event->isRegistrationOpen()) {
            return back()->with('error', 'A nevezési határidő már lejárt, visszalépés nem lehetséges.');
        }

        $registration = $event->registrations()->where('team_id', Auth::guard('team')->id())->first();
        if ($registration) {
            $registration->delete();
        }

        return redirect()->route('events.show', $event)->with('success', 'Sikeresen visszaléptél a nevezésből.');
    }
}
