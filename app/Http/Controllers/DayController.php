<?php

namespace App\Http\Controllers;

use App\Models\Day;
use App\Models\Table;
use Illuminate\Http\Request;
use App\Http\Requests\storeDayRequest;

class DayController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $days = Day::query()->withCount('tables')->orderBy('date', 'desc')->get();

        return view('day.index', compact('days'));
    }

    /**
     * @param \Illuminate\Http\Request $request
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
                        ->get();

        return view('day.show', compact('tables', 'day'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(storeDayRequest $request)
    {      
        $day = Day::create($request->all());

        return redirect()->route('days.index');
    }
}
