<?php

namespace App\Services;

use GuzzleHttp\Client;

class DiscordService
{
    public function __construct(
        public Client $client
    ) {
        //
    }

    public function deleteMessage(int $channelId, int $messageId): void
    {
        try {
            $this->client->delete(config('discord.api_url')."$channelId/messages/$messageId", [
                'headers' => $this->getHeaders(),
            ]);
        } catch (\Exception $e) {
            \Log::error("Erreur de suppression du message $messageId : ".$e->getMessage());
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
