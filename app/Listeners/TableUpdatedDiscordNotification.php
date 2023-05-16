<?php

namespace App\Listeners;

use App\Models\Game;
use GuzzleHttp\Client;
use App\Events\TableUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class TableUpdatedDiscordNotification
{
    /**
     * Send a discord notification according to day of week
     *
     * @param  \App\Events\TableUpdated  $event
     * @return void
     */
    public function handle(TableUpdated $event)
    {
        dd('update table received');

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

        $tableLinkText = 'Plus d\'informations sur http://table-manager.jeuf5892.odns.fr/days/' . $event->day->id;

        $embedMessage = [
            "content" => "La table de " . $game->name . " a été mise à jour",
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

        $response = $client->post("https://discord.com/api/v9/channels/". $channels[$dayOfWeek] ."/messages", [
            'headers' => [
                'Authorization' => $bot_token,
                'Content-Type' => 'application/json'
            ],
            'json' => $embedMessage,
        ]);
    }
}
