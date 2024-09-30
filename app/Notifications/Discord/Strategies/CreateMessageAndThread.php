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
        $messageId = $this->sendMessage($channelId, $embedMessage);

        $threadId = $this->createThread($channelId, $messageId);

        return $this->saveThreadIdOnTable($threadId);
    }

    public function sendMessage(int $channelId, array $embedMessage): int
    {
        $messageResponse = $this->client->post(config('discord.api_url').$channelId.'/messages', [
            'headers' => [
                'Authorization' => config('discord.bot_token'),
                'Content-Type' => 'application/json',
            ],
            'json' => $embedMessage,
        ]);

        $messageData = json_decode($messageResponse->getBody(), true);

        return $messageData['id'];
    }

    public function createThread(int $channelId, int $messageId): string
    {
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

        $threadData = json_decode($threadResponse->getBody(), true);

        return $threadData['id'];
    }

    private function saveThreadIdOnTable(int $threadId): string
    {
        return 'Discord notification and thread created';
    }
}
