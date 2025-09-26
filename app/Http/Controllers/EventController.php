<?php

namespace App\Http\Controllers;

use App\DataObjects\DiscordNotificationData;
use App\Http\Requests\EventStoreRequest;
use App\Models\Day;
use App\Models\Event;
use App\Notifications\Discord\NotificationFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function __construct(
        public DiscordNotificationData $discordNotificationData,
        public NotificationFactory $notificationFactory,
    ) {
        //
    }

    public function create(Request $request, Day $day): View
    {
        return view('event.create')->with([
            'day' => $day->id,
            'isWorkshop' => (bool) $request->workshop,
        ]);
    }

    public function store(EventStoreRequest $request, Day $day): RedirectResponse
    {
        Event::create([
            'day_id' => $day->id,
            'name' => $request->name,
            'description' => $request->description,
            'workshop' => $request->workshop ?? false,
            'start_hour' => $request->start_hour,
        ]);

        $discordNotificationData = $this->discordNotificationData::make(
            game: null,
            table: null,
            day: $day,
            extra: [
                'name' => $request->name,
                'description' => $request->description,
                'start_hour' => $request->start_hour,
            ]
        );

        $discordNotification = ($this->notificationFactory)(entity: 'event', type: 'create-event',
            discordNotificationData: $discordNotificationData);
        $discordNotification->handle();

        return to_route('days.show', $day);
    }

    public function edit(Event $event): View
    {
        return view('event.edit', compact('event'));
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $event->update([
            'name' => $request->name,
            'start_hour' => substr($request->start_hour, 0, 5),
            'description' => $request->description,
        ]);

        $discordNotificationData = $this->discordNotificationData::make(
            game: null,
            table: null,
            day: $event->day,
            extra: [
                'name' => $request->name,
                'description' => $request->description,
                'start_hour' => $request->start_hour,
            ]
        );

        $discordNotification = ($this->notificationFactory)(entity: 'event', type: 'update-event',
            discordNotificationData: $discordNotificationData);
        $discordNotification->handle();

        return to_route('days.show', $event->day_id);
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        $discordNotificationData = $this->discordNotificationData::make(
            game: null,
            table: null,
            day: $event->day,
            extra: [
                'name' => $event->name,
                'description' => $event->description,
                'start_hour' => $event->start_hour,
            ]
        );

        $discordNotification = ($this->notificationFactory)(entity: 'event', type: 'cancel-event',
            discordNotificationData: $discordNotificationData);
        $discordNotification->handle();

        return to_route('days.show', $event->day_id);
    }
}
