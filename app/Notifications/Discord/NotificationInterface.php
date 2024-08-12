<?php

namespace App\Notifications\Discord;

use App\DataObjects\DiscordNotificationData;

interface NotificationInterface
{
    public function create();

    public function buildMessage(DiscordNotificationData $discordNotificationData): array;

    public function send(array $message): void;

}
