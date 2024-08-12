<?php

namespace App\Notifications\Discord;

use App\DataObjects\DiscordNotificationData;
use Illuminate\Support\Str;

class NotificationFactory
{
    public static function create(string $type, DiscordNotificationData $discordNotificationData): NotificationFactory
    {
        $class = __NAMESPACE__.'\\'.Str::studly($type) . 'Notification';

        return new $class();
    }
}
