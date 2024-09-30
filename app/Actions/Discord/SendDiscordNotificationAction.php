<?php

namespace App\Actions\Discord;

use App\Contracts\MessageCreationStrategy;
use App\Models\Table;

class SendDiscordNotificationAction
{
    // @phpstan-ignore-next-line
    public function __invoke(
        MessageCreationStrategy $messageStrategy,
        int $channelId,
        array $embedMessage,
        Table $table
    ): string {

        return $messageStrategy->handle($channelId, $embedMessage, $table);
    }
}
