<?php

namespace App\Http\Controllers\Day;

use App\Actions\Day\DeleteDayTablesAction;
use App\Actions\Day\DisableDayAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\WarningCancelDayRequest;
use App\Models\Day;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class CancelDayController extends Controller
{
    public function justify(Day $day): View
    {
        return view('day.cancel', compact('day'));
    }

    public function confirm(
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

            $discordNotification = ($this->notificationFactory)(entity: 'day', type: 'cancel-day',
                discordNotificationData: $discordNotificationData);
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
