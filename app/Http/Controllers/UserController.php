<?php

namespace App\Http\Controllers;

use App\Logic\UserLogic;
use App\Models\Table;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function subscribe(Table $table): RedirectResponse
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

        $discordNotificationData = $this->discordNotificationData::make($table->game, $table, $table->day);

        $discordNotification = ($this->notificationFactory)(entity: 'user', type: 'user-subscription',
            discordNotificationData: $discordNotificationData);
        $discordNotification->handle();

        return redirect()->back();
    }

    public function unSubscribe(Table $table): RedirectResponse
    {
        $table->users()->detach(Auth::user());

        $discordNotificationData = $this->discordNotificationData::make($table->game, $table, $table->day);

        $discordNotification = ($this->notificationFactory)(entity: 'user', type: 'user-unsubscription',
            discordNotificationData: $discordNotificationData);
        $discordNotification->handle();

        return redirect()->back();
    }
}
