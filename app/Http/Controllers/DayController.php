<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeDayRequest;
use App\Models\Day;
use App\Models\Event;
use App\Models\Table;
use Illuminate\Http\Request;

class DayController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $days = Day::query()
            ->withCount('tables')
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get();

        return view('day.index', compact('days'));
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('day.create');
    }

    public function show(Day $day)
    {
        $tables = Table::with(['users', 'game'])
            ->where('day_id', $day->id)
            ->orderBy('start_hour', 'asc')
            ->get();

        $events = Event::with('users')
            ->where('day_id', $day->id)
            ->get();

//        dd($events);

        return view('day.show', compact('tables', 'day', 'events'));
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeDayRequest $request)
    {
        $day = Day::create($request->all());

        return redirect()->route('days.index');
    }
}
