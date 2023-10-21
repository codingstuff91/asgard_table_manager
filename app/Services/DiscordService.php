<?php

namespace App\Services;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

class DiscordService
{
    /**
     * Get the Discord channel ID according
     */
    public function getChannelByDate(string $date): int
    {
        $dayDate = Carbon::create($date);

        $channelId = match ($dayDate->dayOfWeek) {
            0 => 1069338721633194025,
            5 => 1069338626237931541,
            6 => 1069338674753437850,
            default => 1069369413570138192,
        };

        return $channelId;
    }

    public static function buildEmbedNotificationMessage($event)
    {
        $tableLinkText = 'Plus d\'informations sur http://table-manager.jeuf5892.odns.fr/days/'.$event->day->id;

        $embedMessage = [
            'content' => 'Une table est disponible sur ASGARD-TABLE-MANAGER',
            'embeds' => [
                [
                    'title' => 'Table de : '.$event->game->name,
                    'description' => $tableLinkText,
                    'author' => [
                        'name' => 'Créateur : '.Auth::user()->name,
                    ],
                    'color' => '65280',
                    'fields' => [
                        [
                            'name' => 'Date',
                            'value' => $event->day->date->format('d/m/Y'),
                            'inline' => true,
                        ],
                        [
                            'name' => 'Heure',
                            'value' => $event->table->start_hour,
                            'inline' => true,
                        ],
                    ],
                    'footer' => [
                        'text' => $event->table->description,
                    ],
                ],
            ],
        ];

        return $embedMessage;
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
