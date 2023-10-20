<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Day;
use GuzzleHttp\Client;

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

    public static function sendNotification(string $channelId, array $embedMessage): void
    {
        $bot_token = config('discord.bot_token');

        $response = $client->post("https://discord.com/api/v9/channels/". $channelId ."/messages", [
            'headers' => [
                'Authorization' => $bot_token,
                'Content-Type' => 'application/json'
            ],
            'json' => $embedMessage,
        ]);
    }
}
