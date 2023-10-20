<?php

namespace App\Listeners;

use App\Events\TableUpdated;
use App\Models\Game;
use App\Services\DiscordService;
use GuzzleHttp\Client;

class TableUpdatedDiscordNotification
{
    /**
     * Send a discord notification according to day of week
     *
     * @return void
     */
    public function handle(TableUpdated $event)
    {
        $game = Game::find($event->game);

        $client = new Client;
        $bot_token = config('discord.bot_token');

        $discordChannelId = resolve(DiscordService::class)->getChannelByDate($event->day->date);

        $tableLinkText = 'Plus d\'informations sur http://table-manager.jeuf5892.odns.fr/days/'.$event->day->id;

        $embedMessage = [
            'content' => 'Mise à jour de table',
            'embeds' => [
                [
                    'title' => 'Jeu : '.$game->name,
                    'description' => $tableLinkText,
                    'author' => [
                        'name' => 'Créateur : '.$event->user->name,
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

        $response = $client->post('https://discord.com/api/v9/channels/'.$discordChannelId.'/messages', [
            'headers' => [
                'Authorization' => $bot_token,
                'Content-Type' => 'application/json',
            ],
            'json' => $embedMessage,
        ]);
    }
}
