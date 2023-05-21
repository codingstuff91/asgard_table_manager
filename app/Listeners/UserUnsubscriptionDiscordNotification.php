<?php

namespace App\Listeners;

use GuzzleHttp\Client;
use App\Services\DiscordService;
use App\Events\UserTableUnsubscribed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserUnsubscriptionDiscordNotification
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserTableUnsubscribed $event)
    {                
        $client = new Client();
        $bot_token = config('discord.bot_token');

        $day = $event->table->day;
        $game = $event->table->game;

        $discordChannelId = resolve(DiscordService::class)->getChannelByDate($day->date);

        $description = 'Plus d\'informations sur http://table-manager.jeuf5892.odns.fr/days/' . $day->id;

        $embedMessage = [
            "content" => "Désinscription de joueur",
            "embeds"=> [
                [
                    'title' => $event->user->name . ' s\'est désinscrit de la table de ' . $game->name,
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

        $response = $client->post("https://discord.com/api/v9/channels/". $discordChannelId ."/messages", [
            'headers' => [
                'Authorization' => $bot_token,
                'Content-Type' => 'application/json'
            ],
            'json' => $embedMessage,
        ]);
    }
}
