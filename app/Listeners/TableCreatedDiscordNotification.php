<?php

namespace App\Listeners;

use App\Models\Game;
use GuzzleHttp\Client;
use App\Events\TableCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TableCreatedDiscordNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

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

        // Vendredi => 5
        // Samedi => 6
        // Dimanche => 0
        $dayOfWeek = $event->day->date->dayOfWeek;

        $channels = [
            0 => "1069338721633194025",
            5 => "1069338626237931541",
            6 => "1069338674753437850",
        ];

        $description = 'Plus d\'informations sur http://table-manager.jeuf5892.odns.fr/days/' . $event->day->id;

        $embedMessage = [
            "content" => "Une table est disponible sur ASGARD-TABLE-MANAGER",
            "embeds"=> [
                [
                    'title' => 'Table de : ' . $game->name,
                    'description' => $description,
                    "author" => [
                        "name" => "Créateur : " . $event->user->name,
                    ],
                    'color' => '16711680',
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
                ]
            ]
        ];

        $response = $client->post("https://discord.com/api/v9/channels/". $channels[$dayOfWeek] ."/messages", [
            'headers' => [
                'Authorization' => $bot_token,
                'Content-Type' => 'application/json'
            ],
            'json' => $embedMessage,
        ]);
    }
}
