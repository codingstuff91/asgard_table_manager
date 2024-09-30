<?php

namespace App\Actions\Discord;

use App\Contracts\MessageCreationStrategy;

class SendDiscordNotificationAction
{
    // @phpstan-ignore-next-line
    public function __invoke(
        MessageCreationStrategy $messageStrategy,
        int $channelId,
        array $embedMessage,
    ): string {

        return $messageStrategy->handle($channelId, $embedMessage);
    }
}
