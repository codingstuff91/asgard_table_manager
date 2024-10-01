<?php

namespace App\Notifications\Discord;

interface NotificationInterface
{
    public function handle(): void;

    public function defineChannelId(): self;

    public function buildMessage(): self;

    public function send(): void;
}
