<?php

namespace App\Notifications\Discord\Strategies;

use App\Contracts\MessageCreationStrategy;
use App\Models\Table;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CreateMessage implements MessageCreationStrategy
{
    public function __construct(
        private Client $client
    ) {
        //
    }

    public function handle(int $channelId, array $embedMessage, ?Table $table): string
    {
        try {
            $this->client->post(config('discord.api_url').$channelId.'/messages', [
                'headers' => $this->getHeaders(),
                'json' => $embedMessage,
            ]);

            return 'Discord notification to cancel the table sent';
        } catch (GuzzleException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function getHeaders(): array
    {
        return [
            'Authorization' => config('discord.bot_token'),
            'Content-Type' => 'application/json',
        ];
    }
}
