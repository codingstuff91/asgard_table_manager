<?php

namespace App\Listeners;

use App\Events\TableDeleted;
use App\Services\DiscordService;
use GuzzleHttp\Client;

class TableDeletedDiscordNotification
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TableDeleted $event)
    {
        $client = new Client;
        $bot_token = config('discord.bot_token');

        $discordChannelId = resolve(DiscordService::class)->getChannelByDate($event->day->date);

        $embedMessage = [
            'content' => 'ANNULATION DE TABLE',
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

        $response = $client->post('https://discord.com/api/v9/channels/'.$discordChannelId.'/messages', [
            'headers' => [
                'Authorization' => $bot_token,
                'Content-Type' => 'application/json',
            ],
            'json' => $embedMessage,
        ]);
    }
}
