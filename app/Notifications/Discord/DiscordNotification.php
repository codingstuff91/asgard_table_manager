<?php

namespace App\Notifications\Discord;

use App\Actions\Discord\DefineChannelIdAction;
use App\Actions\Discord\SendDiscordNotificationAction;
use App\DataObjects\DiscordNotificationData;

abstract class DiscordNotification implements NotificationInterface
{
    private int $channelId;

    public function __construct(
        public DiscordNotificationData $discordNotificationData,
        public DefineChannelIdAction $defineChannelIdAction,
        public SendDiscordNotificationAction $sendDiscordNotificationAction,
    ) {
        //
    }

    public function handle(): void
    {
        $this->channelId = ($this->defineChannelIdAction)($this->discordNotificationData->day->date);

        $notificationContent = $this->buildMessage($this->discordNotificationData);

        $this->send($notificationContent);
    }

    abstract public function buildMessage(DiscordNotificationData $discordNotificationData): array;

    public function send(array $message): void
    {
        ($this->sendDiscordNotificationAction)($this->channelId, $message);
    }
}
