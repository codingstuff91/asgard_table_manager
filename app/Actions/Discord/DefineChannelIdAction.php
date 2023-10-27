<?php

namespace App\Actions\Discord;

use Carbon\Carbon;

class DefineChannelIdAction
{
    public function __invoke(string $date)
    {
        $dayDate = Carbon::create($date);

        return match ($dayDate->dayOfWeek) {
            0 => 1069338721633194025, // Sunday
            5 => 1069338626237931541, // Friday
            6 => 1069338674753437850, // Saturday
            default => 1069369413570138192,
        };
    }
}
