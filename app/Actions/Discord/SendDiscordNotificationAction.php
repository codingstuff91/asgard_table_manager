<?php

namespace App\Actions\Discord;

use GuzzleHttp\Client;

class SendDiscordNotificationAction
{
    public function __invoke(int $channelId, array $embedMessage)
    {
        $client = new Client;

        $client->post(config('discord.api_url').$channelId.'/messages', [
            'headers' => [
                'Authorization' => config('discord.bot_token'),
                'Content-Type' => 'application/json',
            ],
            'json' => $embedMessage,
        ]);
    }
}
