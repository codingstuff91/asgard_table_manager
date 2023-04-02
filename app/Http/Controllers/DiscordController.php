<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use TeamReflex\DiscordPhp\Discord;

class DiscordController extends Controller
{
    public function test()
    {
        $client = new Client();
        $response = $client->post('https://discord.com/api/v9/channels/800385339737899018/messages', [
            'headers' => [
                'Authorization' => 'Bot MTA5MjA5MDIxOTY1MTY2MTg2NA.GTKuCZ.qBrwlJHbF5HZED-qbydZ07phwhSxyuHyoz8QG0',
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'content' => 'Ceci est un message de teste généré automatiquement avec un lien http://table-manager.jeuf5892.odns.fr'
            ]
        ]);

    }
}
