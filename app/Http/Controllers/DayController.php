<?php

namespace App\Http\Controllers;

use App\Actions\Day\DeleteDayTablesAction;
use App\Actions\Day\DisableDayAction;
use App\Actions\Discord\CreateDayDiscordNotificationAction;
use App\DataObjects\DiscordNotificationData;
use App\Http\Requests\storeDayRequest;
use App\Http\Requests\WarningCancelDayRequest;
use App\Models\Category;
use App\Models\Day;
use App\Models\Event;
use App\Models\Table;
use App\Notifications\Discord\NotificationFactory;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DayController extends Controller
{
    public function __construct(
        public CreateDayDiscordNotificationAction $createDiscordNotificationAction,
        public DiscordNotificationData $discordNotificationData,
        public NotificationFactory $notificationFactory,
    ) {
        //
    }

    /**
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
     * @return View
     */
    public function create(Request $request)
    {
        return view('day.create');
    }

    /**
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
            },
        ])->get();

        $events = Event::with('users')
            ->where('day_id', $day->id)
            ->get();

        return view('day.show', compact('tables', 'day', 'tablesCountPerCategory', 'events'));
    }

    /**
     * @return RedirectResponse
     */
    public function store(storeDayRequest $request)
    {
        $day = Day::create($request->all());

        return redirect()->route('days.index');
    }

    public function edit_warning(Day $day)
    {
        return view('day.warning', compact('day'));
    }

    public function confirm_warning(Day $day, WarningCancelDayRequest $request): RedirectResponse
    {
        $day->update([
            'explanation' => $request->explanation,
        ]);

        return to_route('days.index');
    }

    public function edit_cancel(Day $day)
    {
        return view('day.cancel', compact('day'));
    }

    public function confirm_cancel(
        Day $day,
        WarningCancelDayRequest $request,
    ): RedirectResponse {
        try {
            app(DisableDayAction::class)->execute($day, $request->explanation);

            app(DeleteDayTablesAction::class)->execute($day);

            $discordNotificationData = $this->discordNotificationData::make(
                game: null,
                table: null,
                day: $day,
                extra: ['explanation' => $request->explanation]
            );

            $discordNotification = ($this->notificationFactory)('cancel-day', $discordNotificationData);
            $discordNotification->handle();
        } catch (Exception $e) {
            Log::error('Problem during day cancellation update: '.$e->getMessage());

            return redirect()
                ->route('days.show', $day)
                ->with([
                    'error' => 'Une erreur est survenue lors de l\'annulation de la session.',
                ]);
        }

        return to_route('days.index');
    }
}
