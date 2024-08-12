<?php

namespace App\Notifications\Discord;

use App\Actions\Discord\BuildEmbedMessageStructureAction;
use App\Actions\Discord\DefineChannelIdAction;
use App\Actions\Discord\SendDiscordNotificationAction;
use App\DataObjects\DiscordNotificationData;

abstract class DiscordNotification implements NotificationInterface
{
    public function __construct(
        public DefineChannelIdAction $defineChannelIdAction,
        public DiscordNotificationData $discordNotificationData,
        public SendDiscordNotificationAction $sendDiscordNotificationAction,
        private int $channelId,
    ) {
    }

    public function create(): void
    {
        $this->channelId = ($this->defineChannelIdAction)($this->discordNotificationData->day->date);

        $message = $this->buildMessage($this->discordNotificationData);

        $this->send($message);
    }

    abstract public function buildMessage(DiscordNotificationData $discordNotificationData): array;

    public function send(array $message): void
    {
        ($this->sendDiscordNotificationAction)($this->channelId, $message);
    }
}
