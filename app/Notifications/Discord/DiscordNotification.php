<?php

namespace App\Notifications\Discord;

use App\Actions\Discord\DefineChannelIdAction;
use App\DataObjects\DiscordNotificationData;

abstract class DiscordNotification implements NotificationInterface
{
    protected int $channelId;

    protected array $message;

    public function __construct(
        public DiscordNotificationData $discordNotificationData,
    ) {
        //
    }

    public function handle(): void
    {
        $this
            ->defineChannelId()
            ->buildMessage()
            ->send();
    }

    public function defineChannelId(): self
    {
        $this->channelId = app(DefineChannelIdAction::class)($this->discordNotificationData->day->date);

        return $this;
    }

    abstract public function buildMessage(): self;

    abstract public function send(): void;
}
