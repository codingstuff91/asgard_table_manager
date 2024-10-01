<?php

namespace App\Contracts;

use App\Models\Table;

interface MessageCreationStrategy
{
    public function handle(int $channelId, array $embedMessage, ?Table $table): string;
}
