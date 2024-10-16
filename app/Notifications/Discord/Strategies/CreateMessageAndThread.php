<?php

namespace App\Notifications\Discord\Strategies;

use App\Contracts\MessageCreationStrategy;
use App\Logic\TableLogic;
use App\Models\Table;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class CreateMessageAndThread implements MessageCreationStrategy
{
    public function __construct(
        private Client $client
    ) {
        //
    }

    /**
     * First a message is created in discord
     * After that, a thread is created, using the created message id
     * Then the id of the thread is linked to the new table into the discord_thread_id column
     *
     * @throws \Exception
     */
    public function handle(int $channelId, array $embedMessage, ?Table $table): string
    {
        $messageId = $this->sendMessage($channelId, $embedMessage);

        $this->saveMessageIdOnTable($messageId, $table);

        $threadId = $this->createThread($channelId, $messageId);

        return $this->saveThreadIdOnTable($threadId, $table);
    }

    public function sendMessage(int $channelId, array $embedMessage): int
    {
        try {
            $messageResponse = $this->client->post(config('discord.api_url').$channelId.'/messages', [
                'headers' => $this->getHeaders(),
                'json' => $embedMessage,
            ]);

            return $this->handleResponse($messageResponse);
        } catch (GuzzleException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function createThread(int $channelId, int $messageId): int
    {
        try {
            $params = [
                'name' => 'Inscriptions & mises à jour',
                'auto_archive_duration' => 10080,
            ];

            $threadResponse = $this->client->post(config('discord.api_url').$channelId.'/messages/'.$messageId.'/threads',
                [
                    'headers' => $this->getHeaders(),
                    'json' => $params,
                ]);

            return $this->handleResponse($threadResponse);

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

    private function handleResponse(ResponseInterface $response): int
    {
        $responseData = json_decode($response->getBody(), true);

        return (int) $responseData['id'];
    }

    private function saveMessageIdOnTable(int $messageId, Table $table): void
    {
        TableLogic::saveThreadId($messageId, $table);
    }

    private function saveThreadIdOnTable(int $threadId, Table $table): string
    {
        TableLogic::saveThreadId($threadId, $table);

        return 'Discord notification and thread created';
    }
}
