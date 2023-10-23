<?php

namespace App\Services;

use App\Enums\EmbedMessageTitle;
use App\Models\Day;
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

    public static function buildEmbedNotificationMessage($event, string $eventType)
    {
        return [
            'content' => self::setNotificationContent($eventType),
            'embeds' => [
                [
                    'title' => 'Table de : '.$event->game->name,
                    'description' => self::setNotificationTitle($event->day),
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
    }

    public static function setNotificationContent(string $eventType): string
    {
        return match ($eventType) {
            'create' => EmbedMessageTitle::CREATED->value,
            'update' => EmbedMessageTitle::UPDATED->value,
            'delete' => EmbedMessageTitle::DELETED->value,
        };
    }

    private static function setNotificationTitle(Day $day)
    {
        return 'Plus d\'informations sur '.config('app.url').'/days/'.$day->id;
    }

    public static function sendNotification(string $channelId, array $embedMessage): void
    {
        $bot_token = config('discord.bot_token');

        $client = new Client;

        $client->post('https://discord.com/api/v9/channels/'.$channelId.'/messages', [
            'headers' => [
                'Authorization' => $bot_token,
                'Content-Type' => 'application/json',
            ],
            'json' => $embedMessage,
        ]);
    }
}
