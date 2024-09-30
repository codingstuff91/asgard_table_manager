<?php

namespace App\Contracts;

interface MessageCreationStrategy
{
    public function handle(int $channelId, array $embedMessage): string;
}
