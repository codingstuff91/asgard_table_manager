<?php

namespace App\Notifications\Discord\Strategies;

use App\Contracts\MessageCreationStrategy;
use App\Models\Table;
use GuzzleHttp\Client;

class CreateMessageIntoThread implements MessageCreationStrategy
{
    public function __construct(
        private Client $client
    ) {
        //
    }

    public function handle(int $channelId, array $embedMessage, ?Table $table): string
    {
        $threadId = $table->discord_thread_id;

        $this->client->post(config('discord.api_url').$threadId.'/messages', [
            'headers' => [
                'Authorization' => config('discord.bot_token'),
                'Content-Type' => 'application/json',
            ],
            'json' => $embedMessage,
        ]);

        return 'message sent into thread';
    }
}
