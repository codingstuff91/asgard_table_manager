<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Day;

class DiscordService
{
    /**
     * Get the Discord channel ID according 
     */
    public function getChannelByDate(String $date) :int
    {   
        $dayDate = Carbon::create($date);
             
        $channelId = match ($dayDate->dayOfWeek) {
            0       => 1069338721633194025,
            5       => 1069338626237931541,
            6       => 1069338674753437850,
            default => 1069369413570138192,
        };

        return $channelId;
    }
}
