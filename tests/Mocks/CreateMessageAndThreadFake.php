<?php

namespace Tests\Mocks;

use App\Contracts\MessageCreationStrategy;
use App\Logic\TableLogic;
use App\Models\Table;

class CreateMessageAndThreadFake implements MessageCreationStrategy
{
    /**
     * This class is a dummy representation of the real strategy class
     * The ID of the discord message is useless here
     * Only the ID of the discord thread is useful here
     *
     * @see CreateMessageAndThread
     */
    public function handle(int $channelId, array $embedMessage, ?Table $table): string
    {
        $threadId = $this->createThread();

        return $this->saveThreadIdOnTable($threadId, $table);
    }

    public function createThread(): int
    {
        return 123456789;
    }

    private function saveThreadIdOnTable(int $threadId, Table $table): string
    {
        TableLogic::saveThreadId($threadId, $table);

        return 'Discord notification and thread created';
    }
}