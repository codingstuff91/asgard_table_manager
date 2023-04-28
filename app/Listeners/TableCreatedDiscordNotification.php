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

        $message = "Une table de **". $game->name ."** a été crée par ". $event->user->name ."\n __**Date:**__ ". $event->day->date->format('d/m/Y') ." __**Heure:**__ ". $event->table->start_hour."\n Pour vous inscrire rendez vous sur ce lien : http://table-manager.jeuf5892.odns.fr/days/".$event->day->id;

        $embeddedMessage = [
            {
                "content": "Une table a été ouverte sur ASGARD-TABLE-MANAGER",
                "embeds": [
                    {
                        "title": "Partie de XXXX",
                        "description": "Plus d'informations sur http://table-manager.jeuf5892.odns.fr/days",
                        "color": 65280,
                        "author": {
                            "name": "Par : Mattou"
                        },
                        "fields": [
                            {
                                "name": "Date",
                                "value": "28/04/2023",
                                "inline": true
                            },
                            {
                                "name": "Heure",
                                "value": "21:00",
                                "inline": true
                            },
                            {
                                "name": "Nombre joueurs",
                                "value": 10,
                                "inline": true
                            },
                            {
                                "name": "Nombre points",
                                "value": 10000,
                                "inline": true
                            }
                        ]
                    }
                ]
            }
        ];
        
        $response = $client->post("https://discord.com/api/v9/channels/". $channels[$dayOfWeek] ."/messages", [
            'headers' => [
                'Authorization' => $bot_token,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'content' => $message
            ]
        ]);
    }
}
