<?php

namespace App\Listeners;

use App\Models\Game;
use GuzzleHttp\Client;
use App\Events\TableCreated;
use App\Services\DiscordService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TableCreatedDiscordNotification
{
    /**
     * Send a discord notification according to day of week
     *
     * @param  \App\Events\TableCreated  $event
     * @return void
     */
    public function handle(TableCreated $event)
    {
        $game = Game::find($event->game);
        
        $client = new Client();
        $bot_token = config('discord.bot_token');

        $discordChannelId = resolve(DiscordService::class)->getChannelByDate($event->day->date);

        $tableLinkText = 'Plus d\'informations sur http://table-manager.jeuf5892.odns.fr/days/' . $event->day->id;

        $embedMessage = [
            "content" => "Une table est disponible sur ASGARD-TABLE-MANAGER",
            "embeds"=> [
                [
                    'title' => 'Table de : ' . $game->name,
                    'description' => $tableLinkText,
                    "author" => [
                        "name" => "Créateur : " . $event->user->name,
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
                        ]
                    ],
                    'footer' => [
                        'text' => $event->table->description,
                    ],
                ]
            ]
        ];

        $response = $client->post("https://discord.com/api/v9/channels/". $discordChannelId ."/messages", [
            'headers' => [
                'Authorization' => $bot_token,
                'Content-Type' => 'application/json'
            ],
            'json' => $embedMessage,
        ]);
    }
}
