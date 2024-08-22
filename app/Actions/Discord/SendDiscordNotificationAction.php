<?php

namespace App\Actions\Discord;

use GuzzleHttp\Client;

class SendDiscordNotificationAction
{
    public function __construct(private Client $client)
    {
        //
    }

    // @phpstan-ignore-next-line
    public function __invoke(int $channelId, array $embedMessage): string
    {
        $this->client->post(config('discord.api_url').$channelId.'/messages', [
            'headers' => [
                'Authorization' => config('discord.bot_token'),
                'Content-Type' => 'application/json',
            ],
            'json' => $embedMessage,
        ]);

        return 'Discord notification sent';
    }
}
