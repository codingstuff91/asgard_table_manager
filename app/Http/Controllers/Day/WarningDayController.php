<?php

namespace App\Http\Controllers\Day;

use App\Http\Controllers\Controller;
use App\Http\Requests\WarningCancelDayRequest;
use App\Models\Day;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class WarningDayController extends Controller
{
    public function justify(Day $day): View
    {
        return view('day.warning', compact('day'));
    }

    public function confirm(Day $day, WarningCancelDayRequest $request): RedirectResponse
    {
        $day->update([
            'explanation' => $request->explanation,
        ]);

        return to_route('days.index');
    }
}
