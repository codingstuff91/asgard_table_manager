<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventStoreRequest;
use App\Models\Day;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function create(Request $request, Day $day)
    {
        return view('event.create')->with(['day' => $day->id]);
    }

    public function store(EventStoreRequest $request, Day $day)
    {
        Event::create([
            'day_id' => $day->id,
            'name' => $request->name,
            'description' => $request->description,
            'start_hour' => $request->start_hour,
        ]);

        return to_route('days.show', $day);
    }

    public function edit()
    {
        return 'test';
    }

    public function subscribe(Event $event): RedirectResponse
    {
        $event->users()->attach(Auth::user());

        return redirect()->back();
    }

    public function unSubscribe(Event $event): RedirectResponse
    {
        $event->users()->detach(Auth::user());

        return redirect()->back();
    }
}
