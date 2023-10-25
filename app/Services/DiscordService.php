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

    public static function buildEmbedStructure($event, string $eventType): array
    {
        return match ($eventType) {
            'create', 'update' => self::generateLongEmbed($event, $eventType),
            'delete' => self::generateShortEmbed($event, $eventType),
        };
    }

    public static function generateLongEmbed($event, string $eventType): array
    {
        return [
            'content' => self::setEmbedContent($eventType),
            'embeds' => [
                [
                    'title' => 'Table de : ' . $event->game->name,
                    'description' => self::setEmbedTitle($event->day),
                    'author' => [
                        'name' => 'Créateur : ' . Auth::user()->name,
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

    private static function generateShortEmbed($event, string $eventType): array
    {
        return [
            'content' => self::setEmbedContent($eventType),
            'embeds' => [
                [
                    'title' => 'La Table de '.$event->game->name.' prévue le '.$event->day->date->format('d/m/Y').' à '.$event->table->start_hour.' est annulée. ',
                    'color' => '16711680',
                    'author' => [
                        'name' => 'Annulée par : '.$event->user->name,
                    ],
                ],
            ],
        ];
    }

    public static function setEmbedContent(string $eventType): string
    {
        return match ($eventType) {
            'create' => EmbedMessageTitle::CREATED->value,
            'update' => EmbedMessageTitle::UPDATED->value,
            'delete' => EmbedMessageTitle::DELETED->value,
        };
    }

    private static function setEmbedTitle(Day $day): string
    {
        return 'Plus d\'informations sur '.config('app.url').'/days/'.$day->id;
    }

    public static function sendNotification(string $channelId, array $embedMessage): void
    {
        $client = new Client;

        $client->post(config('discord.api_url').$channelId.'/messages', [
            'headers' => [
                'Authorization' => config('discord.bot_token'),
                'Content-Type' => 'application/json',
            ],
            'json' => $embedMessage,
        ]);
    }
}
