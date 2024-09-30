<?php

namespace App\Notifications\Discord;

use App\DataObjects\DiscordNotificationData;

interface NotificationInterface
{
    public function handle(): void;

    public function defineChannelId(DiscordNotificationData $discordNotificationData): self;

    public function buildMessage(DiscordNotificationData $discordNotificationData): self;

    public function send(): void;
}
