<?php

namespace App\Listeners;

use GuzzleHttp\Client;
use App\Events\UserTableSubscribed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserSubscriptionDiscordNotification
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
     * Handle the event.
     *
     * @param  \App\Events\TableCreated  $event
     * @return void
     */
    public function handle(UserTableSubscribed $event)
    {        
        $client = new Client();
        $bot_token = config('discord.bot_token');

        $day = $event->table->day;
        $game = $event->table->game;

        // Vendredi => 5
        // Samedi => 6
        // Dimanche => 0
        $dayOfWeek = $day->date->dayOfWeek;

        $channels = [
            0 => "1069338721633194025",
            5 => "1069338626237931541",
            6 => "1069338674753437850",
        ];

        $description = 'Plus d\'informations sur http://table-manager.jeuf5892.odns.fr/days/' . $day->id;

        $embedMessage = [
            "content" => "Inscription",
            "embeds"=> [
                [
                    'title' => $event->user->name . ' s\'est inscrit à la table de ' . $game->name,
                    'description' => $description,
                    'color' => '16711680',
                    'fields' => [
                        [
                            'name' => 'Date',
                            'value' => $day->date->format('d/m/Y'),
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
