<?php

namespace App\Http\Controllers;

use App\DataObjects\DiscordNotificationData;
use App\Logic\UserLogic;
use App\Models\Event;
use App\Models\Table;
use App\Notifications\Discord\NotificationFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SubscribingController extends Controller
{
    public function __construct(
        public NotificationFactory $notificationFactory,
    ) {
        //
    }

    public function tableSubscribe(Table $table): RedirectResponse
    {
        if (app(UserLogic::class)->hasSubscribedToAnotherTableWithTheSameStartHour($table->day, $table)) {
            return redirect()->route('days.show',
                $table->day)->with(['error' => 'Vous êtes déjà inscrit à une autre table à la même heure ce jour là']);
        }

        if (app(UserLogic::class)->isAlreadySubscribedToATable($table)) {
            return redirect()
                ->route('days.show', $table->day)
                ->with([
                    'error' => 'Vous êtes déjà inscrit à cette table',
                ]);
        }

        $table->users()->attach(Auth::user());

        $discordNotificationData = DiscordNotificationData::make($table->game, $table, $table->day);

        $discordNotification = ($this->notificationFactory)(entity: 'user', type: 'user-subscription',
            discordNotificationData: $discordNotificationData);
        $discordNotification->handle();

        return redirect()->back();
    }

    public function tableUnsubscribe(Table $table): RedirectResponse
    {
        $table->users()->detach(Auth::user());

        $discordNotificationData = DiscordNotificationData::make($table->game, $table, $table->day);

        $discordNotification = ($this->notificationFactory)(entity: 'user', type: 'user-unsubscription',
            discordNotificationData: $discordNotificationData);
        $discordNotification->handle();

        return redirect()->back();
    }

    public function eventSubscribe(Event $event): RedirectResponse
    {
        $event->users()->attach(Auth::user());

        return redirect()->back();
    }

    public function eventUnsubscribe(Event $event): RedirectResponse
    {
        $event->users()->detach(Auth::user());

        return redirect()->back();
    }
}
