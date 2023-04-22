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
     * Handle the event.
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

        // dd($channels[$day->date->dayOfWeek]);

        $message = "Une table de **". $game->name ."** a été crée par ". $event->user->name ."\n __**Date:**__ ". $event->day->date->format('d/m/Y') ." __**Heure:**__ ". $event->table->start_hour."\n Pour vous inscrire rendez vous sur ce lien : http://table-manager.jeuf5892.odns.fr/day/".$event->day->id;

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
