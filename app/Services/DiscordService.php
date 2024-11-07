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

    public function getMessages()
    {
        $response = $this->client->get(config('discord.api_url').'882668631891247118/messages', [
            'headers' => $this->getHeaders(),
        ]);

        return json_decode($response->getBody()->getContents());
    }

    public function deleteMessage(string $channelId, string $messageId): void
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
