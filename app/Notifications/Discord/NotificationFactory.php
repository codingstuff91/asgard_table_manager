<?php

namespace App\Notifications\Discord;

use App\Actions\Discord\DefineChannelIdAction;
use App\Actions\Discord\SendDiscordNotificationAction;
use App\DataObjects\DiscordNotificationData;
use Illuminate\Support\Str;

class NotificationFactory
{
    public function __construct(
        public DefineChannelIdAction $defineChannelIdAction,
        public SendDiscordNotificationAction $sendDiscordNotificationAction,
    ) {
        //
    }

    public function __invoke(
        string $type,
        DiscordNotificationData $discordNotificationData
    ): mixed {
        $class = __NAMESPACE__.'\\'.Str::studly($type).'Notification';

        return new $class($discordNotificationData, $this->defineChannelIdAction, $this->sendDiscordNotificationAction);
    }
}
