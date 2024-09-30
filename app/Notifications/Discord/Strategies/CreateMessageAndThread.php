<?php

namespace App\Notifications\Discord\Strategies;

use App\Contracts\MessageCreationStrategy;
use GuzzleHttp\Client;

class CreateMessageAndThread implements MessageCreationStrategy
{
    public function __construct(
        private Client $client
    ) {
        //
    }

    public function handle(int $channelId, array $embedMessage): string
    {
        $messageResponse = $this->client->post(config('discord.api_url').$channelId.'/messages', [
            'headers' => [
                'Authorization' => config('discord.bot_token'),
                'Content-Type' => 'application/json',
            ],
            'json' => $embedMessage,
        ]);

        $messageData = json_decode($messageResponse->getBody(), true);
        $messageId = $messageData['id'];

        $params = [
            'name' => 'Test fil de table',
            'auto_archive_duration' => 10080,
        ];

        $threadResponse = $this->client->post(config('discord.api_url').$channelId.'/messages/'.$messageId.'/threads',
            [
                'headers' => [
                    'Authorization' => config('discord.bot_token'),
                    'Content-Type' => 'application/json',
                ],
                'json' => $params,
            ]);

        return 'Discord notification sent';
    }
}
