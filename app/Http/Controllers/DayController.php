<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeDayRequest;
use App\Models\Category;
use App\Models\Day;
use App\Models\Event;
use App\Models\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DayController extends Controller
{
    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        $days = Day::query()
            ->withCount(['tables', 'events'])
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get();

        return view('day.index', compact('days'));
    }

    /**
     * @param Request $request
     * @return View
     */
    public function create(Request $request)
    {
        return view('day.create');
    }

    /**
     * @param Day $day
     * @return View
     */
    public function show(Day $day)
    {
        $tables = Table::with(['users', 'game.category'])
            ->where('day_id', $day->id)
            ->orderBy('start_hour', 'asc')
            ->get();

        $tablesCountPerCategory = Category::withCount([
            'tables' => function ($query) use ($day) {
                $query->where('day_id', $day->id);
            }
        ])->get();

        $events = Event::with('users')
            ->where('day_id', $day->id)
            ->get();

        return view('day.show', compact('tables', 'day', 'tablesCountPerCategory', 'events'));
    }

    /**
     * @param storeDayRequest $request
     * @return RedirectResponse
     */
    public function store(storeDayRequest $request)
    {
        $day = Day::create($request->all());

        return redirect()->route('days.index');
    }
}
