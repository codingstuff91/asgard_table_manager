<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class editDiscordMessageCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'discord:edit-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Edition de message contenu dans un channel discord';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client();
        $bot_token = config('discord.bot_token');

        $message = "Une table de **Knight** a été crée par Lord Fabius \n __**Date:**__ 30/04/2023 __**Heure:**__ 14:30 \n Pour vous inscrire rendez vous sur ce lien : http://table-manager.jeuf5892.odns.fr/days/14";
        
        $response = $client->patch("https://discord.com/api/v9/channels/1069338721633194025/messages/1099633421531226202", [
            'headers' => [
                'Authorization' => $bot_token,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'content' => $message
            ]
        ]);

        return Command::SUCCESS;
    }
}
