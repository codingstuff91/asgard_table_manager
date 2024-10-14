<?php

namespace App\Http\Controllers\Day;

use App\DataObjects\DiscordNotificationData;
use App\Http\Controllers\Controller;
use App\Http\Requests\storeDayRequest;
use App\Models\Category;
use App\Models\Day;
use App\Models\Event;
use App\Models\Table;
use App\Notifications\Discord\NotificationFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DayController extends Controller
{
    public function __construct(
        public DiscordNotificationData $discordNotificationData,
        public NotificationFactory $notificationFactory,
    ) {
        //
    }

    public function index(Request $request): View
    {
        $days = Day::query()
            ->withCount(['tables', 'events'])
            ->where('date', '>=', now()->format('Y-m-d'))
            ->orderBy('date', 'asc')
            ->get();

        return view('day.index', compact('days'));
    }

    public function create(Request $request): View
    {
        return view('day.create');
    }

    public function show(Day $day): View
    {
        $tables = Table::with(['users', 'game.category'])
            ->where('day_id', $day->id)
            ->orderBy('start_hour', 'asc')
            ->get();

        $tablesCountPerCategory = Category::withCount([
            'tables' => function ($query) use ($day) {
                $query->where('day_id', $day->id);
            },
        ])->get();

        $events = Event::with('users')
            ->where('day_id', $day->id)
            ->get();

        return view('day.show', compact('tables', 'day', 'tablesCountPerCategory', 'events'));
    }

    public function store(storeDayRequest $request): RedirectResponse
    {
        Day::create($request->all());

        return redirect()->route('days.index');
    }
}
