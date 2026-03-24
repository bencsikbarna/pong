<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::withCount('confirmedRegistrations')->orderByDesc('created_at')->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'event_date' => 'required|date',
            'registration_deadline' => 'nullable|date|before:event_date',
            'max_teams' => 'required|integer|min:2|max:128',
        ], [
            'name.required' => 'Az esemény neve kötelező.',
            'event_date.required' => 'A dátum kötelező.',
            'max_teams.required' => 'A maximális csapatszám kötelező.',
            'registration_deadline.before' => 'A nevezési határidő az esemény dátuma előtt kell legyen.',
        ]);

        Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'event_date' => $request->event_date,
            'registration_deadline' => $request->registration_deadline,
            'max_teams' => $request->max_teams,
            'status' => 'registration_open',
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Esemény sikeresen létrehozva!');
    }

    public function show(Event $event)
    {
        $event->load(['confirmedRegistrations.team', 'groups.groupTeams.registration', 'knockoutMatches.homeRegistration', 'knockoutMatches.awayRegistration']);
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'event_date' => 'required|date',
            'registration_deadline' => 'nullable|date',
            'max_teams' => 'required|integer|min:2|max:128',
            'status' => 'required|in:registration_open,registration_closed,group_stage,knockout_stage,finished',
        ]);

        $event->update($request->only(['name', 'description', 'location', 'event_date', 'registration_deadline', 'max_teams', 'status']));

        return redirect()->route('admin.events.show', $event)->with('success', 'Esemény frissítve!');
    }

    public function closeRegistration(Event $event)
    {
        $event->update(['status' => 'registration_closed']);
        return redirect()->route('admin.events.show', $event)->with('success', 'Nevezés lezárva!');
    }

    public function removeRegistration(Event $event, $registrationId)
    {
        $registration = $event->registrations()->findOrFail($registrationId);

        if ($event->status !== 'registration_open' && $event->status !== 'registration_closed') {
            return back()->with('error', 'Csoportbeosztás után nem lehet törölni nevezést.');
        }

        $registration->delete();
        return back()->with('success', 'Nevezés törölve.');
    }
}
