<?php

namespace App\Notifications\Discord;

use App\DataObjects\DiscordNotificationData;
use Illuminate\Support\Str;

class NotificationFactory
{
    public function __invoke(
        string $entity,
        string $type,
        DiscordNotificationData $discordNotificationData
    ): mixed {
        $class = __NAMESPACE__.'\\'.Str::studly($entity).'\\'.Str::studly($type).'Notification';

        return new $class($discordNotificationData);
    }
}
